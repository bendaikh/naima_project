<?php

namespace App\Services;

use App\Models\BonLivraison;
use App\Models\BonRetour;
use App\Models\Facture;

class BonRetourService
{
    protected AvoirService $avoirService;

    public function __construct(AvoirService $avoirService)
    {
        $this->avoirService = $avoirService;
    }

    /**
     * Create a new bon de retour
     * Business rule: Must be linked to an existing bon de livraison
     */
    public function create(array $data): BonRetour
    {
        // Validate that the bon de livraison exists
        $bonLivraison = BonLivraison::findOrFail($data['bon_livraison_id']);

        // Validate quantities don't exceed delivered quantities
        $totalReturnQty = collect($data['lignes'] ?? [])->sum('quantite');
        if (!$this->canReturnQuantity($bonLivraison, $totalReturnQty)) {
            throw new \Exception('La quantité retournée dépasse la quantité livrée.');
        }

        // Create bon de retour
        $bonRetour = BonRetour::create([
            'numero' => $this->generateBonRetourNumero(),
            'client_id' => $bonLivraison->client_id,
            'bon_livraison_id' => $bonLivraison->id,
            'facture_id' => $bonLivraison->facture_id ?? null,
            'date' => $data['date'] ?? now(),
            'motif' => $data['motif'] ?? null,
        ]);

        // Create lines
        $this->createLines($bonRetour, $data['lignes'] ?? []);

        // Link to facture if it exists and is paid
        if ($bonRetour->facture && $bonRetour->facture->isPaid()) {
            // Automatically create avoir for paid invoices
            $this->avoirService->createAvoirFromReturn($bonRetour);
        }

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
     * Generate unique bon de retour numero
     */
    private function generateBonRetourNumero(): string
    {
        $lastBon = BonRetour::orderByDesc('id')->first();
        $number = ($lastBon?->id ?? 0) + 1;
        return 'BR-' . date('Y') . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get all returns for a bon de livraison
     */
    public function getRetursForDelivery(BonLivraison $bonLivraison)
    {
        return BonRetour::where('bon_livraison_id', $bonLivraison->id)->get();
    }

    /**
     * Calculate refund amount for a bon de retour
     * (This is used for unpaid invoices - CASE 1)
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
