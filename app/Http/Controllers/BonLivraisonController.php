<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BonLivraisonController extends Controller
{
    public function index()
    {
        return view('bon-livraison.index');
    }

    public function create()
    {
        return view('bon-livraison.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('bon-livraison.index')->with('success', 'Bon de livraison créé avec succès');
    }

    public function show($id)
    {
        return view('bon-livraison.show');
    }

    public function edit($id)
    {
        return view('bon-livraison.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update logic
        return redirect()->route('bon-livraison.index')->with('success', 'Bon de livraison mis à jour avec succès');
    }

    public function destroy($id)
    {
        // TODO: Implement destroy logic
        return redirect()->route('bon-livraison.index')->with('success', 'Bon de livraison supprimé avec succès');
    }
}

