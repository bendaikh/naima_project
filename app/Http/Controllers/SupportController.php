<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        return view('support.index');
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,critique',
        ]);

        // TODO: Create support ticket in database
        return redirect()->route('support.index')->with('success', 'Ticket créé avec succès');
    }

    public function show($id)
    {
        return view('support.show');
    }

    public function update(Request $request, $id)
    {
        // TODO: Update support ticket in database
        return redirect()->route('support.show', $id)->with('success', 'Ticket mis à jour avec succès');
    }
}
