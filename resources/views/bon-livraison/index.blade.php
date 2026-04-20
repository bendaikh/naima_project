@extends('layouts.dashboard')

@section('title', 'Bons de livraison')

@section('content')
    <div class="w-full space-y-6">
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
            <form method="get" action="{{ route('bon-livraison.index') }}" class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-[#374151] mb-2">Filtrer par statut</label>
                    <select name="statut" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="livre" {{ request('statut') === 'livre' ? 'selected' : '' }}>Livré</option>
                        <option value="validé" {{ request('statut') === 'validé' ? 'selected' : '' }}>Validé</option>
                        <option value="annulé" {{ request('statut') === 'annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-medium text-white hover:bg-[#1557C7] transition-colors">Filtrer</button>
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
                                    @elseif($bon->statut === 'validé')
                                        <span class="px-3 py-1 rounded-full bg-[#E0E7FF] text-[#3730A3] text-xs font-medium">Validé</span>
                                    @elseif($bon->statut === 'annulé' || $bon->statut === 'annule')
                                        <span class="px-3 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] text-xs font-medium">Annulé</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-[#F3F4F6] text-[#6B7280] text-xs font-medium">{{ $bon->statut }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('bon-livraison.show', $bon) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 w-8 h-8 text-white hover:bg-blue-600 transition-colors" title="Voir">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                        </a>
                                        <a href="{{ route('bon-livraison.print', $bon) }}" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-green-500 w-8 h-8 text-white hover:bg-green-600 transition-colors" title="Imprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>
                                        <a href="{{ route('bon-livraison.edit', $bon) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-500 w-8 h-8 text-white hover:bg-amber-600 transition-colors" title="Modifier">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('bon-livraison.destroy', $bon) }}" onsubmit="return confirm('Confirmer la suppression ?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-500 w-8 h-8 text-white hover:bg-red-600 transition-colors" title="Supprimer">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                                            </button>
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
