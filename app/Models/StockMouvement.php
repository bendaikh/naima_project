<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMouvement extends Model
{
    protected $table = 'stock_mouvements';

    protected $fillable = [
        'article_id',
        'type', // entree or sortie
        'quantite',
        'reference_type',
        'reference_id',
        'motif',
        'date',
        'user_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related document based on reference_type
     */
    public function getRelatedDocument()
    {
        return match($this->reference_type) {
            'BonLivraison' => BonLivraison::find($this->reference_id),
            'BonRetour' => BonRetour::find($this->reference_id),
            'Ajustement' => null, // Adjust as needed
            default => null,
        };
    }

    /**
     * Get the type label in French
     */
    public function getTypeLabel(): string
    {
        return $this->type === 'entree' ? 'Entrée de stock' : 'Sortie de stock';
    }

    /**
     * Get the motif label in French
     */
    public function getMotifLabel(): string
    {
        return match($this->motif) {
            'livraison' => 'Livraison client',
            'retour' => 'Retour client',
            'reception' => 'Réception fournisseur',
            'ajustement' => 'Ajustement manuel',
            'inventaire' => 'Correction inventaire',
            default => $this->motif ?? 'Autre',
        };
    }
}
