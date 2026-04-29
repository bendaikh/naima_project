<?php

namespace App\Services;

use App\Models\Article;
use App\Models\BonDeCommandeLigne;
use App\Models\StockMouvement;
use Illuminate\Support\Facades\DB;

class PurchaseStockService
{
    /**
     * Process the reception of a purchase order
     * - For existing articles: increase stock
     * - For new articles: create them first, then add to stock
     *
     * @param BonDeCommandeLigne $ligne
     * @param float $receivedQuantity
     * @return void
     */
    public function processPurchaseReception(BonDeCommandeLigne $ligne, float $receivedQuantity): void
    {
        DB::transaction(function () use ($ligne, $receivedQuantity) {
            $article = $ligne->article;

            // If article doesn't exist, create it
            if (!$article) {
                $article = Article::create([
                    'nom' => $ligne->designation,
                    'description' => 'Created from purchase order',
                    'prix_achat' => $ligne->purchase_price,
                    'prix_vente' => $ligne->purchase_price * 1.5, // Default markup
                    'quantite' => $ligne->quantity,
                    'quantite_stock' => 0,
                    'unite' => 'pcs', // Default unit, can be customized
                    'categorie_id' => $ligne->categorie_id,
                    'image' => $ligne->image,
                ]);

                // Update the bon_de_commande_ligne to reference the created article
                $ligne->update(['article_id' => $article->id]);
            } else {
                $articleUpdates = [];
                if (!$article->categorie_id && $ligne->categorie_id) {
                    $articleUpdates['categorie_id'] = $ligne->categorie_id;
                }
                if (!$article->image && $ligne->image) {
                    $articleUpdates['image'] = $ligne->image;
                }
                if (!empty($articleUpdates)) {
                    $article->update($articleUpdates);
                }
            }

            // Update the received_quantity in the bon_de_commande_ligne
            $currentReceived = (float)($ligne->received_quantity ?? 0);
            $ligne->update(['received_quantity' => $currentReceived + $receivedQuantity]);

            // Increase stock
            $article->increment('quantite_stock', $receivedQuantity);

            // Record the stock movement
            StockMouvement::create([
                'article_id' => $article->id,
                'type' => 'entree',
                'quantite' => $receivedQuantity,
                'reference_type' => 'BonDeCommande',
                'reference_id' => $ligne->bon_de_commande_id,
                'motif' => 'reception_achat',
                'date' => now()->toDateString(),
                'user_id' => auth()->id(),
                'description' => "Reception from purchase order #{$ligne->bonDeCommande->id}",
            ]);
        });
    }

    /**
     * Process the return of purchased items
     * - Decrease stock for returned quantities
     *
     * @param BonDeCommandeLigne $ligne
     * @param float $returnQuantity
     * @return void
     */
    public function processPurchaseReturn(BonDeCommandeLigne $ligne, float $returnQuantity): void
    {
        DB::transaction(function () use ($ligne, $returnQuantity) {
            $article = $ligne->article;

            if (!$article) {
                throw new \Exception("Article not found for return line ID: {$ligne->id}");
            }

            // Decrease stock
            $article->decrement('quantite_stock', $returnQuantity);

            // Record the stock movement
            StockMouvement::create([
                'article_id' => $article->id,
                'type' => 'sortie',
                'quantite' => $returnQuantity,
                'reference_type' => 'BonRetourFournisseur',
                'reference_id' => $ligne->bonDeCommande->retours()->first()->id ?? null,
                'motif' => 'retour_fournisseur',
                'date' => now()->toDateString(),
                'user_id' => auth()->id(),
                'description' => "Return to supplier from purchase order #{$ligne->bonDeCommande->id}",
            ]);
        });
    }

    /**
     * Process replacement reception (when returning product type is REPLACEMENT)
     * - Increase stock for replacement quantities
     *
     * @param BonDeCommandeLigne $ligne
     * @param float $replacementQuantity
     * @return void
     */
    public function processReplacementReception(BonDeCommandeLigne $ligne, float $replacementQuantity): void
    {
        DB::transaction(function () use ($ligne, $replacementQuantity) {
            $article = $ligne->article;

            if (!$article) {
                throw new \Exception("Article not found for replacement line ID: {$ligne->id}");
            }

            // Increase stock
            $article->increment('quantite_stock', $replacementQuantity);

            // Record the stock movement
            StockMouvement::create([
                'article_id' => $article->id,
                'type' => 'entree',
                'quantite' => $replacementQuantity,
                'reference_type' => 'BonRetourFournisseur',
                'reference_id' => $ligne->bonDeCommande->retours()->first()->id ?? null,
                'motif' => 'reception_remplacement',
                'date' => now()->toDateString(),
                'user_id' => auth()->id(),
                'description' => "Replacement reception for purchase order #{$ligne->bonDeCommande->id}",
            ]);
        });
    }

    /**
     * Get current stock for an article
     *
     * @param Article $article
     * @return float
     */
    public function getCurrentStock(Article $article): float
    {
        return $article->quantite_stock ?? 0;
    }

    /**
     * Get stock history for an article
     *
     * @param Article $article
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockHistory(Article $article)
    {
        return StockMouvement::where('article_id', $article->id)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Calculate available stock for return (received - already returned)
     *
     * @param BonDeCommandeLigne $ligne
     * @return float
     */
    public function getAvailableReturnQuantity(BonDeCommandeLigne $ligne): float
    {
        return $ligne->received_quantity - $ligne->getAlreadyReturnedQuantity();
    }
}
