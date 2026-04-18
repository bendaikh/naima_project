<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BanqueController extends Controller
{
    public function index(Request $request): View
    {
        $query = Banque::query()->orderBy('libelle');
        
        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($qry) use ($q) {
                $qry->where('libelle', 'like', "%{$q}%")
                    ->orWhere('numero_compte', 'like', "%{$q}%")
                    ->orWhere('type_compte', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('type_compte')) {
            $query->where('type_compte', $request->type_compte);
        }
        
        $banques = $query->paginate(15)->withQueryString();
        $totalSolde = Banque::sum('solde_actuel');
        
        return view('banques.index', compact('banques', 'totalSolde'));
    }

    public function create(): View
    {
        return view('banques.create', ['banque' => new Banque]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ref' => 'nullable|string|max:255|unique:banques,ref',
            'libelle' => 'required|string|max:255',
            'type_compte' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'etat' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'domiciliation' => 'nullable|string|max:255',
            'web' => 'nullable|url|max:255',
            'commentaire' => 'nullable|string',
            'solde_initial' => 'required|numeric',
            'date' => 'nullable|date',
            'solde_minimum_autorise' => 'nullable|numeric',
            'solde_minimum_desire' => 'nullable|numeric',
            'nom_banque' => 'nullable|string|max:255',
            'code_iban' => 'nullable|string|max:255',
            'code_bic_swift' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'nom_proprietaire' => 'nullable|string|max:255',
            'adresse_proprietaire' => 'nullable|string',
            'code_postal_proprietaire' => 'nullable|string|max:255',
            'ville_proprietaire' => 'nullable|string|max:255',
            'pays_proprietaire' => 'nullable|string|max:255',
            'compte_comptable' => 'nullable|string|max:255',
            'code_journal_comptable' => 'nullable|string|max:255',
        ]);
        
        // Auto-generate ref if not provided
        if (empty($validated['ref'])) {
            $validated['ref'] = 'BQ-' . strtoupper(substr(uniqid(), -8));
        }
        
        $validated['solde_actuel'] = $validated['solde_initial'];
        
        Banque::create($validated);
        return redirect()->route('banques.index')->with('success', 'Compte créé avec succès.');
    }

    public function show(Banque $banque): View
    {
        return view('banques.show', compact('banque'));
    }

    public function edit(Banque $banque): View
    {
        return view('banques.edit', compact('banque'));
    }

    public function update(Request $request, Banque $banque)
    {
        $validated = $request->validate([
            'ref' => 'nullable|string|max:255|unique:banques,ref,' . $banque->id,
            'libelle' => 'required|string|max:255',
            'type_compte' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'etat' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'domiciliation' => 'nullable|string|max:255',
            'web' => 'nullable|url|max:255',
            'commentaire' => 'nullable|string',
            'solde_initial' => 'required|numeric',
            'date' => 'nullable|date',
            'solde_minimum_autorise' => 'nullable|numeric',
            'solde_minimum_desire' => 'nullable|numeric',
            'nom_banque' => 'nullable|string|max:255',
            'code_iban' => 'nullable|string|max:255',
            'code_bic_swift' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'nom_proprietaire' => 'nullable|string|max:255',
            'adresse_proprietaire' => 'nullable|string',
            'code_postal_proprietaire' => 'nullable|string|max:255',
            'ville_proprietaire' => 'nullable|string|max:255',
            'pays_proprietaire' => 'nullable|string|max:255',
            'compte_comptable' => 'nullable|string|max:255',
            'code_journal_comptable' => 'nullable|string|max:255',
        ]);
        
        $banque->update($validated);
        return redirect()->route('banques.index')->with('success', 'Compte mis à jour avec succès.');
    }

    public function destroy(Banque $banque)
    {
        $banque->delete();
        return redirect()->route('banques.index')->with('success', 'Compte supprimé avec succès.');
    }
}
