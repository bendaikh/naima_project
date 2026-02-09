<?php

namespace App\Http\Controllers;

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
            $query->where('statut', $request->statut);
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
            $devisList = Devis::where('client_id', $clientId)
                ->where('statut', 'accepte')
                ->orderByDesc('date')->get();
        }
        return view('bon-livraison.create', [
            'bonLivraison' => new BonLivraison,
            'clients' => $clients,
            'devisList' => $devisList,
            'selectedClientId' => $clientId,
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
        $bonLivraison->load('lignes');
        $clients = Client::orderBy('nom_raison_sociale')->get();
        return view('bon-livraison.edit', compact('bonLivraison', 'clients'));
    }

    public function update(Request $request, BonLivraison $bonLivraison, BonLivraisonService $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'statut' => 'required|in:en_attente,livre,annule',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        $bonLivraison->update([
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'statut' => $validated['statut'],
        ]);

        // Delete existing lines and create new ones
        $bonLivraison->lignes()->delete();
        foreach ($validated['lignes'] as $ligne) {
            BonLivraisonLigne::create([
                'bon_livraison_id' => $bonLivraison->id,
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
}

