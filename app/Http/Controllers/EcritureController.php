<?php

namespace App\Http\Controllers;

use App\Models\Ecriture;
use App\Models\Banque;
use App\Models\CategorieEcriture;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EcritureController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ecriture::with(['compteBancaire', 'categorie'])->orderBy('date_valeur', 'desc');
        
        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($qry) use ($q) {
                $qry->where('description', 'like', "%{$q}%")
                    ->orWhere('ref', 'like', "%{$q}%")
                    ->orWhere('numero', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('compte_bancaire_id')) {
            $query->where('compte_bancaire_id', $request->compte_bancaire_id);
        }
        
        $ecritures = $query->paginate(15)->withQueryString();
        $banques = Banque::orderBy('libelle')->get();
        
        return view('ecritures.index', compact('ecritures', 'banques'));
    }

    public function create(): View
    {
        $banques = Banque::orderBy('libelle')->get();
        $categories = CategorieEcriture::orderBy('nom')->get();
        return view('ecritures.create', compact('banques', 'categories'));
    }

    public function categories(): View
    {
        $categories = CategorieEcriture::withCount('ecritures')
            ->with(['ecritures' => function($query) {
                $query->select('categorie_id', \DB::raw('SUM(credit) as total_credit'), \DB::raw('SUM(debit) as total_debit'))
                      ->groupBy('categorie_id');
            }])
            ->orderBy('nom')
            ->get();
            
        return view('ecritures.categories', compact('categories'));
    }

    public function createCategory(): View
    {
        return view('ecritures.categories-create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        CategorieEcriture::create($validated);

        return redirect()->route('ecritures.categories')->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Ecriture $ecriture): View
    {
        $banques = Banque::orderBy('libelle')->get();
        $categories = CategorieEcriture::orderBy('nom')->get();
        return view('ecritures.edit', compact('ecriture', 'banques', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'compte_bancaire_id' => 'required|exists:banques,id',
            'categorie_id' => 'nullable|exists:categories_ecriture,id',
            'type' => 'nullable|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'date_valeur' => 'required|date',
            'description' => 'required|string',
            'numero' => 'nullable|string|max:255',
            'tiers_utilisateur' => 'nullable|string|max:255',
            'releve' => 'nullable|string|max:255',
        ]);
        
        $validated['ref'] = 'ECR-' . time();
        
        $banque = Banque::find($validated['compte_bancaire_id']);
        $montant = ($validated['credit'] ?? 0) - ($validated['debit'] ?? 0);
        $validated['solde'] = $banque->solde_actuel + $montant;
        
        $ecriture = Ecriture::create($validated);
        
        $banque->solde_actuel = $validated['solde'];
        $banque->save();
        
        return redirect()->route('ecritures.index')->with('success', 'Écriture créée avec succès.');
    }

    public function show(Ecriture $ecriture): View
    {
        $ecriture->load(['compteBancaire', 'categorie']);
        return view('ecritures.show', compact('ecriture'));
    }

    public function update(Request $request, Ecriture $ecriture)
    {
        $validated = $request->validate([
            'compte_bancaire_id' => 'required|exists:banques,id',
            'categorie_id' => 'nullable|exists:categories_ecriture,id',
            'type' => 'nullable|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'date_valeur' => 'required|date',
            'description' => 'required|string',
            'numero' => 'nullable|string|max:255',
            'tiers_utilisateur' => 'nullable|string|max:255',
            'releve' => 'nullable|string|max:255',
        ]);
        
        $oldBanque = Banque::find($ecriture->compte_bancaire_id);
        $oldMontant = $ecriture->credit - $ecriture->debit;
        $oldBanque->solde_actuel -= $oldMontant;
        $oldBanque->save();
        
        $ecriture->update($validated);
        
        $newBanque = Banque::find($validated['compte_bancaire_id']);
        $newMontant = ($validated['credit'] ?? 0) - ($validated['debit'] ?? 0);
        $validated['solde'] = $newBanque->solde_actuel + $newMontant;
        $newBanque->solde_actuel = $validated['solde'];
        $newBanque->save();
        
        return redirect()->route('ecritures.index')->with('success', 'Écriture mise à jour avec succès.');
    }

    public function destroy(Ecriture $ecriture)
    {
        $banque = Banque::find($ecriture->compte_bancaire_id);
        $montant = $ecriture->credit - $ecriture->debit;
        $banque->solde_actuel -= $montant;
        $banque->save();
        
        $ecriture->delete();
        return redirect()->route('ecritures.index')->with('success', 'Écriture supprimée avec succès.');
    }
}
