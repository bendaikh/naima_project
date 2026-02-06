<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametresEntreprise extends Model
{
    protected $table = 'parametres_entreprise';

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'logo',
        'tva_par_defaut',
        'prefixe_devis',
        'prefixe_facture',
        'prefixe_bon_livraison',
        'prefixe_bon_retour',
        'prochain_numero_devis',
        'prochain_numero_facture',
        'prochain_numero_bon_livraison',
        'prochain_numero_bon_retour',
    ];

    protected function casts(): array
    {
        return [
            'tva_par_defaut' => 'decimal:2',
        ];
    }

    public static function get(): self
    {
        $p = self::first();
        if (!$p) {
            $p = self::create([
                'prefixe_devis' => 'DEV-',
                'prefixe_facture' => 'FAC-',
                'prefixe_bon_livraison' => 'BL-',
                'prefixe_bon_retour' => 'BR-',
                'tva_par_defaut' => 20,
                'prochain_numero_devis' => 1,
                'prochain_numero_facture' => 1,
                'prochain_numero_bon_livraison' => 1,
                'prochain_numero_bon_retour' => 1,
            ]);
        }
        return $p;
    }
}
