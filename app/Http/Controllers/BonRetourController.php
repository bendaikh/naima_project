<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use App\Models\BonRetour;
use App\Models\Article;
use App\Services\BonRetourService;
use Illuminate\Http\Request;

class BonRetourController extends Controller
{
    protected BonRetourService $bonRetourService;

    public function __construct(BonRetourService $bonRetourService)
    {
        $this->bonRetourService = $bonRetourService;
    }

    /**
     * Display all returns
     */
    public function index(Request $request)
    {
        $query = BonRetour::with('client', 'bonLivraison', 'lignes');
        
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function($q) use ($recherche) {
                $q->where('numero', 'like', "%{$recherche}%")
                  ->orWhere('motif', 'like', "%{$recherche}%")
                  ->orWhereHas('client', function($q) use ($recherche) {
                      $q->where('nom_raison_sociale', 'like', "%{$recherche}%");
                  })
                  ->orWhereHas('bonLivraison', function($q) use ($recherche) {
                      $q->where('numero', 'like', "%{$recherche}%");
                  });
            });
        }
        
        $bonsRetour = $query->paginate(15)->withQueryString();
        return view('bon-retour.index', compact('bonsRetour'));
    }

    /**
     * Show form to create a return
     */
    public function create()
    {
        $bonsLivraison = BonLivraison::with(['client', 'lignes.article', 'devis.lignes'])
            ->where('statut', '!=', 'cancelled')
            ->get();
        $articleImagesByName = Article::pluck('image', 'nom')->toArray();
        return view('bon-retour.create', compact('bonsLivraison', 'articleImagesByName'));
    }

    /**
     * Store a new return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bon_livraison_id' => 'required|exists:bons_livraison,id',
            'motif' => 'nullable|string',
            'date' => 'required|date',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $bonRetour = $this->bonRetourService->create($validated);
            return redirect()->route('bon-retour.show', $bonRetour->id)
                ->with('success', 'Bon de retour créé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display a return
     */
    public function show(BonRetour $bonRetour)
    {
        $bonRetour->load('client', 'bonLivraison', 'facture', 'lignes');
        return view('bon-retour.show', compact('bonRetour'));
    }

    /**
     * Show form to edit a return
     */
    public function edit(BonRetour $bonRetour)
    {
        $bonRetour->load('lignes');
        $bonsLivraison = BonLivraison::with(['client', 'lignes.article', 'devis.lignes'])->get();
        $articleImagesByName = Article::pluck('image', 'nom')->toArray();
        return view('bon-retour.edit', compact('bonRetour', 'bonsLivraison', 'articleImagesByName'));
    }

    /**
     * Update a return
     */
    public function update(Request $request, BonRetour $bonRetour)
    {
        $validated = $request->validate([
            'bon_livraison_id' => 'required|exists:bons_livraison,id',
            'motif' => 'nullable|string',
            'date' => 'required|date',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $this->bonRetourService->update($bonRetour, $validated);
            return redirect()->route('bon-retour.show', $bonRetour->id)
                ->with('success', 'Bon de retour mis à jour avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a return
     */
    public function destroy(BonRetour $bonRetour)
    {
        $bonRetour->delete();
        return redirect()->route('bon-retour.index')
            ->with('success', 'Bon de retour supprimé avec succès');
    }

    /**
     * Get returns for a specific delivery (AJAX)
     */
    public function getForDelivery($deliveryId)
    {
        $bonLivraison = BonLivraison::findOrFail($deliveryId);
        $returns = $this->bonRetourService->getRetursForDelivery($bonLivraison);

        return response()->json([
            'data' => $returns->load('lignes')->toArray(),
            'total_returned' => $returns->sum(fn($r) => $r->getTotalReturnedQuantity()),
        ]);
    }
}
