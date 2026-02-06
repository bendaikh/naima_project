<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'client_id',
        'devis_id',
        'date',
        'date_echeance',
        'tva',
        'total_ht',
        'total_ttc',
        'montant_paye',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'date_echeance' => 'date',
            'tva' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_ttc' => 'decimal:2',
            'montant_paye' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function bonsLivraison(): HasMany
    {
        return $this->hasMany(BonLivraison::class, 'facture_id');
    }

    public function avoirs(): HasMany
    {
        return $this->hasMany(Avoir::class, 'facture_id');
    }

    /**
     * Calculate invoice quantity accounting for returns
     * Formula: Sum of delivered quantities - Sum of returned quantities
     */
    public function calculateInvoiceQuantity(): decimal|float
    {
        $deliveredQty = $this->bonsLivraison()
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));

        $returnedQty = BonRetour::where('facture_id', $this->id)
            ->with('lignes')
            ->get()
            ->sum(fn($bon) => $bon->lignes->sum('quantite'));

        return $deliveredQty - $returnedQty;
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->statut === 'payee';
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(float $amount): void
    {
        $this->update([
            'montant_paye' => $amount,
            'statut' => 'payee',
        ]);
    }
}
