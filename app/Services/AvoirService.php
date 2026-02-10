<?php

namespace App\Services;

use App\Models\Avoir;
use App\Models\AvoirLigne;
use App\Models\BonRetour;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Collection;

class AvoirService
{
    /**
     * Create an avoir for a returned bon de retour
     * Creates avoir for customer credit (whether or not invoice exists/is paid)
     *
     * @param BonRetour $bonRetour
     * @return Avoir
     */
    public function createAvoirFromReturn(BonRetour $bonRetour): Avoir
    {
        // Generate avoir numero
        $numero = $this->generateAvoirNumero();

        // Calculate avoir amounts from returned items
        $avoirData = $this->calculateAvoirAmounts($bonRetour);

        // Create avoir record
        $avoir = Avoir::create([
            'numero' => $numero,
            'client_id' => $bonRetour->client_id,
            'facture_id' => $bonRetour->facture_id,
            'bon_retour_id' => $bonRetour->id,
            'date' => now(),
            'montant_ht' => $avoirData['montant_ht'],
            'tva' => $avoirData['tva'],
            'montant_ttc' => $avoirData['montant_ttc'],
            'statut' => 'brouillon',
            'description' => "Avoir suite à retour de {$bonRetour->numero}",
        ]);

        // Create avoir lines from bon retour lines
        $this->createAvoirLines($avoir, $bonRetour);

        return $avoir;
    }

    /**
     * Calculate avoir amounts based on returned quantities and original prices
     */
    private function calculateAvoirAmounts(BonRetour $bonRetour): array
    {
        $montantHt = 0;
        $tauxTva = 0.20; // Default 20% TVA

        // Try to get pricing from facture if it exists
        if ($bonRetour->facture) {
            $facture = $bonRetour->facture;
            $tauxTva = $facture->tva / 100;

            // Calculate amount for each returned line
            foreach ($bonRetour->lignes as $retourLine) {
                // Find corresponding invoice line to get the price
                $invoiceLine = $facture->lignes()
                    ->where('designation', $retourLine->designation)
                    ->first();

                if ($invoiceLine) {
                    // Calculate amount for this returned quantity
                    $lineAmount = $retourLine->quantite * $invoiceLine->prix_unitaire;
                    $montantHt += $lineAmount;
                }
            }
        } else {
            // No facture - get prices from articles if available
            foreach ($bonRetour->lignes as $retourLine) {
                // Try to find article by designation
                $article = \App\Models\Article::where('nom', $retourLine->designation)->first();
                if ($article) {
                    $lineAmount = $retourLine->quantite * $article->prix_vente;
                    $montantHt += $lineAmount;
                } else {
                    // If no article found, assume minimum price (can be updated later)
                    $lineAmount = $retourLine->quantite * 0;
                    $montantHt += $lineAmount;
                }
            }
        }

        // Calculate TVA and TTC
        $tva = $montantHt * $tauxTva;
        $montantTtc = $montantHt + $tva;

        return [
            'montant_ht' => round($montantHt, 2),
            'tva' => round($tva, 2),
            'montant_ttc' => round($montantTtc, 2),
        ];
    }

    /**
     * Create avoir line items from bon retour lines
     */
    private function createAvoirLines(Avoir $avoir, BonRetour $bonRetour): void
    {
        $facture = $bonRetour->facture;

        foreach ($bonRetour->lignes as $retourLine) {
            $prixUnitaire = 0;

            if ($facture) {
                // Find corresponding invoice line
                $invoiceLine = $facture->lignes()
                    ->where('designation', $retourLine->designation)
                    ->first();

                if ($invoiceLine) {
                    $prixUnitaire = $invoiceLine->prix_unitaire;
                }
            } else {
                // Try to find article price
                $article = \App\Models\Article::where('nom', $retourLine->designation)->first();
                if ($article) {
                    $prixUnitaire = $article->prix_vente;
                }
            }

            if ($prixUnitaire > 0) {
                AvoirLigne::create([
                    'avoir_id' => $avoir->id,
                    'designation' => $retourLine->designation,
                    'quantite' => $retourLine->quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'montant_ht' => $retourLine->quantite * $prixUnitaire,
                ]);
            }
        }
    }

    /**
     * Generate unique avoir numero
     */
    private function generateAvoirNumero(): string
    {
        $lastAvoir = Avoir::orderByDesc('id')->first();
        $number = ($lastAvoir?->id ?? 0) + 1;
        return 'AV-' . date('Y') . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get all avoirs for a facture
     */
    public function getAvoirsForFacture(Facture $facture): Collection
    {
        return $facture->avoirs()->get();
    }

    /**
     * Calculate total avoir amount for a facture
     */
    public function getTotalAvoirAmount(Facture $facture): float
    {
        return $facture->avoirs()
            ->where('statut', '!=', 'brouillon')
            ->sum('montant_ttc');
    }
}
