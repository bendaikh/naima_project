@extends('layouts.dashboard')

@section('title', 'Créer un bon de retour')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Créer un bon de retour</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('bon-retour.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bon de Livraison *</label>
                <select name="bon_livraison_id" id="bon_livraison_id" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option value="">Sélectionner un bon</option>
                    @foreach($bonsLivraison as $bon)
                        <option value="{{ $bon->id }}">{{ $bon->numero }} - {{ $bon->client->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Date *</label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Motif du retour</label>
            <textarea name="motif" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 h-20"></textarea>
        </div>

        <!-- Articles returned section -->
        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Articles retournés</h3>
            <div id="lignes-container" class="space-y-4">
                <div class="ligne-item grid grid-cols-3 gap-4 p-4 border border-slate-200 rounded-lg">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Désignation *</label>
                        <input type="text" name="lignes[0][designation]" placeholder="Produit" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Quantité *</label>
                        <input type="number" step="0.01" name="lignes[0][quantite]" placeholder="0" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeLigne(this)" class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addLigne()" class="mt-4 rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-300">
                + Ajouter une ligne
            </button>
        </div>

        <div class="flex gap-4 border-t border-slate-200 pt-6">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Créer</button>
            <a href="{{ route('bon-retour.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
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
