@extends('layouts.dashboard')

@section('title', 'Devis ' . $devis->numero)

@section('content')
    <!-- SCREEN VERSION -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header with Actions -->
        <div class="bg-gradient-to-r from-[#1F2937] to-[#374151] rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">Devis {{ $devis->numero }}</h1>
                    <p class="mt-2 text-[#E5E7EB]">Créé le {{ $devis->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex gap-3 items-center flex-wrap">
                    <a href="{{ route('devis.print', $devis) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-[#10B981] px-5 py-3 text-sm font-semibold text-white hover:bg-[#059669] transition-all duration-200 shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer
                    </a>
                    @if($devis->canConvertToFacture())
                        <form method="POST" action="{{ route('devis.convert-to-facture', $devis) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#8B5CF6] px-5 py-3 text-sm font-semibold text-white hover:bg-[#7C3AED] transition-all duration-200 shadow-md">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Convertir en facture
                            </button>
                        </form>
                    @endif
                    @if($devis->canBeEdited())
                        <a href="{{ route('devis.edit', $devis) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#3B82F6] px-5 py-3 text-sm font-semibold text-white hover:bg-[#2563EB] transition-all duration-200 shadow-md">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('devis.index') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-white px-5 py-3 text-sm font-semibold text-white hover:bg-white hover:text-[#1F2937] transition-all duration-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour
                    </a>
                    <form method="POST" action="{{ route('devis.destroy', $devis) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-md">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#D1FAE5] border-2 border-[#10B981] p-4 mb-6 text-[#065F46] font-medium flex items-center gap-2">
                <span>✓</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Status Badge -->
        <div class="mb-6">
            @if($devis->statut === 'brouillon')
                <span class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold">
                    📋 Brouillon
                </span>
            @elseif($devis->statut === 'envoye')
                <span class="inline-block bg-blue-200 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                    📤 Envoyé
                </span>
            @elseif($devis->statut === 'accepte')
                <span class="inline-block bg-green-200 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    ✓ Accepté
                </span>
            @else
                <span class="inline-block bg-red-200 text-red-800 px-4 py-2 rounded-full text-sm font-semibold">
                    ✗ Refusé
                </span>
            @endif
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <!-- Client Info -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#3B82F6] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">👤 CLIENT</h3>
                <div class="space-y-2 text-[#374151]">
                    <p class="font-semibold text-base">{{ $devis->client->nom_raison_sociale ?? '—' }}</p>
                    @if($devis->client->adresse)
                        <p class="text-sm">{{ $devis->client->adresse }}</p>
                    @endif
                    @if($devis->client->ville)
                        <p class="text-sm">{{ $devis->client->ville }}</p>
                    @endif
                    @if($devis->client->telephone)
                        <p class="text-sm">📞 {{ $devis->client->telephone }}</p>
                    @endif
                </div>
            </div>

            <!-- Devis Info -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#10B981] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📅 INFORMATIONS</h3>
                <div class="space-y-3 text-[#374151]">
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">Date</p>
                        <p class="text-base font-semibold">{{ $devis->date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">Créé le</p>
                        <p class="text-base font-semibold">{{ $devis->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Conditions -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#F59E0B] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">⚙️ CONDITIONS</h3>
                <div class="space-y-3 text-[#374151]">
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">TVA</p>
                        <p class="text-2xl font-bold text-[#F59E0B]">{{ $devis->tva }}%</p>
                    </div>
                    @if($devis->disponibilite)
                        <div>
                            <p class="text-xs font-semibold text-[#6B7280] uppercase">Disponibilité</p>
                            <p class="text-base font-semibold">{{ $devis->disponibilite }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Articles Section -->
        @if($devis->lignes && $devis->lignes->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📦 ARTICLES</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#F3F4F6] border-b-2 border-[#E5E7EB]">
                                <th class="px-4 py-3 text-left font-bold text-[#374151]">Désignation</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Quantité</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Prix unitaire</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis->lignes as $ligne)
                                <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB] transition-colors">
                                    <td class="px-4 py-3 text-[#1F2937] font-medium">{{ $ligne->designation }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->quantite, 2) }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                                    <td class="px-4 py-3 text-right font-bold text-[#1F2937]">{{ number_format($ligne->total_ht, 2, ',', ' ') }} DH</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Totals Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="ml-auto max-w-md">
                <div class="space-y-3 mb-4 pb-4 border-b-2 border-[#E5E7EB]">
                    <div class="flex justify-between items-center text-[#374151]">
                        <span class="font-medium">TOTAL HT</span>
                        <span class="font-semibold">{{ number_format($devis->total_ht, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="flex justify-between items-center text-[#374151]">
                        <span class="font-medium">TVA ({{ $devis->tva }}%)</span>
                        <span class="font-semibold">{{ number_format($devis->total_ttc - $devis->total_ht, 2, ',', ' ') }} DH</span>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-gradient-to-r from-[#1F2937] to-[#374151] rounded-lg p-4 text-white">
                    <span class="text-lg font-bold">TOTAL TTC</span>
                    <span class="text-2xl font-bold">{{ number_format($devis->total_ttc, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        @if($devis->signature_image)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">✍️ SIGNATURE</h3>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $devis->signature_image) }}" alt="Signature" class="max-w-sm max-h-64 rounded-lg border-2 border-[#E5E7EB] shadow-md">
                </div>
            </div>
        @endif

        <!-- Status Management -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📋 GESTION DU STATUT</h3>
            <div class="flex gap-3 flex-wrap">
                @if($devis->canMarkAsSent())
                    <form method="POST" action="{{ route('devis.mark-as-sent', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition-all duration-200 shadow-md">
                            📤 Marquer comme envoyé
                        </button>
                    </form>
                @endif

                @if($devis->canMarkAsAccepted())
                    <form method="POST" action="{{ route('devis.mark-as-accepted', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition-all duration-200 shadow-md">
                            ✓ Marquer comme accepté
                        </button>
                    </form>
                @endif

                @if($devis->canMarkAsRefused())
                    <form method="POST" action="{{ route('devis.mark-as-refused', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-all duration-200 shadow-md" onclick="return confirm('Êtes-vous sûr ?')">
                            ✗ Marquer comme refusé
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection
