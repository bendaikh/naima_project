@extends('layouts.dashboard')

@section('title', 'Détail - Avoir')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $avoir->numero }}</h1>
            <p class="text-sm text-slate-600 mt-1">Créé le {{ $avoir->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-2">
            @if($avoir->statut === 'brouillon')
                <form method="POST" action="{{ route('avoir.emit', $avoir->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Émettre</button>
                </form>
            @endif

            @if($avoir->statut === 'emis')
                <form method="POST" action="{{ route('avoir.apply', $avoir->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Appliquer</button>
                </form>
            @endif

            @if($avoir->statut === 'brouillon')
                <form method="POST" action="{{ route('avoir.destroy', $avoir->id) }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Client</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ $avoir->client->name }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Facture</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">
                <a href="{{ route('factures.show', $avoir->facture->id) }}" class="text-blue-600 hover:underline">
                    {{ $avoir->facture->numero }}
                </a>
            </p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Statut</p>
            <p class="text-lg font-semibold mt-2">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                    @if($avoir->statut === 'brouillon') bg-slate-100 text-slate-800
                    @elseif($avoir->statut === 'emis') bg-blue-100 text-blue-800
                    @elseif($avoir->statut === 'applique') bg-green-100 text-green-800
                    @endif">
                    {{ $avoir->statut }}
                </span>
            </p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Bon de Retour</p>
            @if($avoir->bonRetour)
                <p class="text-lg font-semibold text-slate-800 mt-2">
                    <a href="{{ route('bon-retour.show', $avoir->bonRetour->id) }}" class="text-blue-600 hover:underline">
                        {{ $avoir->bonRetour->numero }}
                    </a>
                </p>
            @else
                <p class="text-lg font-semibold text-slate-800 mt-2">-</p>
            @endif
        </div>
    </div>

    @if($avoir->description)
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Description</p>
            <p class="text-slate-800 mt-2">{{ $avoir->description }}</p>
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow">
        <div class="border-b border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800">Articles retournés</h3>
        </div>
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Désignation</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Quantité</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Prix Unitaire</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Montant HT</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($avoir->lignes as $ligne)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $ligne->designation }}</td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">{{ $ligne->quantite }}</td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">{{ number_format($ligne->montant_ht, 2, ',', ' ') }} DH</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">Aucun article</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-slate-600">Montant HT</span>
                <span class="font-semibold text-slate-900">{{ number_format($avoir->montant_ht, 2, ',', ' ') }} DH</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">TVA</span>
                <span class="font-semibold text-slate-900">{{ number_format($avoir->tva, 2, ',', ' ') }} DH</span>
            </div>
            <div class="flex justify-between border-t border-slate-200 pt-3 text-lg">
                <span class="font-semibold text-slate-900">Montant TTC</span>
                <span class="font-bold text-blue-600">{{ number_format($avoir->montant_ttc, 2, ',', ' ') }} DH</span>
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('avoir.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</a>
    </div>
</div>
@endsection
