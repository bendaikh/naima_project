@extends('layouts.dashboard')

@section('title', 'Modifier - Bon de Retour')

@section('content')
<style>
    .article-preview img {
        max-width: 60px;
        max-height: 60px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }
</style>

<div class="max-w-6xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Modifier le bon de retour</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('bon-retour.update', $bonRetour->id) }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf @method('PUT')

        <!-- Bon de Livraison & Image Preview Section -->
        <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bon de Livraison *</label>
                <select name="bon_livraison_id" id="bon_livraison_id" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option value="">Sélectionner un bon</option>
                    @foreach($bonsLivraison as $bon)
                        <option value="{{ $bon->id }}" data-client="{{ $bon->client->nom_raison_sociale ?? 'N/A' }}" data-numero="{{ $bon->numero }}" data-date="{{ $bon->date->format('d/m/Y') }}" data-signature="{{ $bon->devis?->signature_image }}" {{ $bonRetour->bon_livraison_id === $bon->id ? 'selected' : '' }}>
                            {{ $bon->numero }} - {{ $bon->client->nom_raison_sociale ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Date *</label>
                <input type="date" name="date" value="{{ old('date', $bonRetour->date->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
            </div>
        </div>

        <!-- Devis Image Preview -->
        <div id="devis-image-container" class="p-4 border-2 border-blue-200 rounded-lg bg-blue-50 text-center {{ !($bonRetour->bonLivraison && $bonRetour->bonLivraison->devis) ? 'hidden' : '' }}">
            <h3 class="text-sm font-semibold text-blue-700 mb-2">Aperçu du devis</h3>
            <div class="flex flex-col items-center gap-2">
                <img id="devis-image" src="{{ $bonRetour->bonLivraison && $bonRetour->bonLivraison->devis ? '/storage/' . $bonRetour->bonLivraison->devis->signature_image : '' }}" alt="Devis signature" class="{{ $bonRetour->bonLivraison && $bonRetour->bonLivraison->devis && $bonRetour->bonLivraison->devis->signature_image ? '' : 'hidden' }}" style="max-width: 200px; max-height: 150px; margin: 0 auto; object-fit: cover;">
                <p id="devis-missing" class="hidden text-xs text-slate-600"></p>
                <a id="devis-link" href="#" class="hidden text-xs font-semibold text-blue-700 hover:text-blue-800">Voir le devis</a>
            </div>
        </div>

        <!-- Bon Details -->
        <div class="grid grid-cols-3 gap-4 border-b border-slate-200 pb-6">
            <div class="bg-white rounded p-3 border border-slate-200">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Client</p>
                <p class="mt-2 text-sm font-semibold text-slate-800" id="bon_client">{{ $bonRetour->bonLivraison?->client->nom_raison_sociale ?? '—' }}</p>
            </div>
            <div class="bg-white rounded p-3 border border-slate-200">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Numéro du bon</p>
                <p class="mt-2 text-sm font-semibold text-slate-800" id="bon_numero">{{ $bonRetour->bonLivraison?->numero ?? '—' }}</p>
            </div>
            <div class="bg-white rounded p-3 border border-slate-200">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Statut</p>
                <p class="mt-2 text-sm font-semibold" id="bon_status"><span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Livré</span></p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Motif du retour</label>
            <textarea name="motif" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 h-20">{{ old('motif', $bonRetour->motif) }}</textarea>
        </div>

        <!-- Articles returned section -->
        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Articles retournés</h3>
            <div id="lignes-container" class="space-y-4">
                @forelse($bonRetour->lignes as $index => $ligne)
                    <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-slate-200 rounded-lg">
                        <div class="col-span-2">
                            <div class="article-preview">
                                @if($ligne->article && $ligne->article->image)
                                    <img src="/storage/{{ $ligne->article->image }}" alt="{{ $ligne->designation }}" class="w-full">
                                @else
                                    <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-slate-700">Désignation *</label>
                            <input type="text" name="lignes[{{ $index }}][designation]" value="{{ $ligne->designation }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-slate-700">Quantité *</label>
                            <input type="number" step="1" name="lignes[{{ $index }}][quantite]" value="{{ $ligne->quantite }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                            @php
                                $livreeQty = $bonRetour->bonLivraison?->lignes?->firstWhere('designation', $ligne->designation)?->quantite;
                                $devisQty = $bonRetour->bonLivraison?->devis?->lignes?->firstWhere('designation', $ligne->designation)?->quantite;
                                $livreeLabel = $livreeQty !== null ? number_format($livreeQty, 2, ',', ' ') : '—';
                                $devisLabel = $devisQty !== null ? number_format($devisQty, 2, ',', ' ') : null;
                            @endphp
                            <div class="mt-1 text-xs text-slate-500">
                                Qté livrée: <span class="font-medium text-slate-700">{{ $livreeLabel }}</span>
                                @if($devisLabel !== null)
                                    • Qté devis: <span class="font-medium text-slate-700">{{ $devisLabel }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2 flex items-end">
                            <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</button>
                        </div>
                    </div>
                @empty
                    <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-slate-200 rounded-lg">
                        <div class="col-span-2">
                            <div class="article-preview">
                                <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
                            </div>
                        </div>
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-slate-700">Désignation *</label>
                            <input type="text" name="lignes[0][designation]" placeholder="Produit" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-slate-700">Quantité *</label>
                            <input type="number" step="1" name="lignes[0][quantite]" placeholder="0" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                            <div class="mt-1 text-xs text-slate-500">Qté livrée: <span class="font-medium text-slate-700">—</span></div>
                        </div>
                        <div class="col-span-2 flex items-end">
                            <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</button>
                        </div>
                    </div>
                @endforelse
            </div>

            <button type="button" onclick="addLigne()" class="mt-4 rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-300">
                + Ajouter une ligne
            </button>
        </div>

        <div class="flex gap-4 border-t border-slate-200 pt-6">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('bon-retour.show', $bonRetour->id) }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>

@php
    $bonLivraisonPayload = $bonsLivraison->mapWithKeys(function ($bon) use ($articleImagesByName) {
        $devisQuantites = $bon->devis
            ? $bon->devis->lignes->mapWithKeys(fn($ligne) => [$ligne->designation => $ligne->quantite])->toArray()
            : [];

        $devisUrl = $bon->devis ? route('devis.show', $bon->devis->id, false) : null;

        return [
            (string) $bon->id => [
                'id' => $bon->id,
                'numero' => $bon->numero,
                'client' => $bon->client->nom_raison_sociale ?? 'N/A',
                'date' => $bon->date->format('d/m/Y'),
                'statut' => $bon->statut,
                'devis_numero' => $bon->devis?->numero,
                'devis_url' => $devisUrl,
                'signature_url' => $bon->devis?->signature_image ? '/storage/' . $bon->devis->signature_image : null,
                'lignes' => $bon->lignes
                    ->filter(fn($l) => (float) $l->quantite > 0)
                    ->map(function ($l) use ($devisQuantites, $articleImagesByName) {
                    $imagePath = optional($l->article)->image ?? ($articleImagesByName[$l->designation] ?? null);
                    return [
                        'id' => $l->id,
                        'designation' => $l->designation,
                        'quantite' => $l->quantite,
                        'livree_quantite' => $l->quantite,
                        'article_id' => $l->article_id,
                        'image_url' => $imagePath ? '/storage/' . $imagePath : null,
                        'devis_quantite' => $devisQuantites[$l->designation] ?? null,
                    ];
                })->values(),
            ],
        ];
    })->toArray();
@endphp

<script>
let ligneCount = {{ count($bonRetour->lignes) }};
const bonLivraisonData = @json($bonLivraisonPayload);

function renderStatusBadge(status) {
    if (!status) {
        return '—';
    }

    const normalized = String(status);
    const map = {
        brouillon: { label: 'Brouillon', classes: 'bg-slate-100 text-slate-700' },
        en_attente: { label: 'En attente', classes: 'bg-amber-100 text-amber-700' },
        livre: { label: 'Livré', classes: 'bg-green-100 text-green-700' },
        validé: { label: 'Validé', classes: 'bg-blue-100 text-blue-700' },
        valide: { label: 'Validé', classes: 'bg-blue-100 text-blue-700' },
        annule: { label: 'Annulé', classes: 'bg-red-100 text-red-700' },
        annulé: { label: 'Annulé', classes: 'bg-red-100 text-red-700' },
    };

    const config = map[normalized] || {
        label: normalized.replace(/_/g, ' '),
        classes: 'bg-slate-100 text-slate-700',
    };

    return `<span class="px-3 py-1 rounded-full ${config.classes} text-xs font-medium">${config.label}</span>`;
}

function formatQty(value) {
    if (value === null || value === undefined || value === '') {
        return '—';
    }
    const number = Number(value);
    if (Number.isNaN(number)) {
        return String(value);
    }
    return number.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function buildQtyHint(ligne) {
    const delivered = formatQty(ligne.livree_quantite ?? ligne.quantite);
    const devisQty = ligne.devis_quantite;
    const devisHtml = (devisQty !== null && devisQty !== undefined && devisQty !== '')
        ? ` • Qté devis: <span class="font-medium text-slate-700">${formatQty(devisQty)}</span>`
        : '';

    return `<div class="mt-1 text-xs text-slate-500">Qté livrée: <span class="font-medium text-slate-700">${delivered}</span>${devisHtml}</div>`;
}
function loadBonLivraisonDetails() {
    const select = document.getElementById('bon_livraison_id');
    const selectedValue = select.value;
    
    if (!selectedValue || !bonLivraisonData[selectedValue]) {
        document.getElementById('bon_client').textContent = '—';
        document.getElementById('bon_numero').textContent = '—';
        document.getElementById('bon_status').innerHTML = '—';
        document.getElementById('devis-image-container').classList.add('hidden');
        return;
    }
    
    const bon = bonLivraisonData[selectedValue];
    document.getElementById('bon_client').textContent = bon.client || '—';
    document.getElementById('bon_numero').textContent = bon.numero || '—';
    document.getElementById('bon_status').innerHTML = renderStatusBadge(bon.statut);

    // Load articles from bon de livraison
    loadBonArticles(selectedValue);
}

function loadBonArticles(bonId) {
    const container = document.getElementById('lignes-container');
    const imageContainer = document.getElementById('devis-image-container');
    const imageEl = document.getElementById('devis-image');
    const missingEl = document.getElementById('devis-missing');
    const linkEl = document.getElementById('devis-link');
    
    if (!bonId || !bonLivraisonData[bonId]) {
        imageContainer.classList.add('hidden');
        return;
    }

    const bon = bonLivraisonData[bonId];
    
    if (bon.signature_url || bon.devis_url) {
        imageContainer.classList.remove('hidden');
        if (bon.signature_url) {
            imageEl.src = bon.signature_url;
            imageEl.classList.remove('hidden');
            missingEl.classList.add('hidden');
        } else {
            imageEl.removeAttribute('src');
            imageEl.classList.add('hidden');
            missingEl.textContent = bon.devis_url ? 'Aucun aperçu: devis sans signature.' : 'Aucun devis associé.';
            missingEl.classList.remove('hidden');
        }

        if (bon.devis_url) {
            linkEl.href = bon.devis_url;
            linkEl.textContent = bon.devis_numero ? `Voir devis ${bon.devis_numero}` : 'Voir le devis';
            linkEl.classList.remove('hidden');
        } else {
            linkEl.classList.add('hidden');
        }
    } else {
        imageContainer.classList.add('hidden');
    }

    // Clear existing lignes
    container.innerHTML = '';
    ligneCount = 0;

    // Populate articles from bon de livraison
    if (bon.lignes && bon.lignes.length > 0) {
        bon.lignes.forEach((ligne, index) => {
            container.appendChild(createLigneElement(index, ligne));
        });
        ligneCount = bon.lignes.length;
    } else {
        // If no articles, show empty row
        container.appendChild(getEmptyLigne(0));
        ligneCount = 1;
    }
}

function createLigneElement(index, ligne) {
    const div = document.createElement('div');
    div.className = 'ligne-item grid grid-cols-12 gap-4 p-4 border border-slate-200 rounded-lg';
    
    const imageHtml = ligne.image_url 
        ? `<img src="${ligne.image_url}" alt="${ligne.designation}" class="w-full">`
        : '<div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>';
    
    div.innerHTML = `
        <div class="col-span-2">
            <div class="article-preview">
                ${imageHtml}
            </div>
        </div>
        <div class="col-span-5">
            <label class="block text-sm font-medium text-slate-700">Désignation *</label>
            <input type="text" name="lignes[${index}][designation]" value="${ligne.designation}" placeholder="Produit" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>
        <div class="col-span-3">
            <label class="block text-sm font-medium text-slate-700">Quantité *</label>
            <input type="number" step="1" name="lignes[${index}][quantite]" value="${ligne.quantite}" placeholder="0" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
            ${buildQtyHint(ligne)}
        </div>
        <div class="col-span-2 flex items-end">
            <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</button>
        </div>
    `;
    
    return div;
}

function getEmptyLigne(index) {
    const div = document.createElement('div');
    div.className = 'ligne-item grid grid-cols-12 gap-4 p-4 border border-slate-200 rounded-lg';
    
    div.innerHTML = `
        <div class="col-span-2">
            <div class="article-preview">
                <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
            </div>
        </div>
        <div class="col-span-5">
            <label class="block text-sm font-medium text-slate-700">Désignation *</label>
            <input type="text" name="lignes[${index}][designation]" placeholder="Produit" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>
        <div class="col-span-3">
            <label class="block text-sm font-medium text-slate-700">Quantité *</label>
            <input type="number" step="1" name="lignes[${index}][quantite]" placeholder="0" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>
        <div class="col-span-2 flex items-end">
            <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</button>
        </div>
    `;
    
    return div;
}

function addLigne() {
    const container = document.getElementById('lignes-container');
    container.appendChild(getEmptyLigne(ligneCount));
    ligneCount++;
}

function removeLigne(button) {
    const container = document.getElementById('lignes-container');
    if (container.children.length > 1) {
        button.closest('.ligne-item').remove();
    }
}

// Load articles if bon is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('bon_livraison_id');
    if (!select) {
        return;
    }

    select.addEventListener('change', loadBonLivraisonDetails);
    if (select.value) {
        loadBonLivraisonDetails();
    }
});
</script>
@endsection
