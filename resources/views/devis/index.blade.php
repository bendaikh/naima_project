@extends('layouts.dashboard')

@section('title', 'Devis')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-[#1F2937]">Devis</h1>
            <a href="{{ route('devis.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau devis
            </a>
        </div>
        
        @if(session('success'))
            <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">{{ session('success') }}</div>
        @endif

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
            <form method="get" class="flex gap-4">
                <input type="search" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher un devis..." class="flex-1 rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-medium text-white hover:bg-[#1557C7] transition-colors">Rechercher</button>
            </form>
        </div>
        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-[#E5E7EB]">
                <thead class="bg-[#F9FAFB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-[#6B7280]">Total TTC</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-[#6B7280]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] bg-white">
                    @forelse($devis as $d)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $d->numero }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $d->client->nom_raison_sociale ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $d->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4"><span class="rounded-full px-2 py-0.5 text-xs font-medium
                                @if($d->statut === 'accepte') bg-[#E8F5E9] text-[#4CAF50]
                                @elseif($d->statut === 'refuse') bg-[#FFEBEE] text-[#DC2626]
                                @elseif($d->statut === 'envoye') bg-[#E3F2FD] text-[#1860E1]
                                @else bg-[#F5F5F5] text-[#6B7280]
                                @endif">{{ $d->statut }}</span></td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-[#1F2937]">{{ number_format($d->total_ttc, 2, ',', ' ') }} MAD</td>
                            <td class="px-6 py-4 text-center text-sm">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('devis.show', $d) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 w-8 h-8 text-white hover:bg-blue-600 transition-colors" title="Voir">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    </a>
                                    <a href="{{ route('devis.edit', $d) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-500 w-8 h-8 text-white hover:bg-amber-600 transition-colors" title="Modifier">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('devis.destroy', $d) }}" onsubmit="return confirm('Confirmer la suppression ?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-500 w-8 h-8 text-white hover:bg-red-600 transition-colors" title="Supprimer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucun devis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $devis->links() }}
        </div>
    </div>
@endsection
