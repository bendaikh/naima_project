@extends('layouts.dashboard')

@section('title', 'Détails du virement')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Détails du virement</h1>
            <div class="flex gap-2">
                <a href="{{ route('virement-interne.edit', $virementInterne) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Modifier
                </a>
                <a href="{{ route('virement-interne.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour
                </a>
            </div>
        </div>

        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif

        <div class="rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-sm font-medium text-blue-700">Montant viré</p>
            </div>
            <p class="text-2xl font-bold text-blue-900">
                {{ number_format($virementInterne->montant, 2, ',', ' ') }} DH
            </p>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Informations du virement</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Compte source</p>
                    <p class="mt-1 text-base font-semibold text-[#1F2937]">{{ $virementInterne->deCompte->libelle }}</p>
                    <p class="text-xs text-[#6B7280]">{{ $virementInterne->deCompte->numero_compte ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Compte destination</p>
                    <p class="mt-1 text-base font-semibold text-[#1F2937]">{{ $virementInterne->versCompte->libelle }}</p>
                    <p class="text-xs text-[#6B7280]">{{ $virementInterne->versCompte->numero_compte ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Date du virement</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $virementInterne->date->format('d/m/Y') }}</p>
                </div>

                @if($virementInterne->type)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Type</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $virementInterne->type }}</p>
                </div>
                @endif

                @if($virementInterne->description)
                <div class="col-span-2">
                    <p class="text-sm font-medium text-[#6B7280]">Description</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $virementInterne->description }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Créé le</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $virementInterne->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Modifié le</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $virementInterne->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
