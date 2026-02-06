<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'numero',
        'client_id',
        'date',
        'tva',
        'total_ht',
        'total_ttc',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'tva' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_ttc' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(DevisLigne::class, 'devis_id');
    }
}
