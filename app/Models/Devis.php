<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'numero',
        'client_id',
        'date',
        'tva',
        'disponibilite',
        'total_ht',
        'total_ttc',
        'statut',
        'signature_image',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'tva' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_ttc' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(DevisLigne::class, 'devis_id');
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class, 'devis_id');
    }

    public function bonLivraisons(): HasMany
    {
        return $this->hasMany(BonLivraison::class, 'devis_id');
    }

    /**
     * Check if devis can be converted to facture
     */
    public function canConvertToFacture(): bool
    {
        return $this->statut === 'accepte' && $this->lignes()->count() > 0;
    }

    /**
     * Check if devis can be marked as accepted
     */
    public function canMarkAsAccepted(): bool
    {
        return in_array($this->statut, ['brouillon', 'envoye']);
    }

    /**
     * Check if devis can be marked as sent
     */
    public function canMarkAsSent(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Check if devis can be marked as refused
     */
    public function canMarkAsRefused(): bool
    {
        return in_array($this->statut, ['brouillon', 'envoye']);
    }

    /**
     * Check if devis can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Mark devis as sent
     */
    public function markAsSent(): bool
    {
        if ($this->canMarkAsSent()) {
            return $this->update(['statut' => 'envoye']);
        }
        return false;
    }

    /**
     * Mark devis as accepted
     */
    public function markAsAccepted(): bool
    {
        if ($this->canMarkAsAccepted()) {
            return $this->update(['statut' => 'accepte']);
        }
        return false;
    }

    /**
     * Mark devis as refused
     */
    public function markAsRefused(): bool
    {
        if ($this->canMarkAsRefused()) {
            return $this->update(['statut' => 'refuse']);
        }
        return false;
    }

    /**
     * Convert devis to facture
     */
    public function convertToFacture(): ?Facture
    {
        if (!$this->canConvertToFacture()) {
            return null;
        }

        $params = ParametresEntreprise::first();
        
        $facture = Facture::create([
            'numero' => $params->generateDocumentNumber('facture', now()->toDateString()),
            'client_id' => $this->client_id,
            'devis_id' => $this->id,
            'date' => now()->toDateString(),
            'date_echeance' => now()->addMonth()->toDateString(),
            'tva' => $this->tva,
            'total_ht' => $this->total_ht,
            'total_ttc' => $this->total_ttc,
            'montant_paye' => 0,
            'statut' => 'non_payee',
        ]);

        // Copy devis lines to facture lines
        foreach ($this->lignes as $ligne) {
            FactureLigne::create([
                'facture_id' => $facture->id,
                'designation' => $ligne->designation,
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'tva' => $ligne->tva,
                'total_ht' => $ligne->total_ht,
            ]);
        }

        $params->increment('prochain_numero_facture');
        
        return $facture;
    }
}
