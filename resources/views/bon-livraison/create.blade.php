@extends('layouts.dashboard')

@section('title', 'Nouveau bon de livraison')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
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
                    <select name="devis_id" id="devis_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Sélectionner un devis</option>
                        @foreach($devisList as $devis)
                            <option value="{{ $devis->id }}" {{ old('devis_id') == $devis->id ? 'selected' : '' }}>
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
    <script>
        function reloadForClient() {
            const clientId = document.getElementById('client_id').value;
            const url = new URL(window.location.href);
            url.searchParams.set('client_id', clientId);
            window.location.href = url.toString();
        }
    </script>

            <!-- Articles Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-[#1F2937]">Articles à livrer</h2>
                <div id="lignes-container" class="space-y-3">
                    @php $ligneIndex = 0; @endphp
                    @if(old('lignes'))
                        @foreach(old('lignes') as $i => $ligne)
                            <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                                <div>
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                    <input type="text" name="lignes[{{ $i }}][designation]" value="{{ $ligne['designation'] }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                    <input type="number" step="1" name="lignes[{{ $i }}][quantite]" value="{{ $ligne['quantite'] }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                                </div>
                            </div>
                            @php $ligneIndex = $i + 1; @endphp
                        @endforeach
                    @elseif($devisList && $devisList->count() === 1)
                        @php $devis = $devisList->first(); @endphp
                        @foreach($devis->lignes as $ligne)
                            <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-[#E5E7EB] rounded-lg bg-[#F9FAFB]">
                                <div>
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Désignation *</label>
                                    <input type="text" name="lignes[{{ $ligneIndex }}][designation]" value="{{ $ligne->designation }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#374151] mb-2">Quantité *</label>
                                    <input type="number" step="1" name="lignes[{{ $ligneIndex }}][quantite]" value="{{ $ligne->quantite }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Supprimer</button>
                                </div>
                            </div>
                            @php $ligneIndex++; @endphp
                        @endforeach
                    @else
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
    </script>
@endsection
