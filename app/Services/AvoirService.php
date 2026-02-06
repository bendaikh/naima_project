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
     * Only creates avoir if invoice is already paid (CASE 2)
     *
     * @param BonRetour $bonRetour
     * @return Avoir|null
     */
    public function createAvoirFromReturn(BonRetour $bonRetour): ?Avoir
    {
        // Avoir can only be created if:
        // 1. Bon retour is linked to a facture
        // 2. The facture is already paid
        if (!$bonRetour->facture || !$bonRetour->facture->isPaid()) {
            return null;
        }

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
            'description' => "Avoir suite à retour de {$bonRetour->numero()}",
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
        $montantTtc = 0;

        // Get the original invoice lines to match pricing
        $factureId = $bonRetour->facture_id;
        $facture = Facture::find($factureId);

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

        // Calculate TVA and TTC based on facture TVA rate
        $tauxTva = $facture->tva / 100;
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
            // Find corresponding invoice line
            $invoiceLine = $facture->lignes()
                ->where('designation', $retourLine->designation)
                ->first();

            if ($invoiceLine) {
                AvoirLigne::create([
                    'avoir_id' => $avoir->id,
                    'designation' => $retourLine->designation,
                    'quantite' => $retourLine->quantite,
                    'prix_unitaire' => $invoiceLine->prix_unitaire,
                    'montant_ht' => $retourLine->quantite * $invoiceLine->prix_unitaire,
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
