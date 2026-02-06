<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonRetourLigne extends Model
{
    protected $table = 'bon_retour_lignes';

    protected $fillable = ['bon_retour_id', 'designation', 'quantite'];

    protected function casts(): array
    {
        return ['quantite' => 'decimal:2'];
    }

    public function bonRetour(): BelongsTo
    {
        return $this->belongsTo(BonRetour::class, 'bon_retour_id');
    }
}
