<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StockMouvement;
use Illuminate\Support\Facades\Auth;

class StockMouvementService
{
    /**
     * Record a stock movement (entry or exit)
     */
    public function recordMovement(
        Article $article,
        string $type, // 'entree' or 'sortie'
        float $quantite,
        string $motif,
        string $referenceType,
        int $referenceId,
        ?string $description = null
    ): StockMouvement
    {
        $mouvement = StockMouvement::create([
            'article_id' => $article->id,
            'type' => $type,
            'quantite' => $quantite,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'motif' => $motif,
            'date' => now()->toDateString(),
            'user_id' => Auth::id(),
            'description' => $description,
        ]);

        return $mouvement;
    }

    /**
     * Record delivery movements (bon de livraison)
     */
    public function recordDelivery($bonLivraison): void
    {
        if ($bonLivraison->lignes) {
            foreach ($bonLivraison->lignes as $ligne) {
                $this->recordMovement(
                    $ligne->article ?? Article::find($ligne->article_id),
                    'sortie', // Delivery = outgoing stock
                    $ligne->quantite,
                    'livraison',
                    'BonLivraison',
                    $bonLivraison->id,
                    "Livraison BL-{$bonLivraison->numero} au client {$bonLivraison->client->nom_raison_sociale}"
                );
            }
        }
    }

    /**
     * Record return movements (bon de retour)
     */
    public function recordReturn($bonRetour): void
    {
        if ($bonRetour->lignes) {
            foreach ($bonRetour->lignes as $ligne) {
                $this->recordMovement(
                    Article::where('nom', $ligne->designation)->first() ?? null,
                    'entree', // Return = incoming stock
                    $ligne->quantite,
                    'retour',
                    'BonRetour',
                    $bonRetour->id,
                    "Retour BR-{$bonRetour->numero} du client {$bonRetour->client->nom_raison_sociale}"
                );
            }
        }
    }

    /**
     * Record manual adjustment (e.g., inventory correction)
     */
    public function recordAdjustment(
        Article $article,
        float $quantite,
        string $reason,
        ?string $description = null
    ): StockMouvement
    {
        $type = $quantite >= 0 ? 'entree' : 'sortie';
        
        return $this->recordMovement(
            $article,
            $type,
            abs($quantite),
            'ajustement',
            'Ajustement',
            0,
            $description ?? "Ajustement manuel: {$reason}"
        );
    }

    /**
     * Get stock history for an article
     */
    public function getArticleHistory(Article $article, ?int $days = null)
    {
        $query = StockMouvement::where('article_id', $article->id)
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        if ($days) {
            $query->where('date', '>=', now()->subDays($days)->toDateString());
        }

        return $query->get();
    }

    /**
     * Get stock balance for an article at a specific date
     */
    public function getStockAtDate(Article $article, $date)
    {
        $entrees = StockMouvement::where('article_id', $article->id)
            ->where('type', 'entree')
            ->where('date', '<=', $date)
            ->sum('quantite');

        $sorties = StockMouvement::where('article_id', $article->id)
            ->where('type', 'sortie')
            ->where('date', '<=', $date)
            ->sum('quantite');

        return $entrees - $sorties;
    }

    /**
     * Get movements for a specific reference
     */
    public function getReferenceMouvements(string $referenceType, int $referenceId)
    {
        return StockMouvement::where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->with('article')
            ->orderByDesc('date')
            ->get();
    }

    /**
     * Get stock summary (entries vs exits)
     */
    public function getStockSummary(Article $article)
    {
        $entrees = StockMouvement::where('article_id', $article->id)
            ->where('type', 'entree')
            ->sum('quantite');

        $sorties = StockMouvement::where('article_id', $article->id)
            ->where('type', 'sortie')
            ->sum('quantite');

        return [
            'entrees' => $entrees,
            'sorties' => $sorties,
            'balance' => $entrees - $sorties,
        ];
    }
}
