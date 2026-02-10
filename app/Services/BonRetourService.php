<?php

namespace App\Services;

use App\Models\BonLivraison;
use App\Models\BonRetour;
use App\Models\Facture;
use Illuminate\Validation\ValidationException;

class BonRetourService
{
    protected AvoirService $avoirService;
    protected FactureService $factureService;
    protected StockMouvementService $stockService;

    public function __construct(AvoirService $avoirService, FactureService $factureService, StockMouvementService $stockService)
    {
        $this->avoirService = $avoirService;
        $this->factureService = $factureService;
        $this->stockService = $stockService;
    }

    /**
     * Create a new bon de retour
     * Business rules:
     * - Must be linked to an existing bon de livraison
     * - Cannot return more than delivered minus previously returned
     * - Stock increases immediately when BR is created
     * - Triggers avoir creation OR invoice recalculation based on payment status
     */
    public function create(array $data): BonRetour
    {
        // Validate that the bon de livraison exists and is validated
        $bonLivraison = BonLivraison::findOrFail($data['bon_livraison_id']);

        if ($bonLivraison->statut !== 'validé') {
            throw ValidationException::withMessages([
                'bon_livraison' => 'Le bon de livraison doit être validé.'
            ]);
        }

        // Validate quantities don't exceed delivered quantities
        $totalReturnQty = collect($data['lignes'] ?? [])->sum('quantite');
        if (!$this->canReturnQuantity($bonLivraison, $totalReturnQty)) {
            throw ValidationException::withMessages([
                'quantite' => 'La quantité retournée dépasse la quantité livrée.'
            ]);
        }

        // Generate numero
        $params = \App\Models\ParametresEntreprise::first();
        $numero = $params->prefixe_bon_retour . str_pad((string) $params->prochain_numero_bon_retour, 4, '0', STR_PAD_LEFT);

        // Create bon de retour
        $bonRetour = BonRetour::create([
            'numero' => $numero,
            'client_id' => $bonLivraison->client_id,
            'bon_livraison_id' => $bonLivraison->id,
            'facture_id' => $bonLivraison->facture_id ?? null,
            'date' => $data['date'] ?? now(),
            'motif' => $data['motif'] ?? null,
        ]);

        // Create lines
        $this->createLines($bonRetour, $data['lignes'] ?? []);

        // STOCK: Record stock movements (increment due to return)
        $bonRetour->incrementStock();

        // Handle avoir/invoice updates
        if ($bonRetour->facture) {
            if ($bonRetour->facture->isPaid()) {
                // CASE B: Invoice PAID → Create avoir (financial correction)
                $this->avoirService->createAvoirFromReturn($bonRetour);
            } else {
                // CASE A: Invoice NOT PAID → Recalculate invoice total
                // Formula: Total delivered − Total returned
                $this->factureService->recalculateForUnpaidInvoice($bonRetour->facture);
            }
        } else {
            // CASE C: No invoice yet → Create avoir for customer credit
            $this->avoirService->createAvoirFromReturn($bonRetour);
        }

        $params->increment('prochain_numero_bon_retour');

        return $bonRetour;
    }


    /**
     * Update bon de retour (only if not yet processed)
     */
    public function update(BonRetour $bonRetour, array $data): BonRetour
    {
        $bonRetour->update([
            'date' => $data['date'] ?? $bonRetour->date,
            'motif' => $data['motif'] ?? $bonRetour->motif,
        ]);

        // Update lines if provided
        if (isset($data['lignes'])) {
            $bonRetour->lignes()->delete();
            $this->createLines($bonRetour, $data['lignes']);
        }

        return $bonRetour;
    }

    /**
     * Create bon de retour lines
     */
    private function createLines(BonRetour $bonRetour, array $lignes): void
    {
        foreach ($lignes as $ligne) {
            $bonRetour->lignes()->create([
                'designation' => $ligne['designation'],
                'quantite' => $ligne['quantite'],
            ]);
        }
    }

    /**
     * Check if quantity can be returned for this bon de livraison
     * Business rule: Cannot return more than delivered minus previously returned
     */
    private function canReturnQuantity(BonLivraison $bonLivraison, float $qty): bool
    {
        $deliveredQty = $bonLivraison->lignes()->sum('quantite');
        $alreadyReturnedQty = BonRetour::where('bon_livraison_id', $bonLivraison->id)
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));

        return ($qty + $alreadyReturnedQty) <= $deliveredQty;
    }

    /**
     * Get all returns for a bon de livraison
     */
    public function getReturnsForDelivery(BonLivraison $bonLivraison)
    {
        return BonRetour::where('bon_livraison_id', $bonLivraison->id)->get();
    }

    /**
     * Calculate refund amount for a bon de retour
     * Used for displaying refund value
     */
    public function calculateRefundAmount(BonRetour $bonRetour): float
    {
        $amount = 0;

        if (!$bonRetour->facture) {
            return $amount;
        }

        $facture = $bonRetour->facture;

        // Calculate refund based on returned quantities and invoice prices
        foreach ($bonRetour->lignes as $retourLine) {
            $invoiceLine = $facture->lignes()
                ->where('designation', $retourLine->designation)
                ->first();

            if ($invoiceLine) {
                $amount += $retourLine->quantite * $invoiceLine->prix_unitaire;
            }
        }

        return round($amount, 2);
    }
}
