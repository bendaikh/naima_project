@extends('layouts.dashboard')
@section('title', 'Retours Fournisseurs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Retours fournisseurs</h1>
        <a href="{{ route('achats.bon-retour-fournisseur.create') }}" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
            + Ajouter un retour
        </a>
    </div>

    @if($bonRetours->count())
        <div class="overflow-x-auto rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#E5E7EB]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">N° Retour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">N° Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Date retour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#374151] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($bonRetours as $bon)
                        <tr class="hover:bg-[#F9FAFB]">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1F2937]">#{{ $bon->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">
                                <a href="{{ route('achats.bon-de-commande.show', $bon->bonDeCommande) }}" class="text-[#1860E1] hover:text-[#1557C7]">
                                    #{{ $bon->bonDeCommande->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $bon->fournisseur->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $bon->return_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="rounded px-2 py-1 text-xs font-semibold
                                    {{ $bon->return_type === 'REPLACEMENT' ? 'bg-[#DBEAFE] text-[#1E40AF]' : 'bg-[#FED7AA] text-[#92400E]' }}
                                ">
                                    {{ $bon->return_type === 'REPLACEMENT' ? 'Remplacement' : 'Remboursement' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @if($bon->status === 'PENDING') bg-[#FEF08A] text-[#713F12]
                                    @elseif($bon->status === 'COMPLETED') bg-[#DCFCE7] text-[#166534]
                                    @else bg-[#FEE2E2] text-[#991B1B]
                                    @endif
                                ">
                                    @if($bon->status === 'PENDING') En attente
                                    @elseif($bon->status === 'COMPLETED') Complété
                                    @else Erreur
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">
                                {{ number_format($bon->getTotalReturnedAmount(), 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2 text-sm">
                                <a href="{{ route('achats.bon-retour-fournisseur.show', $bon) }}" class="text-[#1860E1] hover:text-[#1557C7]">Voir</a>
                                @if($bon->status === 'PENDING')
                                    <form action="{{ route('achats.bon-retour-fournisseur.complete', $bon) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#10B981] hover:text-[#059669]">Compléter</button>
                                    </form>
                                @endif
                                @if($bon->isReplacement() && $bon->status === 'COMPLETED')
                                    <form action="{{ route('achats.bon-retour-fournisseur.process-replacement', $bon) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#8B5CF6] hover:text-[#7C3AED]">Entrer Remplacement</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bonRetours->links() }}
        </div>
    @else
        <div class="rounded-lg bg-[#F9FAFB] p-12 text-center">
            <p class="text-lg text-[#6B7280]">Aucun retour fournisseur trouvé.</p>
            <a href="{{ route('achats.bon-retour-fournisseur.create') }}" class="mt-4 inline-block rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                Créer un premier retour
            </a>
        </div>
    @endif
</div>
@endsection
