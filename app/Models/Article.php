<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'ugs',
        'impot',
        'categorie',
        'description',
        'prix_vente',
        'prix_achat',
        'compte_revenu',
        'compte_depense',
        'unite',
        'quantite',
        'image_path',
        'entrepot',
    ];

    protected $casts = [
        'prix_vente' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'quantite' => 'decimal:2',
        'impot' => 'decimal:2',
    ];

    /**
     * Get the client associated with this article
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get profit margin
     */
    public function getProfitMarginAttribute(): float
    {
        if ($this->prix_achat == 0) {
            return 0;
        }
        return (($this->prix_vente - $this->prix_achat) / $this->prix_achat) * 100;
    }

    /**
     * Get total value
     */
    public function getTotalValueAttribute(): float
    {
        return $this->prix_vente * $this->quantite;
    }
}
