@extends('layouts.dashboard')

@section('title', 'Bons de livraison')

@section('content')
    <div class="max-w-7xl space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-[#1F2937]">Bons de livraison</h1>
            <a href="{{ route('bon-livraison.create') }}" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">+ Nouveau bon</a>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-4 shadow-sm">
            <form method="get" action="{{ route('bon-livraison.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Filtrer par statut</label>
                    <select name="statut" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="livre" {{ request('statut') === 'livre' ? 'selected' : '' }}>Livré</option>
                        <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-[#6B7280] px-4 py-2 text-sm font-semibold text-white hover:bg-[#4B5563] transition-colors">Filtrer</button>
            </form>
        </div>

        <!-- Bons Table -->
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm overflow-x-auto">
            @if($bonsLivraison->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-[#374151]">Numéro</th>
                            <th class="px-6 py-3 text-left font-medium text-[#374151]">Client</th>
                            <th class="px-6 py-3 text-left font-medium text-[#374151]">Date</th>
                            <th class="px-6 py-3 text-left font-medium text-[#374151]">Statut</th>
                            <th class="px-6 py-3 text-center font-medium text-[#374151]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bonsLivraison as $bon)
                            <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB]">
                                <td class="px-6 py-4 font-semibold text-[#1F2937]">{{ $bon->numero }}</td>
                                <td class="px-6 py-4 text-[#374151]">{{ $bon->client->nom_raison_sociale }}</td>
                                <td class="px-6 py-4 text-[#374151]">{{ $bon->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($bon->statut === 'en_attente')
                                        <span class="px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-xs font-medium">En attente</span>
                                    @elseif($bon->statut === 'livre')
                                        <span class="px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-xs font-medium">Livré</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-xs font-medium">Annulé</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('bon-livraison.show', $bon) }}" class="text-[#1860E1] hover:text-[#1557C7] text-sm font-medium">Voir</a>
                                        <a href="{{ route('bon-livraison.edit', $bon) }}" class="text-[#6B7280] hover:text-[#374151] text-sm font-medium">Modifier</a>
                                        <form method="POST" action="{{ route('bon-livraison.destroy', $bon) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="text-red-600 hover:text-red-800 text-sm font-medium">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $bonsLivraison->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#6B7280] text-sm">Aucun bon de livraison trouvé</p>
                </div>
            @endif
        </div>
    </div>
@endsection
