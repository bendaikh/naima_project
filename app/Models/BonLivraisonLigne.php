<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonLivraisonLigne extends Model
{
    protected $table = 'bon_livraison_lignes';

    protected $fillable = ['bon_livraison_id', 'designation', 'quantite'];

    protected function casts(): array
    {
        return ['quantite' => 'decimal:2'];
    }

    public function bonLivraison(): BelongsTo
    {
        return $this->belongsTo(BonLivraison::class, 'bon_livraison_id');
    }
}
