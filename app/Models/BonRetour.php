<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
