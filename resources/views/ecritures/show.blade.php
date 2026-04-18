@extends('layouts.dashboard')

@section('title', 'Détails de l\'écriture')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Détails de l'écriture #{{ $ecriture->ref }}</h1>
            <div class="flex gap-3">
                <a href="{{ route('ecritures.edit', $ecriture) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Modifier
                </a>
                <a href="{{ route('ecritures.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Informations générales</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @if($ecriture->ref)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Référence</p>
                        <p class="mt-1 text-base font-semibold text-[#1F2937]">{{ $ecriture->ref }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Date valeur</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->date_valeur?->format('d/m/Y') ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Compte bancaire</p>
                        <p class="mt-1 text-base font-semibold text-[#1F2937]">{{ $ecriture->compteBancaire->libelle ?? '—' }}</p>
                    </div>

                    @if($ecriture->type)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Type</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->type }}</p>
                    </div>
                    @endif

                    @if($ecriture->numero)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Numéro</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->numero }}</p>
                    </div>
                    @endif

                    @if($ecriture->tiers_utilisateur)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Tiers/Utilisateur</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->tiers_utilisateur }}</p>
                    </div>
                    @endif

                    @if($ecriture->categorie)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Catégorie</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->categorie->nom }}</p>
                    </div>
                    @endif

                    @if($ecriture->releve)
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Relevé</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->releve }}</p>
                    </div>
                    @endif

                    <div class="md:col-span-2 lg:col-span-3">
                        <p class="text-sm font-medium text-[#6B7280]">Description</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->description }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Montants</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Débit</p>
                        <p class="mt-1 text-xl font-bold text-red-600">
                            {{ $ecriture->debit > 0 ? number_format($ecriture->debit, 2, ',', ' ') . ' DH' : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Crédit</p>
                        <p class="mt-1 text-xl font-bold text-emerald-600">
                            {{ $ecriture->credit > 0 ? number_format($ecriture->credit, 2, ',', ' ') . ' DH' : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Solde</p>
                        <p class="mt-1 text-xl font-bold text-[#1F2937]">
                            {{ number_format($ecriture->solde, 2, ',', ' ') }} DH
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Informations système</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Créée le</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#6B7280]">Dernière modification</p>
                        <p class="mt-1 text-base text-[#1F2937]">{{ $ecriture->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
