<?php

namespace App\Services;

use App\Models\BonLivraison;
use App\Models\BonRetour;
use App\Models\Facture;
use App\Models\ParametresEntreprise;
use Illuminate\Validation\ValidationException;

class FactureService
{
    protected StockMouvementService $stockService;

    public function __construct(StockMouvementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create an invoice based on actual deliveries
     * Business rule: Invoice = Sum(BonLivraison) - Sum(BonRetour)
     *
     * Can be created from:
     * 1. A Devis (invoice all deliveries from that devis)
     * 2. Manually (for ad-hoc invoices not linked to devis)
     */
    public function createFromDeliveries(array $data): Facture
    {
        // Validate inputs
        $bonLivraisonIds = $data['bon_livraison_ids'] ?? [];
        
        if (empty($bonLivraisonIds)) {
            throw ValidationException::withMessages([
                'bon_livraison_ids' => 'Au moins un bon de livraison doit être sélectionné.'
            ]);
        }

        // Validate all bons de livraison are validated
        $bonsLivraison = BonLivraison::whereIn('id', $bonLivraisonIds)
            ->where('statut', '!=', 'validé')
            ->exists();

        if ($bonsLivraison) {
            throw ValidationException::withMessages([
                'bon_livraison' => 'Tous les bons de livraison doivent être validés.'
            ]);
        }

        // Calculate invoice amounts based on actual deliveries and returns
        $invoiceData = $this->calculateInvoiceAmounts($bonLivraisonIds);

        // Generate facture numero
        $params = ParametresEntreprise::first();
        $numero = $params->prefixe_facture . str_pad((string) $params->prochain_numero_facture, 4, '0', STR_PAD_LEFT);

        // Assume all BLs have same client
        $bonLivraison = BonLivraison::find($bonLivraisonIds[0]);

        // Create facture
        $facture = Facture::create([
            'numero' => $numero,
            'client_id' => $bonLivraison->client_id,
            'devis_id' => $bonLivraison->devis_id ?? null,
            'date' => $data['date'] ?? now()->toDateString(),
            'date_echeance' => $data['date_echeance'] ?? now()->addMonth()->toDateString(),
            'tva' => $invoiceData['tva_rate'],
            'total_ht' => $invoiceData['total_ht'],
            'total_ttc' => $invoiceData['total_ttc'],
            'montant_paye' => 0,
            'statut' => 'non_payee',
        ]);

        // Create invoice lines from deliveries minus returns
        $this->createInvoiceLines($facture, $bonLivraisonIds);

        // Link bon livraisons to facture
        BonLivraison::whereIn('id', $bonLivraisonIds)->update(['facture_id' => $facture->id]);

        $params->increment('prochain_numero_facture');

        return $facture;
    }

    /**
     * Calculate invoice amounts based on delivered vs returned
     * Formula: Delivered quantity - Returned quantity
     */
    private function calculateInvoiceAmounts(array $bonLivraisonIds): array
    {
        $totalHt = 0;
        $totalQty = 0;

        // Get all delivery lines
        $deliveryLines = BonLivraison::whereIn('id', $bonLivraisonIds)
            ->with('lignes')
            ->get()
            ->flatMap(fn($bl) => $bl->lignes);

        // Group by article and sum quantities
        $articles = $deliveryLines->groupBy('designation')
            ->map(fn($group) => [
                'designation' => $group->first()->designation,
                'quantite' => $group->sum('quantite'),
            ]);

        // Calculate amounts from facture lines (use first BL's prices as reference)
        $firstBl = BonLivraison::find($bonLivraisonIds[0]);
        $tvaRate = 20; // Default, should be configurable

        foreach ($articles as $article) {
            // Find corresponding facture line if exists (from linked devis)
            if ($firstBl->devis && $firstBl->devis->lignes) {
                $devisLine = $firstBl->devis->lignes()
                    ->where('designation', $article['designation'])
                    ->first();

                if ($devisLine) {
                    $lineAmount = $article['quantite'] * $devisLine->prix_unitaire;
                    $totalHt += $lineAmount;
                    $totalQty += $article['quantite'];
                    $tvaRate = $devisLine->tva;
                }
            }
        }

        // Subtract returned quantities
        $returnedLines = BonRetour::whereIn('bon_livraison_id', $bonLivraisonIds)
            ->with('lignes')
            ->get()
            ->flatMap(fn($br) => $br->lignes);

        foreach ($returnedLines as $line) {
            $devisLine = $firstBl->devis->lignes()
                ->where('designation', $line->designation)
                ->first();

            if ($devisLine) {
                $returnAmount = $line->quantite * $devisLine->prix_unitaire;
                $totalHt -= $returnAmount;
                $totalQty -= $line->quantite;
            }
        }

        // Calculate TVA and TTC
        $tvaAmount = $totalHt * ($tvaRate / 100);
        $totalTtc = $totalHt + $tvaAmount;

        return [
            'total_ht' => round($totalHt, 2),
            'tva_rate' => $tvaRate,
            'tva' => round($tvaAmount, 2),
            'total_ttc' => round($totalTtc, 2),
        ];
    }

    /**
     * Create invoice lines from deliveries (minus returns)
     */
    private function createInvoiceLines(Facture $facture, array $bonLivraisonIds): void
    {
        // Get all delivery lines grouped by designation
        $deliveryLines = BonLivraison::whereIn('id', $bonLivraisonIds)
            ->with('lignes')
            ->get()
            ->flatMap(fn($bl) => $bl->lignes)
            ->groupBy('designation');

        // Get all return lines grouped by designation
        $returnLines = BonRetour::whereIn('bon_livraison_id', $bonLivraisonIds)
            ->with('lignes')
            ->get()
            ->flatMap(fn($br) => $br->lignes)
            ->groupBy('designation');

        $firstBl = BonLivraison::find($bonLivraisonIds[0]);

        // Create facture lines
        foreach ($deliveryLines as $designation => $lines) {
            $deliveredQty = $lines->sum('quantite');
            $returnedQty = $returnLines[$designation]?->sum('quantite') ?? 0;
            $netQty = $deliveredQty - $returnedQty;

            if ($netQty > 0) {
                // Get price from devis
                $devisLine = $firstBl->devis->lignes()
                    ->where('designation', $designation)
                    ->first();

                if ($devisLine) {
                    \App\Models\FactureLigne::create([
                        'facture_id' => $facture->id,
                        'designation' => $designation,
                        'quantite' => $netQty,
                        'prix_unitaire' => $devisLine->prix_unitaire,
                        'tva' => $devisLine->tva,
                        'total_ht' => round($netQty * $devisLine->prix_unitaire, 2),
                    ]);
                }
            }
        }
    }

    /**
     * Recalculate invoice when unpaid and return occurs
     * Business rule CASE A: If invoice NOT paid, recalculate automatically
     */
    public function recalculateForUnpaidInvoice(Facture $facture): void
    {
        if ($facture->isPaid()) {
            // Paid invoices don't get recalculated; avoir is created instead
            return;
        }

        // Get bon livraisons linked to this facture
        $bonLivraisonIds = $facture->bonsLivraison()->pluck('id')->toArray();

        if (empty($bonLivraisonIds)) {
            return;
        }

        // Recalculate amounts
        $invoiceData = $this->calculateInvoiceAmounts($bonLivraisonIds);

        // Update facture
        $facture->update([
            'total_ht' => $invoiceData['total_ht'],
            'total_ttc' => $invoiceData['total_ttc'],
            'tva' => $invoiceData['tva'],
        ]);

        // Update or recreate lines
        $facture->lignes()->delete();
        $this->createInvoiceLines($facture, $bonLivraisonIds);
    }

    /**
     * Mark invoice as paid
     * This enables creation of avoir for any subsequent returns
     */
    public function markAsPaid(Facture $facture, float $amount): void
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'montant_paye' => 'Le montant payé doit être positif.'
            ]);
        }

        $facture->update([
            'montant_paye' => $amount,
            'statut' => $amount >= $facture->total_ttc ? 'payee' : 'partiellement_payee',
        ]);
    }
}
