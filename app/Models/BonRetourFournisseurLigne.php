<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonRetourFournisseurLigne extends Model
{
    protected $table = 'bons_retour_fournisseur_lignes';

    protected $fillable = [
        'bon_retour_fournisseur_id',
        'bon_de_commande_ligne_id',
        'article_id',
        'quantity_received',
        'quantity_already_returned',
        'return_quantity',
    ];

    protected $casts = [
        'quantity_received' => 'decimal:2',
        'quantity_already_returned' => 'decimal:2',
        'return_quantity' => 'decimal:2',
    ];

    /**
     * Get the associated purchase return
     */
    public function bonRetourFournisseur(): BelongsTo
    {
        return $this->belongsTo(BonRetourFournisseur::class);
    }

    /**
     * Get the associated purchase order line
     */
    public function bonDeCommandeLigne(): BelongsTo
    {
        return $this->belongsTo(BonDeCommandeLigne::class);
    }

    /**
     * Get the article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Calculate the return amount
     */
    public function getReturnAmount(): float
    {
        return $this->return_quantity * $this->bonDeCommandeLigne->purchase_price;
    }
}
