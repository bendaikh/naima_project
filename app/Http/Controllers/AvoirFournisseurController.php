<?php

namespace App\Http\Controllers;

use App\Models\AvoirFournisseur;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AvoirFournisseurController extends Controller
{
    /**
     * Display a listing of supplier credit notes
     */
    public function index(): View
    {
        $avoirs = AvoirFournisseur::with('fournisseur', 'bonRetourFournisseur')
            ->orderBy('avoir_date', 'desc')
            ->paginate(15);

        return view('achats.avoir-fournisseur.index', compact('avoirs'));
    }

    /**
     * Display the specified avoir
     */
    public function show(AvoirFournisseur $avoir): View
    {
        $avoir->load('fournisseur', 'bonRetourFournisseur.lignes.article');
        return view('achats.avoir-fournisseur.show', compact('avoir'));
    }

    /**
     * Mark avoir as received
     */
    public function markAsReceived(AvoirFournisseur $avoir): RedirectResponse
    {
        try {
            $avoir->update(['status' => 'RECEIVED']);
            return redirect()->route('achats.avoir-fournisseur.show', $avoir)
                ->with('success', 'Avoir marked as received.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an avoir (only if PENDING)
     */
    public function destroy(AvoirFournisseur $avoir): RedirectResponse
    {
        if ($avoir->status !== 'PENDING') {
            return back()->with('error', 'Can only delete pending avoirs.');
        }

        try {
            $avoir->delete();
            return redirect()->route('achats.avoir-fournisseur.index')
                ->with('success', 'Avoir deleted.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
