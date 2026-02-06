<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvoirLigne extends Model
{
    protected $table = 'avoir_lignes';

    protected $fillable = [
        'avoir_id',
        'designation',
        'quantite',
        'prix_unitaire',
        'montant_ht',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
            'prix_unitaire' => 'decimal:2',
            'montant_ht' => 'decimal:2',
        ];
    }

    public function avoir(): BelongsTo
    {
        return $this->belongsTo(Avoir::class);
    }
}
