<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecriture extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'description',
        'date_valeur',
        'type',
        'numero',
        'tiers_utilisateur',
        'compte_bancaire_id',
        'categorie_id',
        'debit',
        'credit',
        'solde',
        'releve',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'solde' => 'decimal:2',
        'date_valeur' => 'date',
    ];

    public function compteBancaire()
    {
        return $this->belongsTo(Banque::class, 'compte_bancaire_id');
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieEcriture::class, 'categorie_id');
    }
}
