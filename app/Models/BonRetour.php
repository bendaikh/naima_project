<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonRetour extends Model
{
    protected $table = 'bons_retour';

    protected $fillable = [
        'numero',
        'client_id',
        'bon_livraison_id',
        'facture_id',
        'date',
        'motif',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bonLivraison(): BelongsTo
    {
        return $this->belongsTo(BonLivraison::class, 'bon_livraison_id');
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(BonRetourLigne::class, 'bon_retour_id');
    }
}
