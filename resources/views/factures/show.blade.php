@extends('layouts.dashboard')

@section('title', 'Facture ' . $facture->numero)

@section('content')
    <!-- SCREEN VERSION -->
    <div class="max-w-6xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#1F2937]">Facture {{ $facture->numero }}</h1>
                <p class="mt-1 text-sm text-[#6B7280]">Créée le {{ $facture->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex gap-2 items-center">
                <a href="{{ route('factures.print', $facture) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </a>
                <a href="{{ route('factures.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] px-6 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
                @if($facture->canBeEdited())
                    <a href="{{ route('factures.edit', $facture) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                @endif
                @if($facture->statut !== 'payee')
                <form method="POST" action="{{ route('factures.mark-as-paid', $facture) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marquer comme payée
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('factures.destroy', $facture) }}" onsubmit="return confirm('Confirmer la suppression de cette facture ?')" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Info Cards -->
        <div class="grid grid-cols-4 gap-4">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Client</p>
                <p class="mt-2 text-sm font-semibold text-[#1F2937]">{{ $facture->client->nom_raison_sociale ?? '—' }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Date</p>
                <p class="mt-2 text-sm font-semibold text-[#1F2937]">{{ $facture->date->format('d/m/Y') }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Statut</p>
                <p class="mt-2 text-sm font-semibold">
                    @if($facture->statut === 'payee')
                        <span class="px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-xs font-medium">Payée</span>
                    @elseif($facture->statut === 'partiellement_payee')
                        <span class="px-3 py-1 rounded-full bg-[#DBEAFE] text-[#1E40AF] text-xs font-medium">Partiellement payée</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-xs font-medium">Non payée</span>
                    @endif
                </p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Échéance</p>
                <p class="mt-2 text-sm font-semibold text-[#1F2937]">{{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : '—' }}</p>
            </div>
        </div>

        <!-- Articles Section -->
        @if($facture->lignes && $facture->lignes->count() > 0)
            <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-[#1F2937] mb-4">Articles</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-[#374151]">Désignation</th>
                                <th class="px-4 py-3 text-left font-medium text-[#374151]">Catégorie</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Quantité</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Prix unitaire</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facture->lignes as $ligne)
                                <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB]">
                                    <td class="px-4 py-3 text-[#374151]">{{ $ligne->designation }}</td>
                                    <td class="px-4 py-3 text-[#374151]">
                                        @if($ligne->categorie)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#E0EDF8] text-[#1860E1]">
                                                {{ $ligne->categorie }}
                                            </span>
                                        @else
                                            <span class="text-xs text-[#9CA3AF]">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->quantite, 2) }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} MAD</td>
                                    <td class="px-4 py-3 text-right font-semibold text-[#1F2937]">{{ number_format($ligne->total_ht, 2, ',', ' ') }} MAD</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Totals and Payment Section -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Totals -->
            <div class="bg-gradient-to-br from-[#F3F4F6] to-[#E5E7EB] rounded-lg p-6 border border-[#E5E7EB]">
                <h3 class="text-sm font-semibold text-[#1F2937] mb-4">Détail des montants</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#6B7280]">Total HT</span>
                        <span class="font-semibold text-[#1F2937]">{{ number_format($facture->total_ht, 2, ',', ' ') }} MAD</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#6B7280]">TVA ({{ $facture->tva ?? 20 }}%)</span>
                        <span class="font-semibold text-[#1F2937]">{{ number_format($facture->total_ttc - $facture->total_ht, 2, ',', ' ') }} MAD</span>
                    </div>
                    <div class="border-t border-[#E5E7EB] pt-3 flex justify-between items-center">
                        <span class="font-semibold text-[#1F2937]">Total TTC</span>
                        <span class="text-lg font-bold text-[#1860E1]">{{ number_format($facture->total_ttc, 2, ',', ' ') }} MAD</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="bg-white rounded-lg p-6 border border-[#E5E7EB] shadow-sm">
                <h3 class="text-sm font-semibold text-[#1F2937] mb-4">État de paiement</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide mb-2">Montant total</p>
                        <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($facture->total_ttc, 2, ',', ' ') }} MAD</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide mb-2">Montant payé</p>
                        <p class="text-2xl font-bold text-[#1860E1]">{{ number_format($facture->montant_paye, 2, ',', ' ') }} MAD</p>
                    </div>
                    <div class="bg-[#F9FAFB] rounded p-3">
                        <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide mb-2">Reste à payer</p>
                        <p class="text-lg font-bold {{ $facture->total_ttc - $facture->montant_paye > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format(max(0, $facture->total_ttc - $facture->montant_paye), 2, ',', ' ') }} MAD
                        </p>
                    </div>
                    <div>
                        <div class="w-full bg-[#E5E7EB] rounded-full h-2">
                            <div class="bg-[#1860E1] h-2 rounded-full" style="width: {{ $facture->total_ttc > 0 ? min(100, ($facture->montant_paye / $facture->total_ttc) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-[#6B7280] mt-2">{{ $facture->total_ttc > 0 ? round(min(100, ($facture->montant_paye / $facture->total_ttc) * 100)) : 0 }}% payé</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
