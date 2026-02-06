<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'client_id',
        'devis_id',
        'date',
        'date_echeance',
        'tva',
        'total_ht',
        'total_ttc',
        'montant_paye',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'date_echeance' => 'date',
            'tva' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_ttc' => 'decimal:2',
            'montant_paye' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(FactureLigne::class);
    }
}
