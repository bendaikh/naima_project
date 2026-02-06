<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::query()->orderBy('nom_raison_sociale');
        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($qry) use ($q) {
                $qry->where('nom_raison_sociale', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telephone', 'like', "%{$q}%");
            });
        }
        $clients = $query->paginate(15)->withQueryString();
        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create', ['client' => new Client]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_raison_sociale' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
        ]);
        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Client créé.');
    }

    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom_raison_sociale' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
        ]);
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }
}
