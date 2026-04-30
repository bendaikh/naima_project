<?php

namespace App\Services;

use App\Models\BonLivraison;
use App\Models\Article;
use App\Models\Devis;
use App\Models\ParametresEntreprise;
use Illuminate\Validation\ValidationException;

class BonLivraisonService
{
    protected StockMouvementService $stockService;

    public function __construct(StockMouvementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new Bon de Livraison from an accepted Devis
     * Business rule: Can create multiple BLs from one devis for partial deliveries
     */
    public function createFromDevis(Devis $devis, array $data): BonLivraison
    {
        // Validate devis is accepted
        if ($devis->statut !== 'accepte') {
            throw ValidationException::withMessages([
                'devis' => 'Le devis doit être accepté pour créer un bon de livraison.'
            ]);
        }

        // Validate that quantities don't exceed devis quantities
        $this->validateDeliveryQuantities($devis, $data['lignes'] ?? []);

        // Generate BL numero
        $params = ParametresEntreprise::first();
        $numero = $params->generateDocumentNumber('bon_livraison', $data['date'] ?? now());

        // Create bon de livraison
        $bonLivraison = BonLivraison::create([
            'numero' => $numero,
            'client_id' => $devis->client_id,
            'devis_id' => $devis->id,
            'facture_id' => $data['facture_id'] ?? null,
            'date' => $data['date'] ?? now()->toDateString(),
            'statut' => 'en_attente', // Waiting for validation
        ]);

        // Create lines (without stock impact yet)
        $this->createLines($bonLivraison, $data['lignes'] ?? []);

        $params->increment('prochain_numero_bon_livraison');

        return $bonLivraison;
    }

    /**
     * Validate bon de livraison (final signature)
     * This is when stock is actually decremented
     */
    public function validate(BonLivraison $bonLivraison): void
    {
        \Log::info('BonLivraisonService::validate called', [
            'bon_id' => $bonLivraison->id,
            'current_statut' => $bonLivraison->statut
        ]);
        
        // If already validated, do nothing
        if ($bonLivraison->statut === 'validé') {
            \Log::info('Already validated, skipping', ['bon_id' => $bonLivraison->id]);
            return;
        }

        // Validate articles exist and have sufficient stock
        \Log::info('Validating stock availability', ['bon_id' => $bonLivraison->id]);
        $this->validateStockAvailability($bonLivraison);

        // Mark as validated (signed)
        \Log::info('Updating status to validé', ['bon_id' => $bonLivraison->id]);
        $bonLivraison->update(['statut' => 'validé']);

        // NOW: Record stock movements (decrement)
        \Log::info('Decrementing stock', ['bon_id' => $bonLivraison->id]);
        $bonLivraison->decrementStock();
        \Log::info('Validation complete', ['bon_id' => $bonLivraison->id]);
    }

    /**
     * Reverse/cancel a validated bon de livraison
     * This returns stock to inventory
     */
    public function cancel(BonLivraison $bonLivraison): void
    {
        if ($bonLivraison->statut !== 'validé') {
            throw ValidationException::withMessages([
                'statut' => 'Seuls les bons de livraison validés peuvent être annulés.'
            ]);
        }

        // Prevent cancellation if returns exist
        if ($bonLivraison->bonsRetour()->exists()) {
            throw ValidationException::withMessages([
                'bons_retour' => 'Impossible d\'annuler un bon de livraison avec des retours.'
            ]);
        }

        // Return stock to inventory
        $bonLivraison->incrementStock();

        // Mark as cancelled
        $bonLivraison->update(['statut' => 'annulé']);
    }

    /**
     * Validate stock availability before delivery
     */
    private function validateStockAvailability(BonLivraison $bonLivraison): void
    {
        foreach ($bonLivraison->lignes as $ligne) {
            // Skip validation for manual articles (not in the articles table)
            if (!$ligne->article_id) {
                continue;
            }

            // Find article by designation
            $article = \App\Models\Article::where('nom', $ligne->designation)->first();

            if (!$article) {
                throw ValidationException::withMessages([
                    'article' => "Article '{$ligne->designation}' non trouvé."
                ]);
            }

            if ($article->quantite_stock < $ligne->quantite) {
                throw ValidationException::withMessages([
                    'stock' => "Stock insuffisant pour '{$article->nom}'. Disponible: {$article->quantite_stock}, Demandé: {$ligne->quantite}"
                ]);
            }
        }
    }

    /**
     * Validate delivery quantities don't exceed devis quantities
     */
    private function validateDeliveryQuantities(Devis $devis, array $lignes, ?BonLivraison $excludeBon = null): void
    {
        foreach ($lignes as $ligne) {
            $devisLigne = $devis->lignes()->where('designation', $ligne['designation'])->first();

            if (!$devisLigne) {
                throw ValidationException::withMessages([
                    'lignes' => "L'article '{$ligne['designation']}' n'existe pas dans le devis."
                ]);
            }

            // Calculate already delivered from this devis (including drafts and validated, excluding cancelled)
            $query = BonLivraison::where('devis_id', $devis->id)
                ->whereNotIn('statut', ['annulé', 'annule'])
                ->with('lignes');
            
            // Exclude current bon if editing
            if ($excludeBon) {
                $query->where('id', '!=', $excludeBon->id);
            }

            $alreadyDelivered = $query->get()
                ->flatMap(fn($bl) => $bl->lignes->where('designation', $ligne['designation']))
                ->sum('quantite');

            $totalToDeliver = $alreadyDelivered + ($ligne['quantite'] ?? 0);

            if ($totalToDeliver > $devisLigne->quantite) {
                throw ValidationException::withMessages([
                    'quantite' => "Total livré + demandé ({$totalToDeliver}) dépasse le devis ({$devisLigne->quantite}). Déjà livré/planifié: {$alreadyDelivered}, Disponible: " . ($devisLigne->quantite - $alreadyDelivered)
                ]);
            }
        }
    }

    /**
     * Create bon de livraison lines
     */
    private function createLines(BonLivraison $bonLivraison, array $lignes): void
    {
        foreach ($lignes as $ligne) {
            $article = Article::where('nom', $ligne['designation'])->first();
            $bonLivraison->lignes()->create([
                'article_id' => $article?->id,
                'designation' => $ligne['designation'],
                'reference' => $ligne['reference'] ?? $article?->ugs,
                'quantite' => $ligne['quantite'],
            ]);
        }
    }
}
