<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'libelle',
        'type_compte',
        'devise',
        'etat',
        'pays',
        'departement',
        'domiciliation',
        'web',
        'commentaire',
        'solde_initial',
        'solde_actuel',
        'date',
        'solde_minimum_autorise',
        'solde_minimum_desire',
        'nom_banque',
        'code_iban',
        'code_bic_swift',
        'numero_compte',
        'nom_proprietaire',
        'adresse_proprietaire',
        'code_postal_proprietaire',
        'ville_proprietaire',
        'pays_proprietaire',
        'compte_comptable',
        'code_journal_comptable',
    ];

    protected $casts = [
        'solde_initial' => 'decimal:2',
        'solde_actuel' => 'decimal:2',
        'solde_minimum_autorise' => 'decimal:2',
        'solde_minimum_desire' => 'decimal:2',
        'date' => 'date',
    ];

    public function ecritures()
    {
        return $this->hasMany(Ecriture::class, 'compte_bancaire_id');
    }
}
