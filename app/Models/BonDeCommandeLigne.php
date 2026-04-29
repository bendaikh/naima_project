<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonDeCommandeLigne extends Model
{
    protected $table = 'bons_de_commande_lignes';

    protected $fillable = [
        'bon_de_commande_id',
        'article_id',
        'designation',
        'categorie_id',
        'image',
        'quantity',
        'purchase_price',
        'received_quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'received_quantity' => 'decimal:2',
    ];

    /**
     * Get the purchase order associated with this line
     */
    public function bonDeCommande(): BelongsTo
    {
        return $this->belongsTo(BonDeCommande::class);
    }

    /**
     * Get the article if it exists
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Calculate the line total
     */
    public function getLineTotal(): float
    {
        return $this->quantity * $this->purchase_price;
    }

    /**
     * Get the quantity that can still be returned
     */
    public function getReturnableQuantity(): float
    {
        return $this->received_quantity - ($this->getAlreadyReturnedQuantity() ?? 0);
    }

    /**
     * Get the quantity already returned through Bon de Retour
     */
    public function getAlreadyReturnedQuantity(): float
    {
        return BonRetourFournisseurLigne::where('bon_de_commande_ligne_id', $this->id)
            ->sum('return_quantity') ?? 0;
    }
}
