@extends('layouts.dashboard')
@section('title', 'Détails du Bon de Commande')

@section('content')
<!-- SCREEN VERSION -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Bon de Commande #{{ $bonDeCommande->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('achats.bon-de-commande.print', $bonDeCommande) }}" target="_blank" class="rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2v-2a2 2 0 00-2-2zm-6-4h.01M7 16h.01M17 16h.01" />
                </svg>
                Imprimer
            </a>
            @if($bonDeCommande->status === 'DRAFT')
                <a href="{{ route('achats.bon-de-commande.edit', $bonDeCommande) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition-colors flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('achats.bon-de-commande.confirm', $bonDeCommande) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirmer
                    </button>
                </form>
            @endif
            @if(in_array($bonDeCommande->status, ['CONFIRMED', 'RECEIVED']))
                <a href="{{ route('achats.bon-de-commande.receive-form', $bonDeCommande) }}" class="rounded-lg bg-[#8B5CF6] px-4 py-2 text-sm font-semibold text-white hover:bg-[#7C3AED] transition-colors flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    Réceptionner
                </a>
            @endif
            <a href="{{ route('achats.bon-de-commande.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-[#ECFDF5] p-4 text-sm text-[#065F46] border-l-4 border-[#10B981]">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="col-span-2 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Informations de commande</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-[#6B7280]">Fournisseur</p>
                    <p class="text-lg font-medium text-[#1F2937]">{{ $bonDeCommande->fournisseur->nom }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-[#6B7280]">Date de commande</p>
                        <p class="text-lg font-medium text-[#1F2937]">{{ $bonDeCommande->order_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#6B7280]">Date de livraison prévue</p>
                        <p class="text-lg font-medium text-[#1F2937]">{{ $bonDeCommande->expected_delivery_date?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                </div>
                @if($bonDeCommande->notes)
                    <div>
                        <p class="text-sm text-[#6B7280]">Remarques</p>
                        <p class="text-base text-[#1F2937]">{{ $bonDeCommande->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Card -->
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Statut</h2>
            <div class="mb-4">
                <span class="inline-block rounded-full px-4 py-2 text-sm font-semibold
                    @if($bonDeCommande->status === 'DRAFT') bg-[#F3F4F6] text-[#374151]
                    @elseif($bonDeCommande->status === 'CONFIRMED') bg-[#DBEAFE] text-[#1E40AF]
                    @elseif($bonDeCommande->status === 'RECEIVED') bg-[#DCFCE7] text-[#166534]
                    @elseif($bonDeCommande->status === 'COMPLETED') bg-[#E9D5FF] text-[#6B21A8]
                    @else bg-[#FEE2E2] text-[#991B1B]
                    @endif
                ">
                    @if($bonDeCommande->status === 'DRAFT') Brouillon
                    @elseif($bonDeCommande->status === 'CONFIRMED') Confirmée
                    @elseif($bonDeCommande->status === 'RECEIVED') Reçue
                    @elseif($bonDeCommande->status === 'COMPLETED') Complète
                    @else Erreur
                    @endif
                </span>
            </div>
            <div>
                <p class="mb-1 text-sm text-[#6B7280]">Montant total</p>
                <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($bonDeCommande->getTotalAmount(), 2) }} DH</p>
            </div>
        </div>
    </div>

    <!-- Order Lines -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Lignes de commande</h2>
        @if($bonDeCommande->lignes->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#E5E7EB]">
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Produit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Quantité</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Prix d'achat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Reçue</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @foreach($bonDeCommande->lignes as $ligne)
                            <tr class="hover:bg-[#F9FAFB]">
                                <td class="px-4 py-3 text-sm text-[#1F2937]">
                                    {{ $ligne->designation ?? $ligne->article?->nom ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->purchase_price, 2) }} DH</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->received_quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-[#1F2937]">{{ number_format($ligne->getLineTotal(), 2) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-[#6B7280]">Aucune ligne dans cette commande.</p>
        @endif
    </div>

    <!-- Retours d'Achat -->
    @if($bonDeCommande->retours->count())
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Retours associés</h2>
            <div class="space-y-3">
                @foreach($bonDeCommande->retours as $retour)
                    <div class="border-l-4 border-amber-500 pl-4 py-2">
                        <a href="{{ route('achats.bon-retour-fournisseur.show', $retour) }}" class="font-medium text-[#1860E1] hover:text-[#1557C7]">
                            Retour #{{ $retour->id }} - {{ $retour->return_date->format('d/m/Y') }}
                        </a>
                        <p class="text-xs text-[#6B7280]">Type: {{ $retour->return_type === 'REFUND' ? 'Remboursement' : 'Remplacement' }} | Statut: {{ $retour->status }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection
