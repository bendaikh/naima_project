@extends('layouts.dashboard')

@section('title', 'Modifier bon de livraison ' . $bonLivraison->numero)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
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

            <!-- Client, Date and Status Section -->
            <div class="grid grid-cols-3 gap-4 border-b border-[#E5E7EB] pb-6">
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

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Statut *</label>
                    <select name="statut" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="en_attente" {{ $bonLivraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="livre" {{ $bonLivraison->statut === 'livre' ? 'selected' : '' }}>Livré</option>
                        <option value="annule" {{ $bonLivraison->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Articles Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-[#1F2937]">Articles à livrer</h2>
                
                <div id="lignes-container" class="space-y-3">
                    @forelse($bonLivraison->lignes as $index => $ligne)
                        <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                <input type="text" name="lignes[{{ $index }}][designation]" value="{{ $ligne->designation }}" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                <input type="number" step="1" name="lignes[{{ $index }}][quantite]" value="{{ $ligne->quantite }}" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                            </div>
                        </div>
                    @empty
                        <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                <input type="text" name="lignes[0][designation]" placeholder="Nom du produit" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                <input type="number" step="1" name="lignes[0][quantite]" placeholder="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                            </div>
                        </div>
                    @endempty
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

    <script>
        let ligneCount = {{ $bonLivraison->lignes->count() }};

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
    </script>
@endsection
