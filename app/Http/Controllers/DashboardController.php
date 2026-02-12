<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // General metrics
        $nombreClients = Client::count();
        $nombreFournisseurs = Fournisseur::count();
        $totalDevis = Devis::count();
        $totalFactures = Facture::count();
        $chiffreAffaires = Facture::where('statut', 'payee')->sum('total_ttc');
        $facturesImpayees = Facture::whereIn('statut', ['non_payee', 'partiellement_payee'])->sum('total_ttc');
        $nouveauxClientsCeMois = Client::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        // Electronics-specific metrics
        $totalArticles = Article::count();
        $stockTotal = Article::sum('quantite_stock');
        $articlesEnStock = Article::where('quantite_stock', '>', 0)->count();
        $articlesEnRupture = Article::where('quantite_stock', '<=', 0)->count();
        $valeurStockTotal = Article::selectRaw('SUM(quantite_stock * prix_achat) as total')->first()->total ?? 0;
        
        // Best selling products (by quantity sold)
        $meilleursProduits = Article::with('bonLivraisonLignes')
            ->get()
            ->map(function ($article) {
                $quantiteSold = $article->bonLivraisonLignes->sum('quantite');
                return [
                    'nom' => $article->nom,
                    'quantite' => $quantiteSold,
                    'prix' => $article->prix_vente,
                ];
            })
            ->filter(fn ($p) => $p['quantite'] > 0)
            ->sortByDesc('quantite')
            ->take(5)
            ->values();

        $moisLabels = ['JAN', 'FÉV', 'MAR', 'AVR', 'MAI', 'JUIN', 'JUIL', 'AOÛT', 'SEPT', 'OCT', 'NOV', 'DÉC'];
        $debut = now()->subMonths(5)->startOfMonth();
        $chartData = [];
        for ($i = 0; $i < 6; $i++) {
            $mois = $debut->copy()->addMonths($i);
            $total = (float) Facture::where('statut', 'payee')
                ->whereYear('date', $mois->year)
                ->whereMonth('date', $mois->month)
                ->sum('total_ttc');
            $chartData[] = [
                'label' => $moisLabels[$mois->month - 1],
                'value' => $total,
            ];
        }

        $activites = [];
        foreach (Facture::with('client')->latest()->take(3)->get() as $f) {
            $activites[] = [
                'type' => $f->statut === 'payee' ? 'facture_payee' : ($f->statut === 'non_payee' ? 'facture_impayee' : 'facture_partiel'),
                'titre' => $f->statut === 'payee' ? "Facture #{$f->numero} payée" : ($f->statut === 'non_payee' ? 'Facture en retard' : 'Facture partiellement payée'),
                'client' => $f->client->nom_raison_sociale ?? '-',
                'montant' => $f->total_ttc,
                'date' => $f->updated_at,
            ];
        }
        foreach (Devis::with('client')->latest()->take(2)->get() as $d) {
            $activites[] = [
                'type' => 'devis',
                'titre' => 'Nouveau devis créé',
                'client' => $d->client->nom_raison_sociale ?? '-',
                'montant' => $d->total_ttc,
                'date' => $d->created_at,
            ];
        }
        foreach (Client::latest()->take(2)->get() as $c) {
            $activites[] = [
                'type' => 'client',
                'titre' => 'Nouveau client ajouté',
                'client' => $c->nom_raison_sociale,
                'montant' => null,
                'date' => $c->created_at,
            ];
        }
        usort($activites, fn ($a, $b) => $b['date']->getTimestamp() <=> $a['date']->getTimestamp());
        $activites = array_slice($activites, 0, 5);

        return view('dashboard', [
            'user' => $request->user(),
            'nombreClients' => $nombreClients,
            'nombreFournisseurs' => $nombreFournisseurs,
            'totalDevis' => $totalDevis,
            'totalFactures' => $totalFactures,
            'chiffreAffaires' => $chiffreAffaires,
            'facturesImpayees' => $facturesImpayees,
            'nouveauxClientsCeMois' => $nouveauxClientsCeMois,
            'chartData' => $chartData,
            'activites' => $activites,
            // Electronics metrics
            'totalArticles' => $totalArticles,
            'stockTotal' => $stockTotal,
            'articlesEnStock' => $articlesEnStock,
            'articlesEnRupture' => $articlesEnRupture,
            'valeurStockTotal' => $valeurStockTotal,
            'meilleursProduits' => $meilleursProduits,
        ]);
    }
}
