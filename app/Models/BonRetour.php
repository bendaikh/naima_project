<?php

namespace App\Models;

use App\Services\StockMouvementService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Article;

class BonRetour extends Model
{
    protected $table = 'bons_retour';

    protected $fillable = [
        'numero',
        'client_id',
        'bon_livraison_id',
        'facture_id',
        'date',
        'motif',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bonLivraison(): BelongsTo
    {
        return $this->belongsTo(BonLivraison::class, 'bon_livraison_id');
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(BonRetourLigne::class, 'bon_retour_id');
    }

    /**
     * Calculate total returned quantity
     */
    public function getTotalReturnedQuantity(): decimal|float
    {
        return $this->lignes()->sum('quantite');
    }

    /**
     * Check if return is valid (not more than delivered)
     */
    public function isValid(): bool
    {
        $deliveredQty = $this->bonLivraison?->lignes()?->sum('quantite') ?? 0;
        $currentReturnedQty = $this->getTotalReturnedQuantity();
        $otherReturnsQty = BonRetour::where('bon_livraison_id', $this->bon_livraison_id)
            ->where('id', '!=', $this->id)
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->getTotalReturnedQuantity());

        return ($currentReturnedQty + $otherReturnsQty) <= $deliveredQty;
    }

    /**
     * Prevent returning more than delivered quantity
     */
    public function canReturnQuantity(float $qty): bool
    {
        $deliveredQty = $this->bonLivraison?->lignes()?->sum('quantite') ?? 0;
        $otherReturnsQty = BonRetour::where('bon_livraison_id', $this->bon_livraison_id)
            ->where('id', '!=', $this->id)
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->getTotalReturnedQuantity());

        return ($qty + $otherReturnsQty) <= $deliveredQty;
    }

    /**
     * Link bon retour to facture if not already linked
     */
    public function linkToFacture(Facture $facture): void
    {
        if ($this->bonLivraison && $this->bonLivraison->facture_id === $facture->id) {
            $this->update(['facture_id' => $facture->id]);
        }
    }

    /**
     * Increment stock for all articles in this return
     */
    public function incrementStock(): void
    {
        $service = app(StockMouvementService::class);
        
        // Reload lignes to ensure they're fresh from database
        $this->refresh();
        $lignes = $this->lignes()->get();
        
        foreach ($lignes as $ligne) {
            // Find the article by designation or id
            $article = null;
            if (isset($ligne->article_id) && $ligne->article_id) {
                $article = Article::find($ligne->article_id);
            } else {
                $article = Article::where('nom', $ligne->designation)->first();
            }
            
            if ($article) {
                $article->increment('quantite_stock', $ligne->quantite);
                
                // Record the stock movement
                $service->recordMovement(
                    $article,
                    'entree',
                    $ligne->quantite,
                    'retour',
                    'BonRetour',
                    $this->id,
                    "Retour BR-{$this->numero}"
                );
            }
        }
    }

    /**
     * Decrement stock for all articles in this return (reverse operation)
     */
    public function decrementStock(): void
    {
        $service = app(StockMouvementService::class);
        
        foreach ($this->lignes as $ligne) {
            // Find the article by designation or id
            $article = null;
            if (isset($ligne->article_id) && $ligne->article_id) {
                $article = Article::find($ligne->article_id);
            } else {
                $article = Article::where('nom', $ligne->designation)->first();
            }
            
            if ($article) {
                $article->decrement('quantite_stock', $ligne->quantite);
                
                // Record the stock movement (reverse)
                $service->recordMovement(
                    $article,
                    'sortie',
                    $ligne->quantite,
                    'retour',
                    'BonRetour',
                    $this->id,
                    "Annulation retour BR-{$this->numero}"
                );
            }
        }
    }

    /**
     * Recalculate invoice total if it's not paid
     * Business rule: If invoice is NOT paid, recalculate total as:
     * Total delivered − Total returned
     */
    public function recalculateInvoiceTotalIfNotPaid(): void
    {
        if (!$this->facture || $this->facture->isPaid()) {
            return; // Don't recalculate if paid (avoir is created instead)
        }

        // Calculate total delivered
        $totalDelivered = $this->facture->bonsLivraison()
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));

        // Calculate total returned
        $totalReturned = BonRetour::where('facture_id', $this->facture_id)
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));

        // Calculate new amounts based on delivered - returned
        $netQuantity = $totalDelivered - $totalReturned;
        
        // Recalculate invoice lines totals
        $newTotalHt = 0;
        foreach ($this->facture->lignes as $ligne) {
            // Calculate proportion of this line in the new total
            $originalTotal = $this->facture->total_ht;
            $lineProportion = $originalTotal > 0 ? $ligne->total_ht / $originalTotal : 0;
            $newLineTotal = $netQuantity > 0 ? ($originalTotal * $lineProportion * ($netQuantity / $totalDelivered)) : 0;
            $newTotalHt += $newLineTotal;
        }

        // Update invoice totals
        $tva = $newTotalHt * ($this->facture->tva / 100);
        $this->facture->update([
            'total_ht' => round($newTotalHt, 2),
            'total_ttc' => round($newTotalHt + $tva, 2),
        ]);
    }
}
