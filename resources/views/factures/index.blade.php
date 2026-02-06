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
                            <td class="px-6 py-4 text-right text-sm font-medium text-[#1F2937]">{{ number_format($f->total_ttc, 2, ',', ' ') }} €</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('factures.show', $f) }}" class="text-[#1860E1] hover:underline">Voir</a>
                                <a href="{{ route('factures.edit', $f) }}" class="ml-3 text-[#1860E1] hover:underline">Modifier</a>
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
