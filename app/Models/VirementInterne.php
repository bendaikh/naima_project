<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirementInterne extends Model
{
    use HasFactory;

    protected $table = 'virements_internes';

    protected $fillable = [
        'de_compte_id',
        'vers_compte_id',
        'type',
        'date',
        'description',
        'montant',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date' => 'date',
    ];

    public function deCompte()
    {
        return $this->belongsTo(Banque::class, 'de_compte_id');
    }

    public function versCompte()
    {
        return $this->belongsTo(Banque::class, 'vers_compte_id');
    }
}
