@extends('layouts.dashboard')
@section('title', 'Avoirs Fournisseurs')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-[#1F2937]">Notes de Crédit Fournisseur</h1>

    @if($avoirs->count())
        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="w-full">
                <thead class="border-b border-[#E5E7EB] bg-[#F9FAFB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Avoir #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Retour #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">État</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6B7280]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($avoirs as $avoir)
                        <tr class="hover:bg-[#F9FAFB]">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1F2937]">#{{ $avoir->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $avoir->fournisseur->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">
                                <a href="{{ route('achats.bon-retour-fournisseur.show', $avoir->bonRetourFournisseur) }}" class="text-[#1860E1] hover:underline">
                                    #{{ $avoir->bonRetourFournisseur->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $avoir->avoir_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1F2937]">
                                {{ number_format($avoir->amount, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @if($avoir->status === 'RECEIVED') bg-[#DCFCE7] text-[#166534]
                                    @elseif($avoir->status === 'PENDING') bg-[#FEF3C7] text-[#92400E]
                                    @else bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ $avoir->status === 'RECEIVED' ? 'Reçu' : ($avoir->status === 'PENDING' ? 'En Attente' : 'Annulé') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('achats.avoir-fournisseur.show', $avoir) }}" class="text-[#1860E1] hover:text-[#1E40AF]">Voir</a>
                                @if($avoir->status === 'PENDING')
                                    <form action="{{ route('achats.avoir-fournisseur.mark-received', $avoir) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#10B981] hover:text-[#059669]">Marquer comme Reçu</button>
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
            {{ $avoirs->links() }}
        </div>
    @else
        <div class="rounded-lg bg-[#F9FAFB] p-12 text-center">
            <p class="text-lg text-[#6B7280]">Aucune note de crédit fournisseur trouvée.</p>
        </div>
    @endif
</div>
@endsection
