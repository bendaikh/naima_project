<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BonLivraison;
use App\Models\BonLivraisonLigne;
use App\Models\Client;
use App\Models\Devis;
use App\Models\ParametresEntreprise;
use App\Services\BonLivraisonService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BonLivraisonController extends Controller
{
    public function index(Request $request): View
    {
        $query = BonLivraison::with('client')->orderByDesc('date');
        if ($request->filled('statut')) {
            $statut = $request->statut;
            // Normalize the statut value (handle both 'annule' and 'annulé', and 'valide' and 'validé')
            if (in_array($statut, ['annule', 'annulé'])) {
                $query->where(function($q) {
                    $q->where('statut', 'annule')
                      ->orWhere('statut', 'annulé');
                });
            } elseif (in_array($statut, ['valide', 'validé'])) {
                $query->where(function($q) {
                    $q->where('statut', 'valide')
                      ->orWhere('statut', 'validé');
                });
            } else {
                $query->where('statut', $statut);
            }
        }
        $bonsLivraison = $query->paginate(15)->withQueryString();
        return view('bon-livraison.index', compact('bonsLivraison'));
    }


    public function create(Request $request): View
    {
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $clientId = $request->get('client_id');
        $devisList = collect();
        if ($clientId) {
            $devisList = Devis::with('lignes')
                ->where('client_id', $clientId)
                ->where('statut', 'accepte')
                ->orderByDesc('date')->get();
        }

        $designations = $devisList
            ->flatMap(fn($devis) => $devis->lignes->pluck('designation'))
            ->filter()
            ->unique()
            ->values();

        $articleImagesByName = $designations->isEmpty()
            ? []
            : Article::whereIn('nom', $designations)->pluck('image', 'nom')->toArray();

        return view('bon-livraison.create', [
            'bonLivraison' => new BonLivraison,
            'clients' => $clients,
            'devisList' => $devisList,
            'selectedClientId' => $clientId,
            'articleImagesByName' => $articleImagesByName,
        ]);
    }

    public function store(Request $request, BonLivraisonService $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'required|exists:devis,id',
            'date' => 'required|date',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        $devis = Devis::findOrFail($validated['devis_id']);
        $bonLivraison = $service->createFromDevis($devis, [
            'date' => $validated['date'],
            'lignes' => $validated['lignes'],
        ]);

        return redirect()->route('bon-livraison.show', $bonLivraison)
            ->with('success', 'Bon de livraison créé à partir du devis.');
    }

    public function show(BonLivraison $bonLivraison): View
    {
        $bonLivraison->load('client', 'lignes');
        return view('bon-livraison.show', compact('bonLivraison'));
    }

    public function edit(BonLivraison $bonLivraison): View
    {
        $bonLivraison->load(['lignes.article', 'devis.lignes']);
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $designations = $bonLivraison->lignes->pluck('designation');
        if ($bonLivraison->devis) {
            $designations = $designations->merge($bonLivraison->devis->lignes->pluck('designation'));
        }
        $designations = $designations->filter()->unique()->values();

        $articleData = Article::whereIn('nom', $designations)
            ->get()
            ->mapWithKeys(fn($article) => [
                $article->nom => [
                    'id' => $article->id,
                    'image' => $article->image,
                    'stock' => $article->quantite_stock,
                ],
            ])
            ->toArray();

        return view('bon-livraison.edit', compact('bonLivraison', 'clients', 'articleData'));
    }

    public function update(Request $request, BonLivraison $bonLivraison, BonLivraisonService $service)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:bons_livraison,numero,' . $bonLivraison->id,
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'statut' => 'required|in:en_attente,livre,validé,annule',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        $bonLivraison->update([
            'numero' => $validated['numero'],
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'statut' => $validated['statut'],
        ]);

        // Delete existing lines and create new ones
        $bonLivraison->lignes()->delete();
        foreach ($validated['lignes'] as $ligne) {
            $article = Article::where('nom', $ligne['designation'])->first();
            BonLivraisonLigne::create([
                'bon_livraison_id' => $bonLivraison->id,
                'article_id' => $article?->id,
                'designation' => $ligne['designation'],
                'quantite' => $ligne['quantite'],
            ]);
        }

        // If statut is set to 'livre' and not already validated, validate and decrement stock
        if ($validated['statut'] === 'livre' && $bonLivraison->statut !== 'validé') {
            try {
                $service->validate($bonLivraison);
            } catch (\Exception $e) {
                return redirect()->route('bon-livraison.show', $bonLivraison)
                    ->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
            }
        }

        return redirect()->route('bon-livraison.show', $bonLivraison)->with('success', 'Bon de livraison mis à jour.');
    }

    public function destroy(BonLivraison $bonLivraison)
    {
        $bonLivraison->delete();
        return redirect()->route('bon-livraison.index')->with('success', 'Bon de livraison supprimé.');
    }
    /**
     * Validate a Bon de Livraison (set statut to 'validé' and decrement stock)
     */
    public function validateBon(BonLivraison $bonLivraison, BonLivraisonService $service)
    {
        try {
            $service->validate($bonLivraison);
            return redirect()->route('bon-livraison.show', $bonLivraison)
                ->with('success', 'Bon de livraison validé et stock décrémenté.');
        } catch (\Exception $e) {
            return redirect()->route('bon-livraison.show', $bonLivraison)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Print a Bon de Livraison
     */
    public function print(BonLivraison $bonLivraison): View
    {
        $bonLivraison->load('client', 'lignes', 'devis');
        $parametres = ParametresEntreprise::first();
        return view('prints.bon-livraison', compact('bonLivraison', 'parametres'));
    }
}
