@extends('layouts.dashboard')

@section('title', 'Bons de Retour')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-800">Bons de Retour</h1>
        <a href="{{ route('bon-retour.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Créer un bon de retour
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    @if($bonsRetour->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white p-8 text-center shadow">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4 0a1 1 0 01-1 1m0-5a2 2 0 012-2h2a2 2 0 012 2m-6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="mt-4 text-slate-600">Aucun bon de retour enregistré.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Numéro</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Bon de Livraison</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Motif</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($bonsRetour as $bon)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $bon->numero }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $bon->client->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $bon->bonLivraison->numero ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $bon->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($bon->motif, 30) ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('bon-retour.show', $bon->id) }}" class="text-blue-600 hover:text-blue-700 mr-4">Voir</a>
                                <a href="{{ route('bon-retour.edit', $bon->id) }}" class="text-amber-600 hover:text-amber-700 mr-4">Modifier</a>
                                <form method="POST" action="{{ route('bon-retour.destroy', $bon->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bonsRetour->links() }}
        </div>
    @endif
</div>
@endsection
