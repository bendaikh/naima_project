<?php

namespace App\Http\Controllers;

use App\Models\BonRetourFournisseur;
use App\Models\BonDeCommande;
use App\Models\Fournisseur;
use App\Services\PurchaseReturnService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BonRetourFournisseurController extends Controller
{
    protected PurchaseReturnService $service;

    public function __construct(PurchaseReturnService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of supplier returns
     */
    public function index(): View
    {
        $bonRetours = BonRetourFournisseur::with('bonDeCommande', 'fournisseur', 'lignes')
            ->orderBy('return_date', 'desc')
            ->paginate(15);

        return view('achats.bon-retour-fournisseur.index', compact('bonRetours'));
    }

    /**
     * Show the form for creating a new supplier return
     */
    public function create(): View
    {
        $bonsDeCommande = BonDeCommande::whereIn('status', ['CONFIRMED', 'RECEIVED', 'COMPLETED'])
            ->with('fournisseur')
            ->orderBy('order_date', 'desc')
            ->get();

        return view('achats.bon-retour-fournisseur.create', compact('bonsDeCommande'));
    }

    /**
     * Get bon de commande data for AJAX
     */
    public function getBonDeCommandeData(BonDeCommande $bonDeCommande): \Illuminate\Http\JsonResponse
    {
        $bonDeCommande->load('lignes.article');
        return response()->json([
            'fournisseur_id' => $bonDeCommande->fournisseur_id,
            'fournisseur_nom' => $bonDeCommande->fournisseur->nom,
            'lignes' => $bonDeCommande->lignes->map(function ($ligne) {
                $imagePath = $ligne->article?->image ?? $ligne->image;
                return [
                    'id' => $ligne->id,
                    'product_name' => $ligne->product_name ?? $ligne->article->nom ?? 'Unknown',
                    'article_id' => $ligne->article_id,
                    'quantity_received' => $ligne->received_quantity,
                    'quantity_already_returned' => $ligne->getAlreadyReturnedQuantity(),
                    'max_returnable' => $ligne->getReturnableQuantity(),
                    'purchase_price' => $ligne->purchase_price,
                    'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created supplier return
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bon_de_commande_id' => 'required|exists:bons_de_commande,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'return_date' => 'required|date',
            'return_type' => 'required|in:REPLACEMENT,REFUND',
            'return_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.bon_de_commande_ligne_id' => 'required|exists:bons_de_commande_lignes,id',
            'lignes.*.return_quantity' => 'required|numeric|min:0.01',
        ]);

        // Validate return quantities
        $errors = $this->service->validateReturn($validated);
        if (!empty($errors)) {
            return back()->withInput()->withErrors(['validation' => implode(', ', $errors)]);
        }

        try {
            $bonRetour = $this->service->createPurchaseReturn($validated);
            return redirect()->route('achats.bon-retour-fournisseur.show', $bonRetour)
                ->with('success', 'Supplier return created successfully and stock updated.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified supplier return
     */
    public function show(BonRetourFournisseur $bonRetour): View
    {
        $bonRetour->load('bonDeCommande', 'fournisseur', 'lignes.article', 'avoir');
        return view('achats.bon-retour-fournisseur.show', compact('bonRetour'));
    }

    /**
     * Complete a supplier return
     */
    public function complete(BonRetourFournisseur $bonRetour): RedirectResponse
    {
        try {
            $this->service->completePurchaseReturn($bonRetour);
            return redirect()->route('achats.bon-retour-fournisseur.show', $bonRetour)
                ->with('success', 'Supplier return completed. ' . ($bonRetour->isRefund() ? 'Avoir Fournisseur created.' : ''));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process replacement reception (for REPLACEMENT type returns)
     */
    public function processReplacement(BonRetourFournisseur $bonRetour): RedirectResponse
    {
        try {
            $this->service->processReplacementReception($bonRetour, []);
            return redirect()->route('achats.bon-retour-fournisseur.show', $bonRetour)
                ->with('success', 'Replacement products received and stock updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a supplier return (only if PENDING)
     */
    public function destroy(BonRetourFournisseur $bonRetour): RedirectResponse
    {
        if ($bonRetour->status !== 'PENDING') {
            return back()->with('error', 'Can only delete pending returns.');
        }

        try {
            // Reverse stock movements (since stock was decremented on creation)
            // Re-add the stock for the returned items
            foreach ($bonRetour->lignes as $ligne) {
                $article = $ligne->article;
                $article->increment('quantite_stock', $ligne->return_quantity);
            }

            $bonRetour->delete();
            return redirect()->route('achats.bon-retour-fournisseur.index')
                ->with('success', 'Supplier return deleted and stock restored.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
