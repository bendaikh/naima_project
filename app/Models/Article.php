<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = [
        'nom',
        'categorie_id',
        'description',
        'prix_vente',
        'prix_achat',
        'quantite',
        'quantite_stock',
        'unite',
        'numero_facture',
        'compte_revenu',
        'compte_depense',
        'image',
        'entrepot',
        'ugs',
        'impot',
    ];

    protected $casts = [
        'prix_vente' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'quantite' => 'decimal:2',
        'quantite_stock' => 'decimal:2',
        'impot' => 'decimal:2',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function bonLivraisonLignes(): HasMany
    {
        return $this->hasMany(BonLivraisonLigne::class);
    }

    public function bonRetourLignes(): HasMany
    {
        return $this->hasMany(BonRetourLigne::class);
    }

    public function devisLignes(): HasMany
    {
        return $this->hasMany(DevisLigne::class);
    }

    public function factureLignes(): HasMany
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function stockMouvements(): HasMany
    {
        return $this->hasMany(StockMouvement::class);
    }
}
