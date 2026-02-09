@extends('layouts.dashboard')

@section('title', 'Mouvement de Stock')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('stock-mouvements.index') }}" class="text-sm text-[#1860E1] hover:underline">← Mouvements de stock</a>
                <h1 class="mt-2 text-2xl font-semibold text-[#1F2937]">Détail du Mouvement</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Details -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Type Badge and Quantity -->
                <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Type de Mouvement</p>
                            <p class="mt-2 text-2xl font-bold">{{ $mouvement->getTypeLabel() }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $mouvement->type === 'entree' ? 'bg-[#DBEAFE] text-[#1E40AF]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                            {{ $mouvement->type === 'entree' ? 'Entrée' : 'Sortie' }}
                        </span>
                    </div>

                    <div class="mt-6 border-t border-[#E5E7EB] pt-6">
                        <p class="text-sm font-medium text-[#6B7280]">Quantité</p>
                        <p class="mt-2 text-4xl font-bold {{ $mouvement->type === 'entree' ? 'text-[#10B981]' : 'text-[#EF4444]' }}">
                            {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ number_format($mouvement->quantite, 2, ',', ' ') }} {{ $mouvement->article->unite }}
                        </p>
                    </div>
                </div>

                <!-- Article Information -->
                <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Article</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Nom</p>
                            <p class="mt-1 text-[#1F2937]">
                                <a href="{{ route('stock-mouvements.article', $mouvement->article) }}" class="text-[#1860E1] hover:underline font-semibold">
                                    {{ $mouvement->article->nom }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Catégorie</p>
                            <p class="mt-1 text-[#1F2937]">{{ $mouvement->article->categorie ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Stock Actuel</p>
                            <p class="mt-1 text-[#1F2937] font-semibold">{{ number_format($mouvement->article->quantite_stock, 2, ',', ' ') }} {{ $mouvement->article->unite }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reference Information -->
                @if($mouvement->reference_type)
                    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Référence</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-[#6B7280]">Document</p>
                                <p class="mt-1 text-[#1F2937] font-semibold">{{ $mouvement->reference_type }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#6B7280]">Numéro</p>
                                <p class="mt-1 text-[#1F2937]">#{{ $mouvement->reference_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#6B7280]">Motif</p>
                                <p class="mt-1 text-[#1F2937]">{{ $mouvement->getMotifLabel() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Date and User -->
                <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Informations</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Date du Mouvement</p>
                            <p class="mt-1 text-[#1F2937] font-semibold">{{ $mouvement->date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#6B7280]">Créé le</p>
                            <p class="mt-1 text-[#1F2937] text-sm">{{ $mouvement->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @if($mouvement->user)
                            <div>
                                <p class="text-sm font-medium text-[#6B7280]">Utilisateur</p>
                                <p class="mt-1 text-[#1F2937]">{{ $mouvement->user->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($mouvement->description)
                    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Description</h3>
                        <p class="text-[#1F2937]">{{ $mouvement->description }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <a href="{{ route('stock-mouvements.index') }}" class="block w-full rounded-lg bg-[#1860E1] px-4 py-2 text-center text-sm font-semibold text-white hover:bg-[#1557C7]">
                        Retour aux Mouvements
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
