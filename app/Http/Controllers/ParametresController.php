<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\ParametresEntreprise;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParametresController extends Controller
{
    public function index(): View
    {
        $params = ParametresEntreprise::get();
        $categories = Categorie::all();
        return view('parametres.index', compact('params', 'categories'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'tva_par_defaut' => 'nullable|numeric|min:0|max:100',
            'prefixe_devis' => 'nullable|string|max:20',
            'prefixe_facture' => 'nullable|string|max:20',
            'prefixe_bon_livraison' => 'nullable|string|max:20',
            'prefixe_bon_retour' => 'nullable|string|max:20',
        ]);
        $params = ParametresEntreprise::get();
        $params->update($validated);
        return redirect()->route('parametres.index')->with('success', 'Paramètres enregistrés.');
    }

    /**
     * Store a new category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
        ]);

        Categorie::create($validated);

        return redirect()->route('parametres.index')->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Update a category
     */
    public function updateCategory(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string',
        ]);

        $categorie->update($validated);

        return redirect()->route('parametres.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Delete a category
     */
    public function deleteCategory(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('parametres.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}
