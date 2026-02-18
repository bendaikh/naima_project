@extends('layouts.dashboard')
@section('title', 'Détails du Retour Fournisseur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Retour Fournisseur #{{ $bonRetour->id }}</h1>
        <div class="flex gap-2">
            @if($bonRetour->status === 'PENDING')
                <form action="{{ route('achats.bon-retour-fournisseur.complete', $bonRetour) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors">
                        Compléter le Retour
                    </button>
                </form>
            @endif
            @if($bonRetour->isReplacement() && $bonRetour->status === 'COMPLETED')
                <form action="{{ route('achats.bon-retour-fournisseur.process-replacement', $bonRetour) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-[#8B5CF6] px-4 py-2 text-sm font-semibold text-white hover:bg-[#7C3AED] transition-colors">
                        Remplacement Reçu
                    </button>
                </form>
            @endif
            <a href="{{ route('achats.bon-retour-fournisseur.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
                ← Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-[#ECFDF5] p-4 text-sm text-[#065F46] border-l-4 border-[#10B981]">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Return Information -->
        <div class="col-span-2 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Informations de Retour</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-[#6B7280]">Bon de Commande</p>
                    <p class="text-lg font-medium text-[#1F2937]">
                        <a href="{{ route('achats.bon-de-commande.show', $bonRetour->bonDeCommande) }}" class="text-[#1860E1] hover:underline">
                            #{{ $bonRetour->bonDeCommande->id }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-[#6B7280]\">Fournisseur</p>
                    <p class="text-lg font-medium text-[#1F2937]">{{ $bonRetour->fournisseur->nom }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-[#6B7280]">Date de Retour</p>
                        <p class="text-lg font-medium text-[#1F2937]">{{ $bonRetour->return_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#6B7280]">Type de Retour</p>
                        <p class="text-lg font-medium text-[#1F2937]">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $bonRetour->return_type === 'REPLACEMENT' ? 'bg-[#DBEAFE] text-[#1E40AF]' : 'bg-[#FFEDD5] text-[#92400E]' }}
                            ">
                                {{ $bonRetour->return_type === 'REPLACEMENT' ? 'Remplacement' : 'Remboursement' }}
                            </span>
                        </p>
                    </div>
                </div>
                @if($bonRetour->return_reason)
                    <div class="border-t border-[#E5E7EB] pt-4">
                        <p class="text-sm text-[#6B7280]">Raison du Retour</p>
                        <p class="text-base text-[#1F2937]">{{ $bonRetour->return_reason }}</p>
                    </div>
                @endif
                @if($bonRetour->notes)
                    <div class="border-t border-[#E5E7EB] pt-4">
                        <p class="text-sm text-[#6B7280]">Remarques</p>
                        <p class="text-base text-[#1F2937]">{{ $bonRetour->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Card -->
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">État</h2>
            <div class="mb-4">
                <span class="inline-block rounded-full px-4 py-2 text-sm font-semibold
                    @if($bonRetour->status === 'PENDING') bg-yellow-100 text-yellow-800
                    @elseif($bonRetour->status === 'COMPLETED') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif
                ">
                    {{ $bonRetour->status === 'PENDING' ? 'En Attente' : ($bonRetour->status === 'COMPLETED' ? 'Complété' : 'Annulé') }}
                </span>
            </div>
            <div>
                <p class="mb-1 text-sm text-[#6B7280]">Montant Total Retourné</p>
                <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($bonRetour->getTotalReturnedAmount(), 2) }} DH</p>
            </div>
        </div>
    </div>

    <!-- Return Lines -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Produits Retournés</h2>
        @if($bonRetour->lignes->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-[#E5E7EB] bg-[#F9FAFB]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Produit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Reçu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Déjà Retourné</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Qté Retour</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Prix Unitaire</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Montant Retour</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @foreach($bonRetour->lignes as $ligne)
                            <tr class="hover:bg-[#F9FAFB]">
                                <td class="px-4 py-3 text-sm text-[#1F2937]">{{ $ligne->article->nom }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->quantity_received, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->quantity_already_returned, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-[#1F2937]">{{ number_format($ligne->return_quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->bonDeCommandeLigne->purchase_price, 2) }} DH</td>
                                <td class="px-4 py-3 text-sm font-medium text-[#1F2937]">{{ number_format($ligne->getReturnAmount(), 2) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-[#6B7280]">Aucun produit dans ce retour.</p>
        @endif
    </div>

    <!-- Avoir Information (if REFUND and completed) -->
    @if($bonRetour->isRefund() && $bonRetour->avoir)
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Note de Crédit Associée</h2>
            <div class="border-l-4 border-[#10B981] pl-4 py-2">
                <a href="{{ route('achats.avoir-fournisseur.show', $bonRetour->avoir) }}" class="text-lg font-medium text-[#1860E1] hover:text-[#1E40AF]">
                    Avoir #{{ $bonRetour->avoir->id }}
                </a>
                <p class="mt-1 text-sm text-[#6B7280]">
                    Montant: {{ number_format($bonRetour->avoir->amount, 2) }} DH | 
                    État: 
                    <span class="rounded px-2 py-1 text-xs font-semibold
                        {{ $bonRetour->avoir->status === 'RECEIVED' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEF3C7] text-[#92400E]' }}
                    ">
                        {{ $bonRetour->avoir->status === 'RECEIVED' ? 'Reçu' : 'En Attente' }}
                    </span>
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
