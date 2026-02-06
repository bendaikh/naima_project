@extends('layouts.dashboard')

@section('title', 'Détail - Bon de Retour')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $bonRetour->numero }}</h1>
            <p class="text-sm text-slate-600 mt-1">Créé le {{ $bonRetour->date->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bon-retour.edit', $bonRetour->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Modifier</a>
            <form method="POST" action="{{ route('bon-retour.destroy', $bonRetour->id) }}" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Client</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ $bonRetour->client->name }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Bon de Livraison</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">
                <a href="{{ route('bon-livraison.show', $bonRetour->bonLivraison->id) }}" class="text-blue-600 hover:underline">
                    {{ $bonRetour->bonLivraison->numero }}
                </a>
            </p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Facture</p>
            @if($bonRetour->facture)
                <p class="text-lg font-semibold text-slate-800 mt-2">
                    <a href="{{ route('factures.show', $bonRetour->facture->id) }}" class="text-blue-600 hover:underline">
                        {{ $bonRetour->facture->numero }}
                    </a>
                </p>
            @else
                <p class="text-lg font-semibold text-slate-800 mt-2">Non liée</p>
            @endif
        </div>
    </div>

    @if($bonRetour->motif)
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Motif du retour</p>
            <p class="text-slate-800 mt-2">{{ $bonRetour->motif }}</p>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($bonRetour->lignes as $ligne)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $ligne->designation }}</td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">{{ $ligne->quantite }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-slate-500">Aucun article</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bonRetour->facture && $bonRetour->facture->isPaid())
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-6">
            <p class="text-sm font-medium text-amber-700">📋 Information</p>
            <p class="text-slate-700 mt-2">Une facture d'avoir (Avoir) a été générée automatiquement pour ce retour car la facture liée est déjà payée.</p>
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('bon-retour.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</a>
    </div>
</div>
@endsection
