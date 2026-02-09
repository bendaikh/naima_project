<?php

namespace App\Models;

use App\Services\StockMouvementService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Article;

class BonLivraison extends Model
{
    protected $table = 'bons_livraison';

    protected $fillable = [
        'numero',
        'client_id',
        'facture_id',
        'devis_id',
        'date',
        'statut',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(BonLivraisonLigne::class, 'bon_livraison_id');
    }

    public function bonsRetour(): HasMany
    {
        return $this->hasMany(BonRetour::class, 'bon_livraison_id');
    }

    /**
     * Check if delivery note is validated/signed
     */
    public function isValidated(): bool
    {
        return $this->statut === 'validé' || $this->statut === 'signed';
    }

    /**
     * Calculate total delivered quantity
     */
    public function getTotalDeliveredQuantity(): float
    {
        return (float) $this->lignes()->sum('quantite');
    }

    /**
     * Calculate total returned quantity
     */
    public function getTotalReturnedQuantity(): float
    {
        return $this->bonsRetour()
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));
    }

    /**
     * Get remaining quantity available for return
     */
    public function getRemainingQuantity(): float
    {
        return $this->getTotalDeliveredQuantity() - $this->getTotalReturnedQuantity();
    }

    /**
     * Decrement stock for all articles in this delivery
     */
    public function decrementStock(): void
    {
        $service = app(StockMouvementService::class);
        
        foreach ($this->lignes as $ligne) {
            // Find article by designation (nom field)
            $article = Article::where('nom', $ligne->designation)->first();
            
            if ($article) {
                $article->decrement('quantite_stock', $ligne->quantite);
                
                // Record the stock movement
                $service->recordMovement(
                    $article,
                    'sortie',
                    $ligne->quantite,
                    'livraison',
                    'BonLivraison',
                    $this->id,
                    "Livraison BL-{$this->numero}"
                );
            }
        }
    }

    /**
     * Increment stock for all articles in this delivery
     */
    public function incrementStock(): void
    {
        $service = app(StockMouvementService::class);
        
        // Reload lignes to ensure they're fresh from database
        $this->refresh();
        $lignes = $this->lignes()->get();
        
        foreach ($lignes as $ligne) {
            // Find article by designation (nom field)
            $article = Article::where('nom', $ligne->designation)->first();
            
            if ($article) {
                $article->increment('quantite_stock', $ligne->quantite);
                
                // Record the stock movement (reverse)
                $service->recordMovement(
                    $article,
                    'entree',
                    $ligne->quantite,
                    'livraison',
                    'BonLivraison',
                    $this->id,
                    "Annulation livraison BL-{$this->numero}"
                );
            }
        }
    }
}
