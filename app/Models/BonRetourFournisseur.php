<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonRetourFournisseur extends Model
{
    protected $table = 'bons_retour_fournisseur';

    protected $fillable = [
        'bon_de_commande_id',
        'fournisseur_id',
        'return_date',
        'return_type',
        'return_reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    /**
     * Get the associated purchase order
     */
    public function bonDeCommande(): BelongsTo
    {
        return $this->belongsTo(BonDeCommande::class);
    }

    /**
     * Get the supplier
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Get the return lines
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(BonRetourFournisseurLigne::class);
    }

    /**
     * Get the associated avoir if return_type is REFUND
     */
    public function avoir(): BelongsTo
    {
        return $this->belongsTo(AvoirFournisseur::class, 'id', 'bon_retour_fournisseur_id');
    }

    /**
     * Calculate total returned amount
     */
    public function getTotalReturnedAmount(): float
    {
        return $this->lignes()
            ->with('bonDeCommandeLigne')
            ->get()
            ->sum(function ($ligne) {
                return $ligne->return_quantity * $ligne->bonDeCommandeLigne->purchase_price;
            });
    }

    /**
     * Check if this is a replacement return
     */
    public function isReplacement(): bool
    {
        return $this->return_type === 'REPLACEMENT';
    }

    /**
     * Check if this is a refund return
     */
    public function isRefund(): bool
    {
        return $this->return_type === 'REFUND';
    }
}
