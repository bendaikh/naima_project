<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display list of articles
     */
    public function index(Request $request): View
    {
        $query = Article::with('client')->orderByDesc('created_at');
        
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }
        
        $articles = $query->paginate(15)->withQueryString();
        
        return view('articles.index', compact('articles'));
    }

    /**
     * Show create form with multi-step interface
     */
    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        
        return view('articles.create', [
            'article' => new Article(),
            'clients' => $clients,
        ]);
    }

    /**
     * Store article with multi-step validation
     */
    public function store(Request $request)
    {
        // Validate all steps
        $validated = $request->validate([
            // Details tab
            'nom' => 'required|string|max:255',
            'ugs' => 'required|string|max:255|unique:articles,ugs',
            'impot' => 'required|numeric|in:5,10,20',
            'categorie' => 'required|string',
            'description' => 'nullable|string',
            
            // Tarifs tab
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'compte_revenu' => 'nullable|string',
            'compte_depense' => 'nullable|string',
            'unite' => 'required|in:dh,piece,kg,m',
            'quantite' => 'required|numeric|min:0',
            
            // Medias tab
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Entrepot tab
            'entrepot' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        // Create article
        $article = Article::create($validated);

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Article créé avec succès!');
    }

    /**
     * Display article details
     */
    public function show(Article $article): View
    {
        $article->load('client');
        
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ugs' => 'required|string|max:255|unique:articles,ugs,' . $article->id,
            'impot' => 'required|numeric|in:5,10,20',
            'categorie' => 'required|string',
            'description' => 'nullable|string',
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'compte_revenu' => 'nullable|string',
            'compte_depense' => 'nullable|string',
            'unite' => 'required|in:dh,piece,kg,m',
            'quantite' => 'required|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'entrepot' => 'nullable|string',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        $article->update($validated);

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Article mis à jour avec succès!');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article supprimé avec succès!');
    }
}
