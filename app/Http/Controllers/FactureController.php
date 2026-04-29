<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\ParametresEntreprise;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function index(Request $request): View
    {
        $query = Facture::with('client')->orderByDesc('date');
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        $factures = $query->paginate(15)->withQueryString();
        return view('factures.index', compact('factures'));
    }

    public function create(Request $request): View
    {
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $articles = Article::with('categorie')->orderBy('nom')->get();
        $devisId = $request->get('devis_id');
        $devis = $devisId ? Devis::with('client', 'lignes')->find($devisId) : null;
        return view('factures.create', ['facture' => new Facture, 'clients' => $clients, 'articles' => $articles, 'devis' => $devis]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'date' => 'required|date',
            'date_echeance' => 'nullable|date',
            'tva' => 'nullable|numeric|min:0|max:100',
            'articles_data' => 'nullable|json',
        ]);

        $params = ParametresEntreprise::get();
        $validated['numero'] = $params->generateDocumentNumber('facture', $validated['date']);
        $validated['tva'] = $validated['tva'] ?? $params->tva_par_defaut;
        
        // Initialize totals
        $validated['total_ht'] = 0;
        $validated['total_ttc'] = 0;
        $validated['montant_paye'] = 0;
        
        // Parse articles data if provided
        $articlesData = [];
        if ($validated['articles_data']) {
            $articlesData = json_decode($validated['articles_data'], true) ?? [];
            
            // Calculate totals from articles
            foreach ($articlesData as $article) {
                $validated['total_ht'] += floatval($article['total_ht']);
            }
            
            // Calculate TTC
            $tva = $validated['total_ht'] * ($validated['tva'] / 100);
            $validated['total_ttc'] = $validated['total_ht'] + $tva;
        }
        
        $validated['statut'] = 'non_payee';
        
        // Remove articles_data from fillable update
        unset($validated['articles_data']);
        
        // Create facture
        $facture = Facture::create($validated);
        
        // Create facture lines from articles
        foreach ($articlesData as $article) {
            FactureLigne::create([
                'facture_id' => $facture->id,
                'designation' => $article['designation'],
                'categorie' => $article['categorie'] ?? null,
                'quantite' => floatval($article['quantite']),
                'prix_unitaire' => floatval($article['prix_unitaire']),
                'tva' => $validated['tva'],
                'total_ht' => floatval($article['total_ht']),
            ]);
        }
        
        $params->increment('prochain_numero_facture');
        return redirect()->route('factures.show', $facture)->with('success', 'Facture créée.');
    }

    public function show(Facture $facture): View
    {
        $facture->load('client', 'devis', 'lignes');
        return view('factures.show', compact('facture'));
    }

    public function print(Facture $facture): View
    {
        $facture->load('client', 'devis', 'lignes', 'bonsLivraison');
        return view('prints.facture', compact('facture'));
    }

    public function edit(Facture $facture): View
    {
        $facture->load('lignes');
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $articles = Article::orderBy('nom')->get();
        return view('factures.edit', compact('facture', 'clients', 'articles'));
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:factures,numero,' . $facture->id,
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'date_echeance' => 'nullable|date',
            'tva' => 'nullable|numeric|min:0|max:100',
            'statut' => 'required|in:payee,non_payee,partiellement_payee',
            'montant_paye' => 'nullable|numeric|min:0',
            'articles_data' => 'nullable|json',
        ]);

        $articlesData = [];
        $hasArticlesData = $request->has('articles_data');
        if ($validated['articles_data'] ?? null) {
            $articlesData = json_decode($validated['articles_data'], true) ?? [];
        }

        $updateData = $validated;
        unset($updateData['articles_data']);

        if (!array_key_exists('tva', $updateData) || $updateData['tva'] === null) {
            $updateData['tva'] = $facture->tva;
        }

        if ($hasArticlesData) {
            $totalHt = 0;
            foreach ($articlesData as $article) {
                $totalHt += floatval($article['total_ht'] ?? 0);
            }
            $updateData['total_ht'] = $totalHt;
            $tvaRate = ($updateData['tva'] ?? 0) / 100;
            $totalTva = $totalHt * $tvaRate;
            $updateData['total_ttc'] = $totalHt + $totalTva;
        }

        // Handle payment status based on montant_paye
        if (array_key_exists('montant_paye', $updateData)) {
            $montantPaye = floatval($updateData['montant_paye']);
            $totalTtc = $updateData['total_ttc'] ?? $facture->total_ttc;
            $updateData['statut'] = $montantPaye >= $totalTtc ? 'payee' : ($montantPaye > 0 ? 'partiellement_payee' : 'non_payee');
        }

        $facture->update($updateData);

        if ($hasArticlesData) {
            $facture->lignes()->delete();
            foreach ($articlesData as $article) {
                FactureLigne::create([
                    'facture_id' => $facture->id,
                    'designation' => $article['designation'] ?? '',
                    'categorie' => $article['categorie'] ?? null,
                    'quantite' => floatval($article['quantite'] ?? 0),
                    'prix_unitaire' => floatval($article['prix_unitaire'] ?? 0),
                    'tva' => $updateData['tva'] ?? 0,
                    'total_ht' => floatval($article['total_ht'] ?? 0),
                ]);
            }
        }

        return redirect()->route('factures.show', $facture)->with('success', 'Facture mise à jour.');
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();
        return redirect()->route('factures.index')->with('success', 'Facture supprimée.');
    }

    public function markAsPaid(Facture $facture)
    {
        $facture->update([
            'statut' => 'payee',
            'montant_paye' => $facture->total_ttc,
        ]);
        return redirect()->route('factures.show', $facture)
            ->with('success', 'Facture marquée comme payée.');
    }
}

