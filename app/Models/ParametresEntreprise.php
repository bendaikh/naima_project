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
        'fax',
        'email',
        'website',
        'logo',
        'footer_legal_text',
        'tva_par_defaut',
        'prefixe_devis',
        'prefixe_facture',
        'prefixe_bon_livraison',
        'prefixe_bon_retour',
        'prefixe_bon_commande',
        'prochain_numero_devis',
        'prochain_numero_facture',
        'prochain_numero_bon_livraison',
        'prochain_numero_bon_retour',
        'prochain_numero_bon_commande',
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
                'prefixe_bon_commande' => 'BC-',
                'tva_par_defaut' => 20,
                'prochain_numero_devis' => 1,
                'prochain_numero_facture' => 1,
                'prochain_numero_bon_livraison' => 1,
                'prochain_numero_bon_retour' => 1,
                'prochain_numero_bon_commande' => 1,
            ]);
        }
        return $p;
    }

    /**
     * Generate a document number with format: YYYY[TYPE]NNNN
     * Example: 2026D0015 (Year + Type letter + Sequential number)
     */
    public function generateDocumentNumber(string $type, \DateTime|string $date = null): string
    {
        // Get the year from the provided date or use current year
        $year = $date instanceof \DateTime ? $date->format('Y') : 
                (is_string($date) ? date('Y', strtotime($date)) : date('Y'));
        
        // Determine the type letter and sequential number field
        $typeMap = [
            'devis' => ['letter' => 'D', 'field' => 'prochain_numero_devis'],
            'facture' => ['letter' => 'F', 'field' => 'prochain_numero_facture'],
            'bon_livraison' => ['letter' => 'BL', 'field' => 'prochain_numero_bon_livraison'],
            'bon_retour' => ['letter' => 'BR', 'field' => 'prochain_numero_bon_retour'],
            'bon_commande' => ['letter' => 'BC', 'field' => 'prochain_numero_bon_commande'],
        ];
        
        if (!isset($typeMap[$type])) {
            throw new \InvalidArgumentException("Invalid document type: {$type}");
        }
        
        $config = $typeMap[$type];
        $sequentialNumber = $this->{$config['field']};
        
        // Format: YYYY + Letter(s) + 4-digit number
        return $year . $config['letter'] . str_pad((string) $sequentialNumber, 4, '0', STR_PAD_LEFT);
    }
}
