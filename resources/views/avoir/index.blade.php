@extends('layouts.dashboard')

@section('title', 'Avoirs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Avoirs (Crédits Clients)</h1>
            <p class="text-sm text-slate-600 mt-1">Avoirs créés automatiquement lors de retours de marchandises</p>
        </div>
        <a href="{{ route('factures.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">← Retour</a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    @if($avoirs->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white p-8 text-center shadow">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <p class="mt-4 text-slate-600">Aucun avoir enregistré.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Numéro</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Facture</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Bon de Retour</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Montant TTC</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Statut</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($avoirs as $avoir)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $avoir->numero }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $avoir->client->nom_raison_sociale ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <a href="{{ route('factures.show', $avoir->facture->id) }}" class="text-blue-600 hover:underline">
                                    {{ $avoir->facture->numero ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($avoir->bonRetour)
                                    <a href="{{ route('bon-retour.show', $avoir->bonRetour->id) }}" class="text-blue-600 hover:underline">
                                        {{ $avoir->bonRetour->numero ?? 'N/A' }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">{{ number_format($avoir->montant_ttc, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    @if($avoir->statut === 'brouillon') bg-slate-100 text-slate-800
                                    @elseif($avoir->statut === 'emis') bg-blue-100 text-blue-800
                                    @elseif($avoir->statut === 'applique') bg-green-100 text-green-800
                                    @endif">
                                    {{ $avoir->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('avoir.show', $avoir->id) }}" class="text-blue-600 hover:text-blue-700 mr-4">Voir</a>
                                @if($avoir->statut === 'brouillon')
                                    <form method="POST" action="{{ route('avoir.destroy', $avoir->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $avoirs->links() }}
        </div>
    @endif
</div>
@endsection
