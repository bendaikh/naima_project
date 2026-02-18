<?php

namespace App\Services;

use App\Models\AvoirFournisseur;
use App\Models\BonDeCommandeLigne;
use App\Models\BonRetourFournisseur;
use App\Models\BonRetourFournisseurLigne;
use Illuminate\Support\Facades\DB;

class PurchaseReturnService
{
    protected PurchaseStockService $stockService;

    public function __construct(PurchaseStockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a purchase return for supplier
     *
     * @param array $data
     * @return BonRetourFournisseur
     */
    public function createPurchaseReturn(array $data): BonRetourFournisseur
    {
        return DB::transaction(function () use ($data) {
            // Create the bon_retour_fournisseur
            $bonRetour = BonRetourFournisseur::create([
                'bon_de_commande_id' => $data['bon_de_commande_id'],
                'fournisseur_id' => $data['fournisseur_id'],
                'return_date' => $data['return_date'],
                'return_type' => $data['return_type'],
                'return_reason' => $data['return_reason'] ?? null,
                'status' => 'PENDING',
                'notes' => $data['notes'] ?? null,
            ]);

            // Process each return line
            foreach ($data['lignes'] as $ligne) {
                $this->createReturnLine($bonRetour, $ligne);
            }

            return $bonRetour;
        });
    }

    /**
     * Create a return line and immediately process stock reduction
     *
     * @param BonRetourFournisseur $bonRetour
     * @param array $ligneData
     * @return BonRetourFournisseurLigne
     */
    private function createReturnLine(BonRetourFournisseur $bonRetour, array $ligneData): BonRetourFournisseurLigne
    {
        $bonDeCommandeLigne = $bonRetour->bonDeCommande->lignes()
            ->where('id', $ligneData['bon_de_commande_ligne_id'])
            ->firstOrFail();

        // Create the return line
        $returnLigne = BonRetourFournisseurLigne::create([
            'bon_retour_fournisseur_id' => $bonRetour->id,
            'bon_de_commande_ligne_id' => $bonDeCommandeLigne->id,
            'article_id' => $bonDeCommandeLigne->article_id,
            'quantity_received' => $bonDeCommandeLigne->received_quantity,
            'quantity_already_returned' => $bonDeCommandeLigne->getAlreadyReturnedQuantity(),
            'return_quantity' => $ligneData['return_quantity'],
        ]);

        // Process stock reduction immediately upon creation
        $this->stockService->processPurchaseReturn($bonDeCommandeLigne, $ligneData['return_quantity']);

        return $returnLigne;
    }

    /**
     * Complete a purchase return
     *
     * @param BonRetourFournisseur $bonRetour
     * @return BonRetourFournisseur
     */
    public function completePurchaseReturn(BonRetourFournisseur $bonRetour): BonRetourFournisseur
    {
        return DB::transaction(function () use ($bonRetour) {
            $bonRetour->update(['status' => 'COMPLETED']);

            // If return type is REFUND, create Avoir Fournisseur
            if ($bonRetour->isRefund()) {
                $this->createSupplierAvoir($bonRetour);
            }

            return $bonRetour;
        });
    }

    /**
     * Create Avoir Fournisseur for REFUND type returns
     *
     * @param BonRetourFournisseur $bonRetour
     * @return AvoirFournisseur
     */
    public function createSupplierAvoir(BonRetourFournisseur $bonRetour): AvoirFournisseur
    {
        $totalAmount = $bonRetour->getTotalReturnedAmount();

        return AvoirFournisseur::create([
            'fournisseur_id' => $bonRetour->fournisseur_id,
            'bon_retour_fournisseur_id' => $bonRetour->id,
            'avoir_date' => now()->toDateString(),
            'amount' => $totalAmount,
            'status' => 'PENDING',
            'notes' => "Credit note for return from purchase order #{$bonRetour->bonDeCommande->id}",
        ]);
    }

    /**
     * Process replacement reception
     * Called when replacement products are received for a REPLACEMENT return
     *
     * @param BonRetourFournisseur $bonRetour
     * @param array $replacementData
     * @return void
     */
    public function processReplacementReception(BonRetourFournisseur $bonRetour, array $replacementData): void
    {
        DB::transaction(function () use ($bonRetour, $replacementData) {
            if (!$bonRetour->isReplacement()) {
                throw new \Exception('This return is not a REPLACEMENT type.');
            }

            foreach ($bonRetour->lignes as $returnLigne) {
                $bonDeCommandeLigne = $returnLigne->bonDeCommandeLigne;
                
                // The replacement quantity matches the returned quantity
                $replacementQuantity = $returnLigne->return_quantity;

                $this->stockService->processReplacementReception($bonDeCommandeLigne, $replacementQuantity);
            }
        });
    }

    /**
     * Mark Avoir as received (payment received from supplier)
     *
     * @param AvoirFournisseur $avoir
     * @return AvoirFournisseur
     */
    public function markAvoirAsReceived(AvoirFournisseur $avoir): AvoirFournisseur
    {
        $avoir->update(['status' => 'RECEIVED']);
        return $avoir;
    }

    /**
     * Get return validation errors
     *
     * @param array $data
     * @return array
     */
    public function validateReturn(array $data): array
    {
        $errors = [];

        if (empty($data['lignes'])) {
            $errors[] = 'At least one product must be returned.';
            return $errors;
        }

        foreach ($data['lignes'] as $ligne) {
            $bonDeCommandeLigne = BonDeCommandeLigne::find($ligne['bon_de_commande_ligne_id']);
            
            if (!$bonDeCommandeLigne) {
                $errors[] = "Invalid bon_de_commande_ligne_id: {$ligne['bon_de_commande_ligne_id']}";
                continue;
            }

            $availableQuantity = $this->stockService->getAvailableReturnQuantity($bonDeCommandeLigne);
            
            if ($ligne['return_quantity'] > $availableQuantity) {
                $errors[] = "Return quantity ({$ligne['return_quantity']}) exceeds available ({$availableQuantity}) for article {$bonDeCommandeLigne->product_name}.";
            }
        }

        return $errors;
    }
}
