<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DevisController extends Controller
{
    public function index(Request $request): View
    {
        $query = Devis::with('client')->orderByDesc('date');
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        $devis = $query->paginate(15)->withQueryString();
        return view('devis.index', compact('devis'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('nom_raison_sociale')->get();
        return view('devis.create', ['devis' => new Devis, 'clients' => $clients]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'tva' => 'nullable|numeric|min:0|max:100',
        ]);
        $params = \App\Models\ParametresEntreprise::get();
        $validated['numero'] = $params->prefixe_devis . str_pad((string) $params->prochain_numero_devis, 4, '0', STR_PAD_LEFT);
        $validated['tva'] = $validated['tva'] ?? $params->tva_par_defaut;
        $validated['total_ht'] = 0;
        $validated['total_ttc'] = 0;
        $validated['statut'] = 'brouillon';
        $devis = Devis::create($validated);
        $params->increment('prochain_numero_devis');
        return redirect()->route('devis.show', $devis)->with('success', 'Devis créé.');
    }

    public function show(Devis $devis): View
    {
        $devis->load('client', 'lignes');
        return view('devis.show', compact('devis'));
    }

    public function edit(Devis $devis): View
    {
        $devis->load('lignes');
        $clients = Client::orderBy('nom_raison_sociale')->get();
        return view('devis.edit', compact('devis', 'clients'));
    }

    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'tva' => 'nullable|numeric|min:0|max:100',
            'statut' => 'required|in:brouillon,envoye,accepte,refuse',
        ]);
        $devis->update($validated);
        return redirect()->route('devis.show', $devis)->with('success', 'Devis mis à jour.');
    }

    public function destroy(Devis $devis)
    {
        $devis->delete();
        return redirect()->route('devis.index')->with('success', 'Devis supprimé.');
    }
}
