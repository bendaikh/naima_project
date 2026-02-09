<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::paginate(15);
        return view('articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        \Log::info('Article store called', [
            'user' => auth()->user()?->id,
            'has_csrf' => $request->has('_token'),
            'session_id' => session()->getId(),
        ]);
        
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'categorie' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'quantite' => 'required|numeric|min:0',
            'quantite_stock' => 'nullable|numeric|min:0',
            'unite' => 'required|string|max:50',
            'numero_facture' => 'nullable|string|unique:articles,numero_facture',
            'compte_revenu' => 'nullable|string|max:255',
            'compte_depense' => 'nullable|string|max:255',
            'image_path' => 'nullable|string',
            'entrepot' => 'nullable|string|max:255',
            'ugs' => 'nullable|string|max:255',
            'impot' => 'nullable|numeric|min:0',
        ]);

        // If quantite_stock is not provided, set it equal to quantite
        if (!isset($validated['quantite_stock']) || is_null($validated['quantite_stock'])) {
            $validated['quantite_stock'] = $validated['quantite'];
        }

        Article::create($validated);
        return redirect()->route('articles.index')->with('success', 'Article créé avec succès.');
    }

    public function show(Article $article): View
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'categorie' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'quantite' => 'required|numeric|min:0',
            'quantite_stock' => 'nullable|numeric|min:0',
            'unite' => 'required|string|max:50',
            'numero_facture' => 'nullable|string|unique:articles,numero_facture,' . $article->id,
            'compte_revenu' => 'nullable|string|max:255',
            'compte_depense' => 'nullable|string|max:255',
            'image_path' => 'nullable|string',
            'entrepot' => 'nullable|string|max:255',
            'ugs' => 'nullable|string|max:255',
            'impot' => 'nullable|numeric|min:0',
        ]);

        $article->update($validated);
        return redirect()->route('articles.show', $article)->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article supprimé avec succès.');
    }
}
