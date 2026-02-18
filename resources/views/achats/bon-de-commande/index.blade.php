@extends('layouts.dashboard')
@section('title', 'Bons de Commande')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Bons de Commande</h1>
        <a href="{{ route('achats.bon-de-commande.create') }}" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
            + Nouveau Bon de Commande
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
        <form method="GET" id="filter-form" class="space-y-4">
            <!-- Main Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Chercher par fournisseur, numéro de commande..."
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-[#E5E7EB] text-sm focus:border-[#1860E1] focus:ring-2 focus:ring-[#1860E1] focus:ring-opacity-50 transition-all"
                    onchange="document.getElementById('filter-form').submit()"
                >
            </div>

            <!-- Filter Pills -->
            <div class="flex flex-wrap gap-3 items-center">
                <!-- Status Filter -->
                <div class="relative inline-block">
                    <select 
                        id="status" 
                        name="status" 
                        class="appearance-none pl-4 pr-10 py-2 rounded-lg border border-[#E5E7EB] text-sm focus:border-[#1860E1] focus:ring-2 focus:ring-[#1860E1] focus:ring-opacity-50 transition-all cursor-pointer bg-white"
                        onchange="document.getElementById('filter-form').submit()"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>📋 Brouillon</option>
                        <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>✓ Confirmée</option>
                        <option value="RECEIVED" {{ request('status') === 'RECEIVED' ? 'selected' : '' }}>📦 Reçu</option>
                    </select>
                    <svg class="absolute right-2 top-2.5 h-5 w-5 text-[#6B7280] pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Supplier Filter -->
                <div class="relative inline-block">
                    <select 
                        id="fournisseur_id" 
                        name="fournisseur_id" 
                        class="appearance-none pl-4 pr-10 py-2 rounded-lg border border-[#E5E7EB] text-sm focus:border-[#1860E1] focus:ring-2 focus:ring-[#1860E1] focus:ring-opacity-50 transition-all cursor-pointer bg-white"
                        onchange="document.getElementById('filter-form').submit()"
                    >
                        <option value="">Tous les fournisseurs</option>
                        @foreach(\App\Models\Fournisseur::orderBy('nom')->get() as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="absolute right-2 top-2.5 h-5 w-5 text-[#6B7280] pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Date Range -->
                <div class="flex gap-2 items-center">
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}" 
                        class="px-3 py-2 rounded-lg border border-[#E5E7EB] text-sm focus:border-[#1860E1] focus:ring-2 focus:ring-[#1860E1] focus:ring-opacity-50 transition-all"
                        onchange="document.getElementById('filter-form').submit()"
                    >
                    <span class="text-[#9CA3AF]">à</span>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}" 
                        class="px-3 py-2 rounded-lg border border-[#E5E7EB] text-sm focus:border-[#1860E1] focus:ring-2 focus:ring-[#1860E1] focus:ring-opacity-50 transition-all"
                        onchange="document.getElementById('filter-form').submit()"
                    >
                </div>

                <!-- Clear Filters Button -->
                @if(request()->filled('search') || request()->filled('status') || request()->filled('fournisseur_id') || request()->filled('date_from') || request()->filled('date_to'))
                    <a href="{{ route('achats.bon-de-commande.index') }}" class="ml-auto inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#FEE2E2] text-[#991B1B] text-sm font-medium hover:bg-[#FECACA] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($bonsDeCommande->count())
    <div class="rounded-xl border border-[#E5E7EB] bg-white shadow-sm overflow-hidden">
        <table class="w-full">
                <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Commande #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Date de Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Livraison Prévue</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($bonsDeCommande as $bon)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1F2937]">#{{ $bon->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $bon->fournisseur->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ $bon->order_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6B7280]">
                                {{ $bon->expected_delivery_date?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    @if($bon->status === 'DRAFT') bg-[#FEF3C7] text-[#92400E]
                                    @elseif($bon->status === 'CONFIRMED') bg-[#DBEAFE] text-[#1E40AF]
                                    @elseif($bon->status === 'RECEIVED') bg-[#DCFCE7] text-[#166534]
                                    @else bg-[#F3F4F6] text-[#6B7280]
                                    @endif
                                ">
                                    @if($bon->status === 'DRAFT') Brouillon
                                    @elseif($bon->status === 'CONFIRMED') Confirmée
                                    @elseif($bon->status === 'RECEIVED') Reçu
                                    @else {{ $bon->status }}
                                    @endif
                                </span>
                            </td>{ number_format($bon->getTotalAmount(), 2, ',', ' ') }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2 flex flex-wrap gap-2">
                                <a href="{{ route('achats.bon-de-commande.show', $bon) }}" class="text-[#1860E1] hover:text-[#1557C7] font-medium">Voir</a>
                                @if($bon->status === 'DRAFT')
                                    <a href="{{ route('achats.bon-de-commande.edit', $bon) }}" class="text-[#F59E0B] hover:text-[#D97706] font-medium">Modifier</a>
                                    <form action="{{ route('achats.bon-de-commande.confirm', $bon) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#10B981] hover:text-[#059669] font-medium">Confirmer</button>
                                    </form>
                                @endif
                                @if(in_array($bon->status, ['CONFIRMED', 'RECEIVED']))
                                    <a href="{{ route('achats.bon-de-commande.receive-form', $bon) }}" class="text-[#8B5CF6] hover:text-[#7C3AED] font-medium">Réceptionner</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bonsDeCommande->links('pagination::tailwind') }}
        </div>
    @else
        <div class="rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-4 text-[#6B7280] text-lg">Aucun bon de commande trouvé.</p>
            <a href="{{ route('achats.bon-de-commande.create') }}" class="mt-6 inline-block rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                Créer le premier bon de commande
            </a>
        </div>
    @endif
</div>
@endsection
