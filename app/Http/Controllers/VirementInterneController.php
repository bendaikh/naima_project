<?php

namespace App\Http\Controllers;

use App\Models\VirementInterne;
use App\Models\Banque;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class VirementInterneController extends Controller
{
    public function index(Request $request): View
    {
        $query = VirementInterne::with(['deCompte', 'versCompte'])->orderBy('date', 'desc');
        
        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($qry) use ($q) {
                $qry->where('description', 'like', "%{$q}%");
            });
        }
        
        $virements = $query->paginate(15)->withQueryString();
        return view('virement-interne.index', compact('virements'));
    }

    public function create(): View
    {
        $banques = Banque::orderBy('libelle')->get();
        return view('virement-interne.create', compact('banques'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'de_compte_id' => 'required|exists:banques,id|different:vers_compte_id',
            'vers_compte_id' => 'required|exists:banques,id',
            'type' => 'nullable|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        $validated['type'] = $validated['type'] ?? 'Virement bancaire';
        
        DB::transaction(function () use ($validated) {
            VirementInterne::create($validated);
            
            $banqueSource = Banque::find($validated['de_compte_id']);
            $banqueSource->solde_actuel -= $validated['montant'];
            $banqueSource->save();
            
            $banqueDestination = Banque::find($validated['vers_compte_id']);
            $banqueDestination->solde_actuel += $validated['montant'];
            $banqueDestination->save();
        });
        
        return redirect()->route('virement-interne.index')->with('success', 'Virement effectué avec succès.');
    }

    public function show(VirementInterne $virementInterne): View
    {
        $virementInterne->load(['deCompte', 'versCompte']);
        return view('virement-interne.show', compact('virementInterne'));
    }

    public function edit(VirementInterne $virementInterne): View
    {
        $banques = Banque::orderBy('libelle')->get();
        return view('virement-interne.edit', compact('virementInterne', 'banques'));
    }

    public function update(Request $request, VirementInterne $virementInterne)
    {
        $validated = $request->validate([
            'de_compte_id' => 'required|exists:banques,id|different:vers_compte_id',
            'vers_compte_id' => 'required|exists:banques,id',
            'type' => 'nullable|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        DB::transaction(function () use ($validated, $virementInterne) {
            $oldSource = Banque::find($virementInterne->de_compte_id);
            $oldSource->solde_actuel += $virementInterne->montant;
            $oldSource->save();
            
            $oldDestination = Banque::find($virementInterne->vers_compte_id);
            $oldDestination->solde_actuel -= $virementInterne->montant;
            $oldDestination->save();
            
            $virementInterne->update($validated);
            
            $newSource = Banque::find($validated['de_compte_id']);
            $newSource->solde_actuel -= $validated['montant'];
            $newSource->save();
            
            $newDestination = Banque::find($validated['vers_compte_id']);
            $newDestination->solde_actuel += $validated['montant'];
            $newDestination->save();
        });
        
        return redirect()->route('virement-interne.index')->with('success', 'Virement mis à jour avec succès.');
    }

    public function destroy(VirementInterne $virementInterne)
    {
        DB::transaction(function () use ($virementInterne) {
            $banqueSource = Banque::find($virementInterne->de_compte_id);
            $banqueSource->solde_actuel += $virementInterne->montant;
            $banqueSource->save();
            
            $banqueDestination = Banque::find($virementInterne->vers_compte_id);
            $banqueDestination->solde_actuel -= $virementInterne->montant;
            $banqueDestination->save();
            
            $virementInterne->delete();
        });
        
        return redirect()->route('virement-interne.index')->with('success', 'Virement annulé avec succès.');
    }
}
