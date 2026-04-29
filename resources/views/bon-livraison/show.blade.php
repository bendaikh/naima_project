@extends('layouts.dashboard')

@section('title', 'Bon de livraison ' . $bonLivraison->numero)

@section('content')
    <div class="max-w-6xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#1F2937]">Bon de livraison {{ $bonLivraison->numero }}</h1>
                <p class="mt-1 text-sm text-[#6B7280]">Créé le {{ $bonLivraison->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('bon-livraison.print', $bonLivraison) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </a>
                <a href="{{ route('bon-livraison.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-semibold text-[#6B7280] hover:bg-[#F9FAFB] transition-colors">← Retour</a>
                <a href="{{ route('bon-livraison.edit', $bonLivraison) }}" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">Modifier</a>
                @if($bonLivraison->statut !== 'validé')
                <form method="POST" action="{{ route('bon-livraison.validate', $bonLivraison) }}" onsubmit="return confirm('Valider ce bon de livraison ? Le stock sera décrémenté.')">
                    @csrf
                    <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition-colors">Valider & décrémenter le stock</button>
                </form>
                @endif
                <a href="{{ route('bon-retour.create', ['bonLivraison' => $bonLivraison->id]) }}" class="rounded-lg bg-yellow-500 px-6 py-2 text-sm font-semibold text-white hover:bg-yellow-600 transition-colors">Créer un retour</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-[#FFEBEE] p-4 text-sm text-[#C62828] border-l-4 border-[#F44336]">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Info Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Client</p>
                <p class="mt-2 text-lg font-semibold text-[#1F2937]">{{ $bonLivraison->client->nom_raison_sociale ?? '—' }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Date</p>
                <p class="mt-2 text-lg font-semibold text-[#1F2937]">{{ $bonLivraison->date->format('d/m/Y') }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Statut</p>
                <p class="mt-2 text-lg font-semibold">
                    @if($bonLivraison->statut === 'brouillon')
                        <span class="px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-sm font-medium">Brouillon</span>
                    @elseif($bonLivraison->statut === 'validé')
                        <span class="px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-sm font-medium">Validé</span>
                    @elseif($bonLivraison->statut === 'annulé')
                        <span class="px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-sm font-medium">Annulé</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-[#E0E7FF] text-[#3730A3] text-sm font-medium">{{ ucfirst($bonLivraison->statut) }}</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Articles Section -->
        @if($bonLivraison->lignes && $bonLivraison->lignes->count() > 0)
            <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-[#1F2937] mb-4">Articles livrés</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-[#374151]">Référence</th>
                                <th class="px-4 py-3 text-left font-medium text-[#374151]">Désignation</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bonLivraison->lignes as $ligne)
                                <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB]">
                                    <td class="px-4 py-3 text-[#6B7280]">{{ $ligne->reference ?? '—' }}</td>
                                    <td class="px-4 py-3 text-[#374151]">{{ $ligne->designation }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->quantite, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Summary Section -->
        <div class="rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-6">
            <h2 class="text-lg font-semibold text-[#1F2937] mb-4">Résumé</h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-[#6B7280] mb-1">Nombre d'articles</p>
                    <p class="text-2xl font-bold text-[#1F2937]">{{ $bonLivraison->lignes->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#6B7280] mb-1">Total quantité</p>
                    <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($bonLivraison->lignes->sum('quantite'), 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#6B7280] mb-1">Retours liés</p>
                    <p class="text-2xl font-bold text-[#1F2937]">{{ $bonLivraison->bonsRetour()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
