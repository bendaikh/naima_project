<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonDeCommande extends Model
{
    protected $table = 'bons_de_commande';

    protected $fillable = [
        'reference',
        'fournisseur_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    /**
     * Get the supplier associated with the purchase order
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Get the purchase order lines
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(BonDeCommandeLigne::class);
    }

    /**
     * Get the purchase returns associated with this order
     */
    public function retours(): HasMany
    {
        return $this->hasMany(BonRetourFournisseur::class);
    }

    /**
     * Calculate total amount of the purchase order
     */
    public function getTotalAmount(): float
    {
        return $this->lignes()->sum(\DB::raw('quantity * purchase_price'));
    }

    /**
     * Get all returned quantities for each article in this order
     */
    public function getReturnedQuantities()
    {
        return $this->retours()
            ->with('lignes')
            ->get()
            ->flatMap(function ($retour) {
                return $retour->lignes->map(function ($ligne) {
                    return [
                        'article_id' => $ligne->article_id,
                        'returned_quantity' => $ligne->return_quantity,
                    ];
                });
            })
            ->groupBy('article_id')
            ->map(function ($items) {
                return $items->sum('returned_quantity');
            });
    }
}
