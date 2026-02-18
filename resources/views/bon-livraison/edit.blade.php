@extends('layouts.dashboard')

@section('title', 'Modifier bon de livraison ' . $bonLivraison->numero)

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
        <h1 class="text-3xl font-bold text-[#1F2937]">Modifier bon de livraison {{ $bonLivraison->numero }}</h1>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-red-700 border-l-4 border-red-400">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bon-livraison.update', $bonLivraison) }}" class="space-y-6 rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <!-- Devis Preview Section -->
            <div id="devis-image-container" class="p-4 border-2 border-[#E0EDF8] rounded-lg bg-[#F0F7FF] text-center hidden">
                <h3 class="text-sm font-semibold text-[#1860E1] mb-2">Aperçu du devis</h3>
                <img id="devis-image" src="" alt="Devis signature" style="max-width: 200px; max-height: 150px; margin: 0 auto; object-fit: cover;">
            </div>

            <!-- Client, Date and Status Section -->
            <div class="grid grid-cols-3 gap-4 border-b border-[#E5E7EB] pb-6">
                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Devis *</label>
                    <select name="devis_id" id="devis_id" onchange="loadDevisArticles()" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Sélectionner un devis</option>
                        @if($bonLivraison->devis)
                            <option value="{{ $bonLivraison->devis->id }}" selected>
                                {{ $bonLivraison->devis->numero }}
                            </option>
                        @endif
                    </select>
                    @error('devis_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Client *</label>
                    <select name="client_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ $bonLivraison->client_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nom_raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Date *</label>
                    <input type="date" name="date" value="{{ $bonLivraison->date->format('Y-m-d') }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Statut Section -->
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Statut *</label>
                <select name="statut" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="en_attente" {{ $bonLivraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="livre" {{ $bonLivraison->statut === 'livre' ? 'selected' : '' }}>Livré</option>
                    <option value="validé" {{ $bonLivraison->statut === 'validé' ? 'selected' : '' }}>Validé</option>
                    <option value="annule" {{ ($bonLivraison->statut === 'annule' || $bonLivraison->statut === 'annulé') ? 'selected' : '' }}>Annulé</option>
                </select>
                @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Articles Section -->
            @php
                $devisQuantites = $bonLivraison->devis
                    ? $bonLivraison->devis->lignes->pluck('quantite', 'designation')->toArray()
                    : [];
            @endphp

            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-[#1F2937]">Articles à livrer</h2>
                
                <div id="lignes-container" class="space-y-3">
                    @forelse($bonLivraison->lignes as $index => $ligne)
                        @php
                            $articleInfo = $articleData[$ligne->designation] ?? null;
                            $imagePath = optional($ligne->article)->image ?? ($articleInfo['image'] ?? null);
                            $devisQty = $devisQuantites[$ligne->designation] ?? null;
                        @endphp
                        <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                            <div class="col-span-2">
                                <div class="article-preview">
                                    @if($imagePath)
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $ligne->designation }}" class="w-full">
                                    @else
                                        <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-5">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                <input type="text" name="lignes[{{ $index }}][designation]" value="{{ $ligne->designation }}" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                <input type="number" step="1" name="lignes[{{ $index }}][quantite]" value="{{ $ligne->quantite }}" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                <p class="mt-1 text-xs text-slate-500">Qté devis: {{ $devisQty !== null ? number_format($devisQty, 2, ',', ' ') : '—' }}</p>
                            </div>
                            <div class="col-span-2 flex items-end">
                                <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                            </div>
                        </div>
                    @empty
                        <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                            <div class="col-span-2">
                                <div class="article-preview">
                                    <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
                                </div>
                            </div>
                            <div class="col-span-5">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                <input type="text" name="lignes[0][designation]" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                <input type="number" step="1" name="lignes[0][quantite]" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                <p class="mt-1 text-xs text-slate-500">Qté devis: —</p>
                            </div>
                            <div class="col-span-2 flex items-end">
                                <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" onclick="addLigne()" class="rounded-lg bg-[#E0EDF8] px-4 py-2 text-sm font-semibold text-[#1860E1] hover:bg-[#DBEAFE] transition-colors">
                    + Ajouter une ligne
                </button>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 border-t border-[#E5E7EB] pt-6">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">Mettre à jour</button>
                <a href="{{ route('bon-livraison.show', $bonLivraison) }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB] transition-colors">Annuler</a>
                @if($bonLivraison->statut === 'brouillon')
                <form method="POST" action="{{ route('bon-livraison.validate', $bonLivraison) }}" onsubmit="return confirm('Valider ce bon de livraison ? Le stock sera décrémenté.')">
                    @csrf
                    <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition-colors">Valider & décrémenter le stock</button>
                </form>
                @endif
            </div>
        </form>
    </div>

    @php
        $devisPayload = [];
        if ($bonLivraison->devis) {
            $devisPayload[$bonLivraison->devis->id] = [
                'numero' => $bonLivraison->devis->numero,
                'signature_image' => $bonLivraison->devis->signature_image,
                'lignes' => $bonLivraison->devis->lignes->map(function ($l) use ($articleData) {
                    $articleInfo = $articleData[$l->designation] ?? null;
                    return [
                        'id' => $l->id,
                        'designation' => $l->designation,
                        'quantite' => $l->quantite,
                        'image' => $articleInfo['image'] ?? null,
                        'devis_quantite' => $l->quantite,
                    ];
                })->values(),
            ];
        }
    @endphp

    <script>
        const storageBase = @json(asset('storage'));
        let ligneCount = {{ max($bonLivraison->lignes->count(), 1) }};

        const devisData = @json($devisPayload);

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[char]));
        }

        function formatDevis(value) {
            if (value === null || value === undefined || value === '') {
                return '—';
            }
            const number = Number(value);
            if (Number.isNaN(number)) {
                return value;
            }
            return number.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function buildDevisHint(value) {
            return `<p class="mt-1 text-xs text-slate-500">Qté devis: ${formatDevis(value)}</p>`;
        }

        function loadDevisArticles() {
            const devisId = document.getElementById('devis_id').value;
            const container = document.getElementById('lignes-container');
            const imageContainer = document.getElementById('devis-image-container');
            
            if (!devisId || !devisData[devisId]) {
                imageContainer.classList.add('hidden');
                return;
            }

            const devis = devisData[devisId];
            
            // Show devis image
            if (devis.signature_image) {
                document.getElementById('devis-image').src = `${storageBase}/${devis.signature_image}`;
                imageContainer.classList.remove('hidden');
            } else {
                imageContainer.classList.add('hidden');
            }

            // Clear existing lignes
            container.innerHTML = '';
            ligneCount = 0;

            // Populate articles from devis
            devis.lignes.forEach((ligne, index) => {
                container.appendChild(createLigneElement(index, ligne));
            });
            ligneCount = devis.lignes.length;
        }

        function createLigneElement(index, ligne) {
            const div = document.createElement('div');
            div.className = 'ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]';
            
            const safeDesignation = escapeHtml(ligne.designation ?? '');
            const quantiteValue = ligne.quantite ?? '';
            const imageHtml = ligne.image
                ? `<img src="${storageBase}/${ligne.image}" alt="${safeDesignation}" class="w-full">`
                : '<div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>';
            const devisQty = ligne.devis_quantite ?? ligne.quantite ?? null;
            const devisHint = buildDevisHint(devisQty);
            
            div.innerHTML = `
                <div class="col-span-2">
                    <div class="article-preview">
                        ${imageHtml}
                    </div>
                </div>
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                    <input type="text" name="lignes[${index}][designation]" value="${safeDesignation}" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                    <input type="number" step="1" name="lignes[${index}][quantite]" value="${quantiteValue}" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    ${devisHint}
                </div>
                <div class="col-span-2 flex items-end">
                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                </div>
            `;
            
            return div;
        }

        function getEmptyLigne(index) {
            const div = document.createElement('div');
            div.className = 'ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]';
            
            const devisHint = buildDevisHint(null);

            div.innerHTML = `
                <div class="col-span-2">
                    <div class="article-preview">
                        <div class="w-full h-[60px] bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Pas image</div>
                    </div>
                </div>
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                    <input type="text" name="lignes[${index}][designation]" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                    <input type="number" step="1" name="lignes[${index}][quantite]" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    ${devisHint}
                </div>
                <div class="col-span-2 flex items-end">
                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
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

        // Load articles if devis is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            const devisId = document.getElementById('devis_id').value;
            if (devisId) {
                loadDevisArticles();
            }
        });
    </script>
@endsection
