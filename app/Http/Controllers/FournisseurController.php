<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FournisseurController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fournisseur::query()->orderBy('nom');
        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($qry) use ($q) {
                $qry->where('nom', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telephone', 'like', "%{$q}%");
            });
        }
        $fournisseurs = $query->paginate(15)->withQueryString();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create(): View
    {
        return view('fournisseurs.create', ['fournisseur' => new Fournisseur]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
        ]);
        Fournisseur::create($validated);
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur créé.');
    }

    public function edit(Fournisseur $fournisseur): View
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
        ]);
        $fournisseur->update($validated);
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
