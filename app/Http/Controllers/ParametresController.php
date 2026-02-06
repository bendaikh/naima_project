<?php

namespace App\Http\Controllers;

use App\Models\ParametresEntreprise;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParametresController extends Controller
{
    public function index(): View
    {
        $params = ParametresEntreprise::get();
        return view('parametres.index', compact('params'));
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
}
