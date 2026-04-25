<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonLivraisonLigne extends Model
{
    protected $table = 'bon_livraison_lignes';

    protected $fillable = [
        'bon_livraison_id',
        'article_id',
        'designation',
        'reference',
        'quantite',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
        ];
    }

    public function bonLivraison(): BelongsTo
    {
        return $this->belongsTo(BonLivraison::class, 'bon_livraison_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
