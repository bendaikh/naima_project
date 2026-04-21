<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\Devis;
use App\Models\DevisLigne;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DevisController extends Controller
{
    public function index(Request $request): View
    {
        $query = Devis::with('client')->orderByDesc('date');
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function($q) use ($recherche) {
                $q->where('numero', 'like', "%{$recherche}%")
                  ->orWhereHas('client', function($q) use ($recherche) {
                      $q->where('nom_raison_sociale', 'like', "%{$recherche}%");
                  });
            });
        }
        
        $devis = $query->paginate(15)->withQueryString();
        return view('devis.index', compact('devis'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $articles = Article::orderBy('nom')->get();
        return view('devis.create', ['devis' => new Devis, 'clients' => $clients, 'articles' => $articles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'tva' => 'nullable|numeric|min:0|max:100',
            'disponibilite' => 'nullable|string|max:50',
            'articles_data' => 'nullable|json',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $params = \App\Models\ParametresEntreprise::get();
        $validated['numero'] = $params->generateDocumentNumber('devis', $validated['date']);
        $validated['tva'] = $validated['tva'] ?? $params->tva_par_defaut;
        
        // Initialize totals
        $validated['total_ht'] = 0;
        $validated['total_ttc'] = 0;
        
        // Parse articles data if provided
        $articlesData = [];
        if ($validated['articles_data']) {
            $articlesData = json_decode($validated['articles_data'], true) ?? [];
            
            // Calculate totals from articles
            foreach ($articlesData as $article) {
                $validated['total_ht'] += floatval($article['total_ht']);
            }
            
            // Calculate TTC
            $tva = $validated['total_ht'] * ($validated['tva'] / 100);
            $validated['total_ttc'] = $validated['total_ht'] + $tva;
        }
        
        $validated['statut'] = 'brouillon';
        
        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            $validated['signature_image'] = $request->file('signature_image')->store('devis/signatures', 'public');
        }
        
        // Remove articles_data from fillable update
        unset($validated['articles_data']);
        
        // Create devis
        $devis = Devis::create($validated);
        
        // Create devis lines from articles
        foreach ($articlesData as $article) {
            DevisLigne::create([
                'devis_id' => $devis->id,
                'designation' => $article['designation'],
                'quantite' => floatval($article['quantite']),
                'prix_unitaire' => floatval($article['prix_unitaire']),
                'tva' => $validated['tva'],
                'total_ht' => floatval($article['total_ht']),
            ]);
        }
        
        $params->increment('prochain_numero_devis');
        return redirect()->route('devis.show', $devis)->with('success', 'Devis créé.');
    }

    public function show(Devis $devis): View
    {
        $devis->load('client', 'lignes');
        return view('devis.show', compact('devis'));
    }

    public function print(Devis $devis): View
    {
        $devis->load('client', 'lignes');
        return view('prints.devis', compact('devis'));
    }

    public function edit(Devis $devis): View
    {
        $devis->load('lignes');
        $clients = Client::orderBy('nom_raison_sociale')->get();
        $articles = Article::orderBy('nom')->get();
        return view('devis.edit', compact('devis', 'clients', 'articles'));
    }

    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'tva' => 'nullable|numeric|min:0|max:100',
            'disponibilite' => 'nullable|string|max:50',
            'statut' => 'required|in:brouillon,envoye,accepte,refuse',
            'articles_data' => 'nullable|json',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $articlesData = [];
        $hasArticlesData = $request->has('articles_data');
        if ($validated['articles_data'] ?? null) {
            $articlesData = json_decode($validated['articles_data'], true) ?? [];
        }

        $updateData = $validated;
        unset($updateData['articles_data']);

        if (!array_key_exists('tva', $updateData) || $updateData['tva'] === null) {
            $updateData['tva'] = $devis->tva;
        }

        if ($request->hasFile('signature_image')) {
            if ($devis->signature_image) {
                \Storage::disk('public')->delete($devis->signature_image);
            }
            $updateData['signature_image'] = $request->file('signature_image')->store('devis/signatures', 'public');
        }

        if ($hasArticlesData) {
            $totalHt = 0;
            foreach ($articlesData as $article) {
                $totalHt += floatval($article['total_ht'] ?? 0);
            }
            $updateData['total_ht'] = $totalHt;
            $tvaRate = ($updateData['tva'] ?? 0) / 100;
            $totalTva = $totalHt * $tvaRate;
            $updateData['total_ttc'] = $totalHt + $totalTva;
        }

        $devis->update($updateData);

        if ($hasArticlesData) {
            $devis->lignes()->delete();
            foreach ($articlesData as $article) {
                DevisLigne::create([
                    'devis_id' => $devis->id,
                    'designation' => $article['designation'] ?? '',
                    'quantite' => floatval($article['quantite'] ?? 0),
                    'prix_unitaire' => floatval($article['prix_unitaire'] ?? 0),
                    'tva' => $updateData['tva'] ?? 0,
                    'total_ht' => floatval($article['total_ht'] ?? 0),
                ]);
            }
        }

        return redirect()->route('devis.show', $devis)->with('success', 'Devis mis à jour.');
    }

    public function destroy(Devis $devis)
    {
        $devis->delete();
        return redirect()->route('devis.index')->with('success', 'Devis supprimé.');
    }

    /**
     * Mark devis as sent
     */
    public function markAsSent(Devis $devis)
    {
        if ($devis->markAsSent()) {
            return redirect()->route('devis.show', $devis)->with('success', 'Devis marqué comme envoyé.');
        }
        return redirect()->route('devis.show', $devis)->with('error', 'Le devis ne peut pas être marqué comme envoyé.');
    }

    /**
     * Mark devis as accepted
     */
    public function markAsAccepted(Devis $devis)
    {
        if ($devis->markAsAccepted()) {
            return redirect()->route('devis.show', $devis)->with('success', 'Devis marqué comme accepté.');
        }
        return redirect()->route('devis.show', $devis)->with('error', 'Le devis ne peut pas être marqué comme accepté.');
    }

    /**
     * Mark devis as refused
     */
    public function markAsRefused(Devis $devis)
    {
        if ($devis->markAsRefused()) {
            return redirect()->route('devis.show', $devis)->with('success', 'Devis marqué comme refusé.');
        }
        return redirect()->route('devis.show', $devis)->with('error', 'Le devis ne peut pas être marqué comme refusé.');
    }

    /**
     * Convert devis to facture
     */
    public function convertToFacture(Devis $devis)
    {
        if (!$devis->canConvertToFacture()) {
            return redirect()->route('devis.show', $devis)->with('error', 'Le devis doit être accepté pour être converti en facture.');
        }

        $facture = $devis->convertToFacture();
        if ($facture) {
            return redirect()->route('factures.show', $facture)->with('success', 'Devis converti en facture avec succès.');
        }
        return redirect()->route('devis.show', $devis)->with('error', 'Erreur lors de la conversion du devis.');
    }
}
