<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avoir extends Model
{
    protected $table = 'avoirs';

    protected $fillable = [
        'numero',
        'client_id',
        'facture_id',
        'bon_retour_id',
        'date',
        'montant_ht',
        'tva',
        'montant_ttc',
        'statut',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'montant_ht' => 'decimal:2',
            'tva' => 'decimal:2',
            'montant_ttc' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function bonRetour(): BelongsTo
    {
        return $this->belongsTo(BonRetour::class, 'bon_retour_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(AvoirLigne::class, 'avoir_id');
    }

    /**
     * Check if avoir can be created (invoice must be paid)
     */
    public function canBeCreated(): bool
    {
        return $this->facture && $this->facture->statut === 'payee';
    }

    /**
     * Mark avoir as emitted
     */
    public function emit(): void
    {
        $this->update(['statut' => 'emis']);
    }

    /**
     * Mark avoir as applied
     */
    public function apply(): void
    {
        $this->update(['statut' => 'applique']);
    }
}
