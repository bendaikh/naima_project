<?php

namespace App\Services;

use App\Models\BonDeCommande;
use App\Models\BonDeCommandeLigne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BonDeCommandeService
{
    protected PurchaseStockService $stockService;

    public function __construct(PurchaseStockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new bon de commande (purchase order)
     * Note: Creating a bon de commande does NOT affect stock
     *
     * @param array $data
     * @return BonDeCommande
     */
    public function createBonDeCommande(array $data): BonDeCommande
    {
        return DB::transaction(function () use ($data) {
            $bonDeCommande = BonDeCommande::create([
                'fournisseur_id' => $data['fournisseur_id'],
                'order_date' => $data['order_date'],
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'status' => $data['status'] ?? 'DRAFT',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create order lines
            foreach ($data['lignes'] as $ligne) {
                $this->createOrderLine($bonDeCommande, $ligne);
            }

            return $bonDeCommande;
        });
    }

    /**
     * Create an order line
     *
     * @param BonDeCommande $bonDeCommande
     * @param array $ligneData
     * @return BonDeCommandeLigne
     */
    private function createOrderLine(BonDeCommande $bonDeCommande, array $ligneData): BonDeCommandeLigne
    {
        // Handle image upload if provided
        $imagePath = null;
        if (!empty($ligneData['image']) && is_object($ligneData['image'])) {
            $imagePath = $ligneData['image']->store('articles', 'public');
        }

        $data = [
            'bon_de_commande_id' => $bonDeCommande->id,
            'article_id' => $ligneData['article_id'] ?? null,
            'product_name' => $ligneData['product_name'] ?? null,
            'image' => $imagePath,
            'quantity' => $ligneData['quantity'],
            'purchase_price' => $ligneData['purchase_price'],
            'received_quantity' => 0,
        ];

        if (Schema::hasColumn('bons_de_commande_lignes', 'categorie_id')) {
            $data['categorie_id'] = $ligneData['categorie_id'] ?? null;
        }

        return BonDeCommandeLigne::create($data);
    }

    /**
     * Confirm a purchase order (change status from DRAFT to CONFIRMED)
     * Note: Still does NOT affect stock
     *
     * @param BonDeCommande $bonDeCommande
     * @return BonDeCommande
     */
    public function confirmBonDeCommande(BonDeCommande $bonDeCommande): BonDeCommande
    {
        $bonDeCommande->update(['status' => 'CONFIRMED']);
        return $bonDeCommande;
    }

    /**
     * Receive the purchase order
     * This processes stock changes for all received items
     *
     * @param BonDeCommande $bonDeCommande
     * @param array $receivedData - array of [bon_de_commande_ligne_id => received_quantity]
     * @return BonDeCommande
     */
    public function receiveBonDeCommande(BonDeCommande $bonDeCommande, array $receivedData): BonDeCommande
    {
        return DB::transaction(function () use ($bonDeCommande, $receivedData) {
            foreach ($bonDeCommande->lignes as $ligne) {
                $receivedQuantity = $receivedData[$ligne->id] ?? $ligne->quantity;

                // Process the reception which handles stock changes
                $this->stockService->processPurchaseReception($ligne, $receivedQuantity);
            }

            // Update the bon_de_commande status
            $bonDeCommande->update(['status' => 'RECEIVED']);

            return $bonDeCommande;
        });
    }

    /**
     * Complete a purchase order
     *
     * @param BonDeCommande $bonDeCommande
     * @return BonDeCommande
     */
    public function completeBonDeCommande(BonDeCommande $bonDeCommande): BonDeCommande
    {
        $bonDeCommande->update(['status' => 'COMPLETED']);
        return $bonDeCommande;
    }

    /**
     * Cancel a purchase order (only if in DRAFT status)
     *
     * @param BonDeCommande $bonDeCommande
     * @return BonDeCommande
     */
    public function cancelBonDeCommande(BonDeCommande $bonDeCommande): BonDeCommande
    {
        if ($bonDeCommande->status !== 'DRAFT') {
            throw new \Exception('Cannot cancel a purchase order that is not in DRAFT status.');
        }

        $bonDeCommande->update(['status' => 'CANCELLED']);
        return $bonDeCommande;
    }

    /**
     * Update a bon de commande (only if in DRAFT status)
     *
     * @param BonDeCommande $bonDeCommande
     * @param array $data
     * @return BonDeCommande
     */
    public function updateBonDeCommande(BonDeCommande $bonDeCommande, array $data): BonDeCommande
    {
        if ($bonDeCommande->status !== 'DRAFT') {
            throw new \Exception('Cannot update a purchase order that is not in DRAFT status.');
        }

        return DB::transaction(function () use ($bonDeCommande, $data) {
            $bonDeCommande->update([
                'fournisseur_id' => $data['fournisseur_id'] ?? $bonDeCommande->fournisseur_id,
                'order_date' => $data['order_date'] ?? $bonDeCommande->order_date,
                'expected_delivery_date' => $data['expected_delivery_date'] ?? $bonDeCommande->expected_delivery_date,
                'notes' => $data['notes'] ?? $bonDeCommande->notes,
            ]);

            // Update lines if provided
            if (isset($data['lignes'])) {
                // Delete existing lines
                $bonDeCommande->lignes()->delete();

                // Create new lines
                foreach ($data['lignes'] as $ligne) {
                    $this->createOrderLine($bonDeCommande, $ligne);
                }
            }

            return $bonDeCommande;
        });
    }

    /**
     * Get all purchase orders for a supplier
     *
     * @param int $fournisseurId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBonDeCommandeByFournisseur(int $fournisseurId)
    {
        return BonDeCommande::where('fournisseur_id', $fournisseurId)
            ->with('lignes')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    /**
     * Validate bon de commande data
     *
     * @param array $data
     * @return array
     */
    public function validateBonDeCommande(array $data): array
    {
        $errors = [];

        if (empty($data['fournisseur_id'])) {
            $errors[] = 'Supplier is required.';
        }

        if (empty($data['order_date'])) {
            $errors[] = 'Order date is required.';
        }

        if (empty($data['lignes'])) {
            $errors[] = 'At least one product must be ordered.';
            return $errors;
        }

        foreach ($data['lignes'] as $ligne) {
            if (empty($ligne['article_id']) && empty($ligne['product_name'])) {
                $errors[] = 'Each line must have either an article or product name.';
            }

            if (empty($ligne['quantity']) || $ligne['quantity'] <= 0) {
                $errors[] = 'Quantity must be greater than 0.';
            }

            if (empty($ligne['purchase_price']) || $ligne['purchase_price'] <= 0) {
                $errors[] = 'Purchase price must be greater than 0.';
            }
        }

        return $errors;
    }
}
