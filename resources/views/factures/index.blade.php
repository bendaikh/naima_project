@extends('layouts.dashboard')

@section('title', 'Facturation')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Facturation</h1>
            <a href="{{ route('factures.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">+ Nouvelle facture</a>
        </div>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
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
                    @forelse($factures as $f)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $f->numero }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $f->client->nom_raison_sociale ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $f->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                    @if($f->statut === 'payee') bg-[#E8F5E9] text-[#4CAF50]
                                    @elseif($f->statut === 'partiellement_payee') bg-[#FFF3E0] text-[#FF9800]
                                    @else bg-[#FFEBEE] text-[#DC2626]
                                    @endif">{{ $f->statut }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-[#1F2937]">{{ number_format($f->total_ttc, 2, ',', ' ') }} MAD</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('factures.show', $f) }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] w-9 h-9 text-white hover:bg-[#1557C7] transition-colors" title="Voir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('factures.edit', $f) }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] w-9 h-9 text-white hover:bg-[#1557C7] transition-colors" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('factures.destroy', $f) }}" onsubmit="return confirm('Confirmer la suppression ?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 w-9 h-9 text-white hover:bg-red-700 transition-colors" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucune facture.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $factures->links() }}
    </div>
@endsection
