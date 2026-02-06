<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonLivraison extends Model
{
    protected $table = 'bons_livraison';

    protected $fillable = [
        'numero',
        'client_id',
        'facture_id',
        'devis_id',
        'date',
        'statut',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(BonLivraisonLigne::class, 'bon_livraison_id');
    }
}
