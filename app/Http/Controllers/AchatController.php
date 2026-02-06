<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchatController extends Controller
{
    public function index()
    {
        return view('achats.index');
    }

    public function create()
    {
        return view('achats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|unique:achats',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date' => 'required|date',
            'montant' => 'required|numeric|min:0',
        ]);

        // TODO: Create achat in database
        return redirect()->route('achats.index')->with('success', 'Achat créé avec succès');
    }

    public function show($id)
    {
        return view('achats.show');
    }

    public function edit($id)
    {
        return view('achats.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Update achat in database
        return redirect()->route('achats.index')->with('success', 'Achat mis à jour avec succès');
    }

    public function destroy($id)
    {
        // TODO: Delete achat from database
        return redirect()->route('achats.index')->with('success', 'Achat supprimé avec succès');
    }
}
