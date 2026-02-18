@extends('layouts.dashboard')
@section('title', 'Détails de l\'Avoir Fournisseur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Note de Crédit Fournisseur #{{ $avoir->id }}</h1>
        <div class="flex gap-2">
            @if($avoir->status === 'PENDING')
                <form action="{{ route('achats.avoir-fournisseur.mark-received', $avoir) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors">
                        Marquer comme Reçu
                    </button>
                </form>
            @endif
            <a href="{{ route('achats.avoir-fournisseur.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
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
        <!-- Avoir Information -->
        <div class="col-span-2 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Informations de la Note de Crédit</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-[#6B7280]">Fournisseur</p>
                    <p class="text-lg font-medium text-[#1F2937]">{{ $avoir->fournisseur->nom }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#6B7280]">Retour Associé</p>
                    <p class="text-lg font-medium text-[#1F2937]">
                        <a href="{{ route('achats.bon-retour-fournisseur.show', $avoir->bonRetourFournisseur) }}" class="text-[#1860E1] hover:underline">
                            Retour #{{ $avoir->bonRetourFournisseur->id }}
                        </a>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-[#6B7280]">Date de l'Avoir</p>
                        <p class="text-lg font-medium text-[#1F2937]">{{ $avoir->avoir_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#6B7280]">Type de Retour</p>
                        <p class="text-lg font-medium text-[#1F2937]">
                            <span class="rounded bg-[#FFEDD5] px-2 py-1 text-sm text-[#92400E]">
                                Remboursement
                            </span>
                        </p>
                    </div>
                </div>
                @if($avoir->notes)
                    <div>
                        <p class="text-sm text-[#6B7280]">Remarques</p>
                        <p class="text-base text-[#1F2937]">{{ $avoir->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Card -->
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">État</h2>
            <div class="mb-4">
                <span class="inline-block rounded-full px-4 py-2 text-sm font-semibold
                    @if($avoir->status === 'RECEIVED') bg-[#DCFCE7] text-[#166534]
                    @elseif($avoir->status === 'PENDING') bg-[#FEF3C7] text-[#92400E]
                    @else bg-red-100 text-red-800
                    @endif
                ">
                    {{ $avoir->status === 'RECEIVED' ? 'Reçu' : ($avoir->status === 'PENDING' ? 'En Attente' : 'Annulé') }}
                </span>
            </div>
            <div>
                <p class="mb-1 text-sm text-[#6B7280]">Montant du Crédit</p>
                <p class="text-3xl font-bold text-[#1F2937]">{{ number_format($avoir->amount, 2) }} DH</p>
            </div>
        </div>
    </div>

    <!-- Return Details -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Détails des Produits Retournés</h2>
        @if($avoir->bonRetourFournisseur->lignes->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-[#E5E7EB] bg-[#F9FAFB]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Produit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Qté Retour</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Prix Unitaire</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#6B7280]">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @foreach($avoir->bonRetourFournisseur->lignes as $ligne)
                            <tr class="hover:bg-[#F9FAFB]">
                                <td class="px-4 py-3 text-sm text-[#1F2937]">{{ $ligne->article->nom }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->return_quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->bonDeCommandeLigne->purchase_price, 2) }} DH</td>
                                <td class="px-4 py-3 text-sm font-medium text-[#1F2937]">{{ number_format($ligne->getReturnAmount(), 2) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-[#6B7280]">Aucun produit dans le retour associé.</p>
        @endif
    </div>
</div>
@endsection
