@extends('layouts.dashboard')

@section('title', 'Créer un bon de retour')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Créer un bon de retour</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700 border-l-4 border-red-400">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('bon-retour.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf

        <!-- Bon de Livraison Section -->
        <div class="border-b border-slate-200 pb-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Bon de Livraison</h2>
            
            <div class="rounded-lg bg-gradient-to-br from-slate-50 to-slate-100 p-6 border border-slate-200">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Bon de Livraison *</label>
                        <select name="bon_livraison_id" id="bon_livraison_id" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" onchange="loadBonLivraisonDetails()">
                            <option value="">Sélectionner un bon</option>
                            @foreach($bonsLivraison as $bon)
                                <option value="{{ $bon->id }}" data-client="{{ $bon->client->nom_raison_sociale ?? 'N/A' }}" data-numero="{{ $bon->numero }}" data-date="{{ $bon->date->format('d/m/Y') }}">
                                    {{ $bon->numero }} - {{ $bon->client->nom_raison_sociale ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de livraison</label>
                        <input type="text" id="bon_livraison_date" readonly class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-100 text-slate-600" />
                    </div>
                </div>

                <!-- Bon Details -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white rounded p-3 border border-slate-200">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Client</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800" id="bon_client">—</p>
                    </div>
                    <div class="bg-white rounded p-3 border border-slate-200">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Numéro du bon</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800" id="bon_numero">—</p>
                    </div>
                    <div class="bg-white rounded p-3 border border-slate-200">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Statut</p>
                        <p class="mt-2 text-sm font-semibold" id="bon_status">—</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Information Section -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-slate-800">Informations du retour</h2>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Date *</label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Motif du retour</label>
                <textarea name="motif" class="w-full rounded-lg border border-slate-300 px-4 py-2 h-20 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
            </div>
        </div>

        <!-- Articles returned section -->
        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Articles retournés</h3>
            <div id="lignes-container" class="space-y-4">
                <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-slate-200 rounded-lg bg-slate-50">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Désignation *</label>
                        <input type="text" name="lignes[0][designation]" placeholder="Produit" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Quantité *</label>
                        <input type="number" step="1" name="lignes[0][quantite]" placeholder="0" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addLigne()" class="mt-4 rounded-lg bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-200 transition-colors">
                + Ajouter une ligne
            </button>
        </div>

        <div class="flex gap-4 border-t border-slate-200 pt-6">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Créer</button>
            <a href="{{ route('bon-retour.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Annuler</a>
        </div>
    </form>
</div>

<script>
let ligneCount = 1;

function addLigne() {
    const container = document.getElementById('lignes-container');
    const template = container.firstElementChild.cloneNode(true);
    
    template.querySelectorAll('input').forEach(input => {
        const name = input.name.replace(/\[\d+\]/, `[${ligneCount}]`);
        input.name = name;
        input.value = '';
    });
    
    container.appendChild(template);
    ligneCount++;
}

function removeLigne(button) {
    const container = document.getElementById('lignes-container');
    if (container.children.length > 1) {
        button.closest('.ligne-item').remove();
    }
}

function loadBonLivraisonDetails() {
    const select = document.getElementById('bon_livraison_id');
    const selectedOption = select.options[select.selectedIndex];
    
    const clientName = selectedOption.dataset.client || '—';
    const bonNumero = selectedOption.dataset.numero || '—';
    const bonDate = selectedOption.dataset.date || '—';
    
    document.getElementById('bon_client').textContent = clientName;
    document.getElementById('bon_numero').textContent = bonNumero;
    document.getElementById('bon_livraison_date').value = bonDate;
    
    // Update status badge
    const statusEl = document.getElementById('bon_status');
    statusEl.innerHTML = '<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Livré</span>';
}
</script>
@endsection
