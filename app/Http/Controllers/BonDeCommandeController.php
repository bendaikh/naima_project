<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommande;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Categorie;
use App\Services\BonDeCommandeService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BonDeCommandeController extends Controller
{
    protected BonDeCommandeService $service;

    public function __construct(BonDeCommandeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of purchase orders
     */
    public function index(Request $request): View
    {
        $query = BonDeCommande::with('fournisseur', 'lignes');

        // Search by supplier name or order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('fournisseur', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by supplier
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $bonsDeCommande = $query->orderBy('order_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('achats.bon-de-commande.index', compact('bonsDeCommande'));
    }

    /**
     * Show the form for creating a new purchase order
     */
    public function create(): View
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $articles = Article::with('categorie')->orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();

        return view('achats.bon-de-commande.create', compact('fournisseurs', 'articles', 'categories'));
    }

    /**
     * Store a newly created purchase order in storage
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'status' => 'required|in:DRAFT,CONFIRMED',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'nullable|exists:articles,id',
            'lignes.*.product_name' => 'nullable|string',
            'lignes.*.categorie_id' => 'nullable|exists:categories,id',
            'lignes.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lignes.*.quantity' => 'required|numeric|min:0.01',
            'lignes.*.purchase_price' => 'required|numeric|min:0.01',
        ]);

        try {
            $bonDeCommande = $this->service->createBonDeCommande($validated);
            return redirect()->route('achats.bon-de-commande.show', $bonDeCommande)
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified purchase order
     */
    public function show(BonDeCommande $bonDeCommande): View
    {
        $bonDeCommande->load('fournisseur', 'lignes.article', 'retours');
        return view('achats.bon-de-commande.show', compact('bonDeCommande'));
    }

    public function print(BonDeCommande $bonDeCommande): View
    {
        $bonDeCommande->load('fournisseur', 'lignes.article');
        return view('prints.bon-de-commande', compact('bonDeCommande'));
    }

    /**
     * Show the form for editing a purchase order
     */
    public function edit(BonDeCommande $bonDeCommande): View
    {
        if ($bonDeCommande->status !== 'DRAFT') {
            abort(403, 'Can only edit purchase orders in DRAFT status.');
        }

        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $articles = Article::orderBy('nom')->get();

        return view('achats.bon-de-commande.edit', compact('bonDeCommande', 'fournisseurs', 'articles'));
    }

    /**
     * Update the specified purchase order
     */
    public function update(Request $request, BonDeCommande $bonDeCommande): RedirectResponse
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'nullable|exists:articles,id',
            'lignes.*.product_name' => 'nullable|string',
            'lignes.*.categorie_id' => 'nullable|exists:categories,id',
            'lignes.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lignes.*.quantity' => 'required|numeric|min:0.01',
            'lignes.*.purchase_price' => 'required|numeric|min:0.01',
        ]);

        try {
            $bonDeCommande = $this->service->updateBonDeCommande($bonDeCommande, $validated);
            return redirect()->route('achats.bon-de-commande.show', $bonDeCommande)
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm a purchase order
     */
    public function confirm(BonDeCommande $bonDeCommande): RedirectResponse
    {
        try {
            $this->service->confirmBonDeCommande($bonDeCommande);
            return redirect()->route('achats.bon-de-commande.show', $bonDeCommande)
                ->with('success', 'Purchase order confirmed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form to receive a purchase order
     */
    public function showReceiveForm(BonDeCommande $bonDeCommande): View
    {
        if (!in_array($bonDeCommande->status, ['CONFIRMED', 'RECEIVED'])) {
            abort(403, 'Can only receive confirmed purchase orders.');
        }

        $bonDeCommande->load('lignes.article');
        return view('achats.bon-de-commande.receive', compact('bonDeCommande'));
    }

    /**
     * Process the reception of a purchase order
     */
    public function receive(Request $request, BonDeCommande $bonDeCommande): RedirectResponse
    {
        try {
            // Get received data - may contain null values due to middleware
            $received = $request->input('received');
            
            if (!is_array($received)) {
                \Log::warning('Received is not an array', ['received' => $received]);
                return back()->with('error', 'Format de données invalide.');
            }

            // Map received quantities for all lines
            $receivedData = [];
            foreach ($bonDeCommande->lignes as $ligne) {
                $value = $received[$ligne->id] ?? null;
                // Convert null/empty to 0
                $receivedData[$ligne->id] = empty($value) ? 0 : (float)$value;
            }

            \Log::info('Processing reception', [
                'bon_id' => $bonDeCommande->id,
                'received_data' => $receivedData,
            ]);

            // Call service to update stock
            $this->service->receiveBonDeCommande($bonDeCommande, $receivedData);
            
            \Log::info('Reception completed successfully', ['bon_id' => $bonDeCommande->id]);

            return redirect()->route('achats.bon-de-commande.show', $bonDeCommande)
                ->with('success', 'Bon de commande reçu et stock mis à jour.');
        } catch (\Exception $e) {
            \Log::error('Error in receive method', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Delete a purchase order
     */
    public function destroy(BonDeCommande $bonDeCommande): RedirectResponse
    {
        try {
            $this->service->cancelBonDeCommande($bonDeCommande);
            return redirect()->route('achats.bon-de-commande.index')
                ->with('success', 'Purchase order cancelled.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
