@extends('layouts.dashboard')

@section('title', 'Avoirs')

@section('content')
<div class="w-full space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Avoirs (Crédits Clients)</h1>
        <p class="text-sm text-[#6B7280]">Avoirs créés automatiquement lors de retours de marchandises</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">{{ session('success') }}</div>
    @endif

    @if($avoirs->isEmpty())
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-8 text-center shadow">
            <svg class="mx-auto h-12 w-12 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <p class="mt-4 text-[#6B7280]">Aucun avoir enregistré.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="w-full">
                <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Numéro</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Facture</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Bon de Retour</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-[#374151]">Montant TTC</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Statut</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-[#374151]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($avoirs as $avoir)
                        <tr class="hover:bg-[#F9FAFB]">
                            <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $avoir->numero }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $avoir->client->nom_raison_sociale ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">
                                @if($avoir->facture)
                                    <a href="{{ route('factures.show', $avoir->facture->id) }}" class="text-[#1860E1] hover:text-[#1557C7] hover:underline">
                                        {{ $avoir->facture->numero }}
                                    </a>
                                @else
                                    <span class="text-[#9CA3AF]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">
                                @if($avoir->bonRetour)
                                    <a href="{{ route('bon-retour.show', $avoir->bonRetour->id) }}" class="text-[#1860E1] hover:text-[#1557C7] hover:underline">
                                        {{ $avoir->bonRetour->numero ?? 'N/A' }}
                                    </a>
                                @else
                                    <span class="text-[#9CA3AF]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-[#1F2937]">{{ number_format($avoir->montant_ttc, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    @if($avoir->statut === 'brouillon') bg-[#F3F4F6] text-[#374151]
                                    @elseif($avoir->statut === 'emis') bg-[#DBEAFE] text-[#1E40AF]
                                    @elseif($avoir->statut === 'applique') bg-[#DCFCE7] text-[#166534]
                                    @endif">
                                    {{ ucfirst($avoir->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('avoir.show', $avoir->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 w-8 h-8 text-white hover:bg-blue-600 transition-colors" title="Voir">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    </a>
                                    @if($avoir->statut === 'brouillon')
                                        <form method="POST" action="{{ route('avoir.destroy', $avoir->id) }}" onsubmit="return confirm('Confirmer la suppression ?')" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-500 w-8 h-8 text-white hover:bg-red-600 transition-colors" title="Supprimer">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
