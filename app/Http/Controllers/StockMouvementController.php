<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\StockMouvement;
use App\Services\StockMouvementService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMouvementController extends Controller
{
    protected StockMouvementService $service;

    public function __construct(StockMouvementService $service)
    {
        $this->service = $service;
    }

    /**
     * Display all stock movements
     */
    public function index(Request $request): View
    {
        $query = StockMouvement::with('article', 'user')
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        // Filter by article
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by motif
        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $mouvements = $query->paginate(25)->withQueryString();
        $articles = Article::orderBy('nom')->get();

        return view('stock-mouvements.index', compact('mouvements', 'articles'));
    }

    /**
     * Display stock movements for a specific article
     */
    public function article(Article $article, Request $request): View
    {
        $query = StockMouvement::where('article_id', $article->id)
            ->with('user')
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $mouvements = $query->paginate(25)->withQueryString();
        
        // Get stock summary
        $summary = $this->service->getStockSummary($article);

        return view('stock-mouvements.article', compact('article', 'mouvements', 'summary'));
    }

    /**
     * Display a specific stock movement
     */
    public function show(StockMouvement $mouvement): View
    {
        $mouvement->load('article', 'user');
        return view('stock-mouvements.show', compact('mouvement'));
    }

    /**
     * Get movements for a specific reference (BonLivraison, BonRetour, etc.)
     */
    public function reference(Request $request): View
    {
        $referenceType = $request->reference_type;
        $referenceId = $request->reference_id;

        $mouvements = $this->service->getReferenceMouvements($referenceType, $referenceId);

        return view('stock-mouvements.reference', compact('mouvements', 'referenceType', 'referenceId'));
    }

    /**
     * Record a manual adjustment
     */
    public function adjustment(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'quantite' => 'required|numeric',
            'reason' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $article = Article::find($validated['article_id']);
        $mouvement = $this->service->recordAdjustment(
            $article,
            $validated['quantite'],
            $validated['reason'],
            $validated['description'] ?? null
        );

        return redirect()->route('stock-mouvements.show', $mouvement)
            ->with('success', 'Ajustement enregistré avec succès.');
    }

    /**
     * Export stock movements to CSV
     */
    public function export(Request $request)
    {
        $query = StockMouvement::with('article', 'user')
            ->orderByDesc('date');

        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $mouvements = $query->get();

        $csv = "Date,Article,Type,Quantité,Motif,Référence,Description,Utilisateur\n";
        foreach ($mouvements as $mouvement) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $mouvement->date->format('d/m/Y'),
                $mouvement->article->nom,
                $mouvement->getTypeLabel(),
                $mouvement->quantite,
                $mouvement->getMotifLabel(),
                "{$mouvement->reference_type}#{$mouvement->reference_id}",
                str_replace(',', ';', $mouvement->description ?? ''),
                $mouvement->user?->name ?? 'Système'
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=mouvements-stock-' . now()->format('Y-m-d') . '.csv',
        ]);
    }
}
