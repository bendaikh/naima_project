<?php

namespace App\Console\Commands;

use App\Models\Devis;
use App\Models\Facture;
use App\Models\BonLivraison;
use App\Models\BonRetour;
use App\Models\BonDeCommande;
use Illuminate\Console\Command;

class UpdateDocumentNumbers extends Command
{
    protected $signature = 'documents:update-numbers {--dry-run : Preview changes without saving}';

    protected $description = 'Update existing document numbers (Devis, Facture, BL, BR, BC) to the new YYYY[TYPE]NNNN format';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be saved');
        }

        $this->info('Updating document numbers...');
        $this->newLine();

        $this->updateDevis($dryRun);
        $this->updateFactures($dryRun);
        $this->updateBonsLivraison($dryRun);
        $this->updateBonsRetour($dryRun);
        $this->updateBonsDeCommande($dryRun);

        $this->newLine();
        $this->info('Done!');

        return self::SUCCESS;
    }

    private function updateDevis(bool $dryRun): void
    {
        $this->info('Processing Devis...');
        $counters = [];
        $devis = Devis::orderBy('date')->orderBy('id')->get();
        
        foreach ($devis as $item) {
            $year = $item->date ? $item->date->format('Y') : date('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $newNumero = $year . 'D' . str_pad((string) $counters[$year], 4, '0', STR_PAD_LEFT);
            
            $this->line("  {$item->numero} -> {$newNumero}");
            
            if (!$dryRun) {
                $item->numero = $newNumero;
                $item->save();
            }
        }
        
        $this->info("  Updated " . count($devis) . " devis");
    }

    private function updateFactures(bool $dryRun): void
    {
        $this->info('Processing Factures...');
        $counters = [];
        $factures = Facture::orderBy('date')->orderBy('id')->get();
        
        foreach ($factures as $item) {
            $year = $item->date ? $item->date->format('Y') : date('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $newNumero = $year . 'F' . str_pad((string) $counters[$year], 4, '0', STR_PAD_LEFT);
            
            $this->line("  {$item->numero} -> {$newNumero}");
            
            if (!$dryRun) {
                $item->numero = $newNumero;
                $item->save();
            }
        }
        
        $this->info("  Updated " . count($factures) . " factures");
    }

    private function updateBonsLivraison(bool $dryRun): void
    {
        $this->info('Processing Bons de Livraison...');
        $counters = [];
        $items = BonLivraison::orderBy('date')->orderBy('id')->get();
        
        foreach ($items as $item) {
            $year = $item->date ? $item->date->format('Y') : date('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $newNumero = $year . 'BL' . str_pad((string) $counters[$year], 4, '0', STR_PAD_LEFT);
            
            $this->line("  {$item->numero} -> {$newNumero}");
            
            if (!$dryRun) {
                $item->numero = $newNumero;
                $item->save();
            }
        }
        
        $this->info("  Updated " . count($items) . " bons de livraison");
    }

    private function updateBonsRetour(bool $dryRun): void
    {
        $this->info('Processing Bons de Retour...');
        $counters = [];
        $items = BonRetour::orderBy('date')->orderBy('id')->get();
        
        foreach ($items as $item) {
            $year = $item->date ? $item->date->format('Y') : date('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $newNumero = $year . 'BR' . str_pad((string) $counters[$year], 4, '0', STR_PAD_LEFT);
            
            $this->line("  {$item->numero} -> {$newNumero}");
            
            if (!$dryRun) {
                $item->numero = $newNumero;
                $item->save();
            }
        }
        
        $this->info("  Updated " . count($items) . " bons de retour");
    }

    private function updateBonsDeCommande(bool $dryRun): void
    {
        $this->info('Processing Bons de Commande...');
        $counters = [];
        $items = BonDeCommande::orderBy('order_date')->orderBy('id')->get();
        
        foreach ($items as $item) {
            $year = $item->order_date ? $item->order_date->format('Y') : date('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $newReference = $year . 'BC' . str_pad((string) $counters[$year], 4, '0', STR_PAD_LEFT);
            
            $this->line("  " . ($item->reference ?? '#' . $item->id) . " -> {$newReference}");
            
            if (!$dryRun) {
                $item->reference = $newReference;
                $item->save();
            }
        }
        
        $this->info("  Updated " . count($items) . " bons de commande");
    }
}
