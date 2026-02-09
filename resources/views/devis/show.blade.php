@extends('layouts.dashboard')

@section('title', 'Devis ' . $devis->numero)

@section('content')
    <div class="max-w-6xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#1F2937]">Devis {{ $devis->numero }}</h1>
                <p class="mt-1 text-sm text-[#6B7280]">Créé le {{ $devis->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex gap-2 items-center">
                <a href="{{ route('devis.index') }}" class="rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F3F4F6] transition-colors">← Retour</a>
                @if($devis->canBeEdited())
                    <a href="{{ route('devis.edit', $devis) }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] w-10 h-10 text-white hover:bg-[#1557C7] transition-colors" title="Modifier">✏️</a>
                @endif
                <form method="POST" action="{{ route('devis.destroy', $devis) }}" onsubmit="return confirm('Confirmer la suppression de ce devis ?')" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 w-10 h-10 text-white hover:bg-red-700 transition-colors" title="Supprimer">🗑️</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Info Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Client</p>
                <p class="mt-2 text-lg font-semibold text-[#1F2937]">{{ $devis->client->nom_raison_sociale ?? '—' }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Date</p>
                <p class="mt-2 text-lg font-semibold text-[#1F2937]">{{ $devis->date->format('d/m/Y') }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Statut</p>
                <p class="mt-2 text-lg font-semibold">
                    @if($devis->statut === 'brouillon')
                        <span class="px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-sm font-medium">Brouillon</span>
                    @elseif($devis->statut === 'envoye')
                        <span class="px-3 py-1 rounded-full bg-[#DBEAFE] text-[#1E40AF] text-sm font-medium">Envoyé</span>
                    @elseif($devis->statut === 'accepte')
                        <span class="px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-sm font-medium">Accepté</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-sm font-medium">Refusé</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Articles Section -->
        @if($devis->lignes && $devis->lignes->count() > 0)
            <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-[#1F2937] mb-4">Articles</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-[#374151]">Désignation</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Quantité</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Prix unitaire</th>
                                <th class="px-4 py-3 text-right font-medium text-[#374151]">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis->lignes as $ligne)
                                <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB]">
                                    <td class="px-4 py-3 text-[#374151]">{{ $ligne->designation }}</td>
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

        <!-- Totals Section -->
        <div class="bg-gradient-to-br from-[#F3F4F6] to-[#E5E7EB] rounded-lg p-6 border border-[#E5E7EB]">
            <div class="grid grid-cols-3 gap-4">
                <!-- Total HT Card -->
                <div class="bg-white rounded-lg p-4 border-l-4 border-[#1860E1] shadow-sm">
                    <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">Total HT</p>
                    <p class="mt-2 text-2xl font-bold text-[#1F2937]">{{ number_format($devis->total_ht, 2, ',', ' ') }}</p>
                    <p class="text-xs text-[#9CA3AF] mt-1">MAD</p>
                </div>

                <!-- TVA Card -->
                <div class="bg-white rounded-lg p-4 border-l-4 border-[#F97316] shadow-sm">
                    <p class="text-xs font-medium text-[#6B7280] uppercase tracking-wide">TVA ({{ $devis->tva }}%)</p>
                    <p class="mt-2 text-2xl font-bold text-[#1F2937]">{{ number_format($devis->total_ttc - $devis->total_ht, 2, ',', ' ') }}</p>
                    <p class="text-xs text-[#9CA3AF] mt-1">MAD</p>
                </div>

                <!-- Total TTC Card -->
                <div class="bg-gradient-to-br from-[#1860E1] to-[#1557C7] rounded-lg p-4 border-l-4 border-[#0F3D8F] shadow-md">
                    <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Total TTC</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ number_format($devis->total_ttc, 2, ',', ' ') }}</p>
                    <p class="text-xs text-blue-200 mt-1">MAD</p>
                </div>
            </div>
        </div>

        <!-- Status and Action Buttons -->
        <div class="bg-white rounded-lg border border-[#E5E7EB] p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-[#1F2937] mb-4">Gestion du statut</h3>
                    <div class="flex gap-2 flex-wrap">
                        @if($devis->canMarkAsSent())
                            <form method="POST" action="{{ route('devis.mark-as-sent', $devis) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 text-white text-sm font-medium hover:bg-blue-600 transition-colors">Marquer comme envoyé</button>
                            </form>
                        @endif

                        @if($devis->canMarkAsAccepted())
                            <form method="POST" action="{{ route('devis.mark-as-accepted', $devis) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 text-white text-sm font-medium hover:bg-green-600 transition-colors">Marquer comme accepté</button>
                            </form>
                        @endif

                        @if($devis->canMarkAsRefused())
                            <form method="POST" action="{{ route('devis.mark-as-refused', $devis) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition-colors" onclick="return confirm('Êtes-vous sûr ?')">Marquer comme refusé</button>
                            </form>
                        @endif

                        @if($devis->canConvertToFacture())
                            <form method="POST" action="{{ route('devis.convert-to-facture', $devis) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 transition-colors">Convertir en facture</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
