<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvoirFournisseur extends Model
{
    protected $table = 'avoirs_fournisseur';

    protected $fillable = [
        'fournisseur_id',
        'bon_retour_fournisseur_id',
        'avoir_date',
        'amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'avoir_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the supplier associated with the credit note
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Get the associated purchase return
     */
    public function bonRetourFournisseur(): BelongsTo
    {
        return $this->belongsTo(BonRetourFournisseur::class);
    }

    /**
     * Check if the avoir is received
     */
    public function isReceived(): bool
    {
        return $this->status === 'RECEIVED';
    }

    /**
     * Check if the avoir is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }
}
