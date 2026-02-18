@extends('layouts.dashboard')

@section('title', 'Nouveau bon de livraison')

@section('content')
    <style>
        .article-preview img {
            max-width: 100px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>

    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-3xl font-bold text-[#1F2937]">Nouveau bon de livraison</h1>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-red-700 border-l-4 border-red-400">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bon-livraison.store') }}" class="space-y-6 rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf

            <!-- Client, Devis, and Date Section -->
            <div class="grid grid-cols-3 gap-4 border-b border-[#E5E7EB] pb-6">
                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Client *</label>
                    <select name="client_id" id="client_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]" onchange="reloadForClient()">
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ (old('client_id', $selectedClientId ?? null) == $c->id) ? 'selected' : '' }}>
                                {{ $c->nom_raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Commande (Devis accepté) *</label>
                    <select name="devis_id" id="devis_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]" onchange="loadDevisArticles()">
                        <option value="">Sélectionner un devis</option>
                        @foreach($devisList as $devis)
                            <option value="{{ $devis->id }}" data-devis="{{ json_encode($devis) }}" {{ old('devis_id') == $devis->id ? 'selected' : '' }}>
                                {{ $devis->numero }} ({{ $devis->date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('devis_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Devis Image Preview -->
            <div id="devis-image-container" class="hidden">
                <h3 class="text-sm font-semibold text-[#1F2937] mb-3">Devis Signature</h3>
                <div id="devis-image-preview" class="border border-[#E5E7EB] rounded-lg p-4 bg-[#F9FAFB]">
                </div>
            </div>

    <script>
        function reloadForClient() {
            const clientId = document.getElementById('client_id').value;
            const url = new URL(window.location.href);
            url.searchParams.set('client_id', clientId);
            window.location.href = url.toString();
        }

        // Store devis data
        const devisData = {!! json_encode($devisList->keyBy('id')->map(function($d) use ($articleImagesByName) { 
            return [
                'numero' => $d->numero,
                'signature_image' => $d->signature_image,
                'lignes' => $d->lignes->map(function($l) use ($articleImagesByName) {
                    return [
                        'id' => $l->id,
                        'designation' => $l->designation,
                        'quantite' => $l->quantite,
                        'article_id' => $l->article_id,
                        'image' => $articleImagesByName[$l->designation] ?? null
                    ];
                })
            ];
        })) !!};

        function loadDevisArticles() {
            const devisId = document.getElementById('devis_id').value;
            const container = document.getElementById('lignes-container');
            const imageContainer = document.getElementById('devis-image-container');
            const imagePreview = document.getElementById('devis-image-preview');

            if (!devisId || !devisData[devisId]) {
                container.innerHTML = getEmptyLigne(0);
                imageContainer.classList.add('hidden');
                return;
            }

            const devis = devisData[devisId];
            
            // Show devis image
            if (devis.signature_image) {
                imagePreview.innerHTML = `<img src="/storage/${devis.signature_image}" alt="Signature" style="max-width: 200px; max-height: 150px; border: 1px solid #E5E7EB; border-radius: 4px;">`;
                imageContainer.classList.remove('hidden');
            } else {
                imageContainer.classList.add('hidden');
            }

            // Load articles
            if (devis.lignes && devis.lignes.length > 0) {
                container.innerHTML = '';
                devis.lignes.forEach((ligne, index) => {
                    container.appendChild(createLigneElement(index, ligne));
                });
            } else {
                container.innerHTML = getEmptyLigne(0);
            }
        }

        function createLigneElement(index, ligne = null) {
            const div = document.createElement('div');
            div.className = 'ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]';
            
            const imageHtml = ligne && ligne.image 
                ? `<img src="/storage/${ligne.image}" alt="${ligne.designation}" style="max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 4px;">`
                : '<div class="bg-gray-200 w-16 h-16 rounded flex items-center justify-center text-gray-400 text-xs">Pas image</div>';
            
            div.innerHTML = `
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Image</label>
                    <div class="article-preview flex items-center justify-center border border-[#E5E7EB] rounded-lg p-2 bg-white">
                        ${imageHtml}
                    </div>
                </div>
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                    <input type="text" name="lignes[${index}][designation]" value="${ligne?.designation || ''}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                    <input type="number" step="1" name="lignes[${index}][quantite]" value="${ligne?.quantite || ''}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
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
            div.innerHTML = `
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Image</label>
                    <div class="bg-gray-200 w-full h-16 rounded flex items-center justify-center text-gray-400 text-xs">Pas image</div>
                </div>
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                    <input type="text" name="lignes[${index}][designation]" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                    <input type="number" step="1" name="lignes[${index}][quantite]" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div class="col-span-2 flex items-end">
                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                </div>
            `;
            return div;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const devisId = document.getElementById('devis_id').value;
            if (devisId) {
                loadDevisArticles();
            }
        });
    </script>

            <!-- Articles Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-[#1F2937]">Articles à livrer</h2>
                <div id="lignes-container" class="space-y-3">
                    @php $ligneIndex = 0; @endphp
                    @if(old('lignes'))
                        @foreach(old('lignes') as $i => $ligne)
                            <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Image</label>
                                    <div class="bg-gray-200 w-full h-16 rounded flex items-center justify-center text-gray-400 text-xs">Pas image</div>
                                </div>
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                    <input type="text" name="lignes[{{ $i }}][designation]" value="{{ $ligne['designation'] }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                    <input type="number" step="1" name="lignes[{{ $i }}][quantite]" value="{{ $ligne['quantite'] }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="col-span-2 flex items-end">
                                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                                </div>
                            </div>
                            @php $ligneIndex = $i + 1; @endphp
                        @endforeach
                    @elseif($devisList && $devisList->count() === 1)
                        @php $devis = $devisList->first(); @endphp
                        @foreach($devis->lignes as $ligne)
                        @php 
                                $imagePath = $articleImagesByName[$ligne->designation] ?? null;
                                $imageHtml = $imagePath
                                    ? "<img src='/storage/{$imagePath}' alt='{$ligne->designation}' style='max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 4px;'>"
                                    : '<div class="bg-gray-200 w-16 h-16 rounded flex items-center justify-center text-gray-400 text-xs">Pas image</div>';
                            @endphp
                            <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Image</label>
                                    <div class="article-preview flex items-center justify-center border border-[#E5E7EB] rounded-lg p-2 bg-white">
                                        {!! $imageHtml !!}
                                    </div>
                                </div>
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                    <input type="text" name="lignes[{{ $ligneIndex }}][designation]" value="{{ $ligne->designation }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                    <input type="number" step="1" name="lignes[{{ $ligneIndex }}][quantite]" value="{{ $ligne->quantite }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="col-span-2 flex items-end">
                                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                                </div>
                            </div>
                            @php $ligneIndex++; @endphp
                        @endforeach
                    @else
                        <div class="ligne-item grid grid-cols-12 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Image</label>
                                <div class="bg-gray-200 w-full h-16 rounded flex items-center justify-center text-gray-400 text-xs">Pas image</div>
                            </div>
                            <div class="col-span-5">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                <input type="text" name="lignes[0][designation]" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                <input type="number" step="1" name="lignes[0][quantite]" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="col-span-2 flex items-end">
                                <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                            </div>
                        </div>
                        @php $ligneIndex = 1; @endphp
                    @endif
                </div>
                <button type="button" onclick="addLigne()" class="rounded-lg bg-[#E0EDF8] px-4 py-2 text-sm font-semibold text-[#1860E1] hover:bg-[#DBEAFE] transition-colors">
                    + Ajouter une ligne
                </button>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 border-t border-[#E5E7EB] pt-6">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">Créer le bon</button>
                <a href="{{ route('bon-livraison.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB] transition-colors">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        let ligneCount = {{ $ligneIndex ?? 1 }};

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
    </script>
@endsection
