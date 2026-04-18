<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieEcriture extends Model
{
    use HasFactory;

    protected $table = 'categories_ecriture';

    protected $fillable = [
        'nom',
    ];

    public function ecritures()
    {
        return $this->hasMany(Ecriture::class, 'categorie_id');
    }

    public function getTotalAttribute()
    {
        return $this->ecritures()->sum('credit') - $this->ecritures()->sum('debit');
    }

    public function getMoyenneAttribute()
    {
        $count = $this->ecritures()->count();
        return $count > 0 ? $this->total / $count : 0;
    }
}
