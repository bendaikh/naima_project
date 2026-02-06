<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_raison_sociale',
        'telephone',
        'email',
        'adresse',
    ];

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function bonsLivraison(): HasMany
    {
        return $this->hasMany(BonLivraison::class, 'client_id');
    }

    public function bonsRetour(): HasMany
    {
        return $this->hasMany(BonRetour::class);
    }
}
