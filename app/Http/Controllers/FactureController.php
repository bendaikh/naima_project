<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
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
        $devisId = $request->get('devis_id');
        $devis = $devisId ? Devis::with('client', 'lignes')->find($devisId) : null;
        return view('factures.create', ['facture' => new Facture, 'clients' => $clients, 'devis' => $devis]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'date' => 'required|date',
            'date_echeance' => 'nullable|date',
            'tva' => 'nullable|numeric|min:0|max:100',
        ]);
        $params = ParametresEntreprise::get();
        $validated['numero'] = $params->prefixe_facture . str_pad((string) $params->prochain_numero_facture, 4, '0', STR_PAD_LEFT);
        $validated['tva'] = $validated['tva'] ?? $params->tva_par_defaut;
        $validated['total_ht'] = 0;
        $validated['total_ttc'] = 0;
        $validated['montant_paye'] = 0;
        $validated['statut'] = 'non_payee';
        $facture = Facture::create($validated);
        $params->increment('prochain_numero_facture');
        return redirect()->route('factures.show', $facture)->with('success', 'Facture créée.');
    }

    public function show(Facture $facture): View
    {
        $facture->load('client', 'devis', 'lignes');
        return view('factures.show', compact('facture'));
    }

    public function edit(Facture $facture): View
    {
        $facture->load('lignes');
        $clients = Client::orderBy('nom_raison_sociale')->get();
        return view('factures.edit', compact('facture', 'clients'));
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'date_echeance' => 'nullable|date',
            'statut' => 'required|in:payee,non_payee,partiellement_payee',
            'montant_paye' => 'nullable|numeric|min:0',
        ]);
        if (array_key_exists('montant_paye', $validated)) {
            $facture->montant_paye = $validated['montant_paye'];
            $facture->statut = $validated['montant_paye'] >= $facture->total_ttc ? 'payee' : ($validated['montant_paye'] > 0 ? 'partiellement_payee' : 'non_payee');
        } else {
            $facture->statut = $validated['statut'];
        }
        $facture->fill(collect($validated)->except('montant_paye')->toArray())->save();
        return redirect()->route('factures.show', $facture)->with('success', 'Facture mise à jour.');
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();
        return redirect()->route('factures.index')->with('success', 'Facture supprimée.');
    }
}
