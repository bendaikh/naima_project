<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function index()
    {
        return view('projets.index');
    }

    public function create()
    {
        return view('projets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'budget' => 'nullable|numeric|min:0',
        ]);

        // TODO: Create projet in database
        return redirect()->route('projets.index')->with('success', 'Projet créé avec succès');
    }

    public function show($id)
    {
        return view('projets.show');
    }

    public function edit($id)
    {
        return view('projets.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Update projet in database
        return redirect()->route('projets.index')->with('success', 'Projet mis à jour avec succès');
    }

    public function destroy($id)
    {
        // TODO: Delete projet from database
        return redirect()->route('projets.index')->with('success', 'Projet supprimé avec succès');
    }
}
