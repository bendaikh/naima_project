<?php

namespace App\Http\Controllers;

use App\Models\Avoir;
use App\Models\Facture;
use App\Services\AvoirService;
use Illuminate\Http\Request;

class AvoirController extends Controller
{
    protected AvoirService $avoirService;

    public function __construct(AvoirService $avoirService)
    {
        $this->avoirService = $avoirService;
    }

    /**
     * Display all avoirs
     */
    public function index()
    {
        $avoirs = Avoir::with('client', 'facture', 'bonRetour', 'lignes')->paginate(15);
        return view('avoir.index', compact('avoirs'));
    }

    /**
     * Display avoir details
     */
    public function show(Avoir $avoir)
    {
        $avoir->load('client', 'facture', 'bonRetour', 'lignes');
        return view('avoir.show', compact('avoir'));
    }

    /**
     * Get avoirs for a facture
     */
    public function getForFacture(Facture $facture)
    {
        $avoirs = $this->avoirService->getAvoirsForFacture($facture);
        $totalAmount = $this->avoirService->getTotalAvoirAmount($facture);

        return response()->json([
            'data' => $avoirs->toArray(),
            'total' => $totalAmount,
        ]);
    }

    /**
     * Emit an avoir (change status from draft to issued)
     */
    public function emit(Avoir $avoir)
    {
        if ($avoir->statut === 'brouillon') {
            $avoir->emit();
            return redirect()->back()->with('success', 'Avoir émis avec succès');
        }

        return redirect()->back()->with('error', 'Impossible d\'émettre cet avoir');
    }

    /**
     * Apply an avoir (change status from issued to applied)
     */
    public function apply(Avoir $avoir)
    {
        if ($avoir->statut === 'emis') {
            $avoir->apply();
            return redirect()->back()->with('success', 'Avoir appliqué avec succès');
        }

        return redirect()->back()->with('error', 'Impossible d\'appliquer cet avoir');
    }

    /**
     * Delete an avoir (only if in draft status)
     */
    public function destroy(Avoir $avoir)
    {
        if ($avoir->statut === 'brouillon') {
            $avoir->delete();
            return redirect()->back()->with('success', 'Avoir supprimé avec succès');
        }

        return redirect()->back()->with('error', 'Impossible de supprimer un avoir émis ou appliqué');
    }
}
