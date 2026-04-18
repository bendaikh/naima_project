@extends('layouts.dashboard')

@section('title', 'Détails du compte')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Détails du compte</h1>
            <div class="flex gap-2">
                <a href="{{ route('banques.edit', $banque) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Modifier
                </a>
                <a href="{{ route('banques.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour
                </a>
            </div>
        </div>

        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Solde actuel card --}}
            <div class="rounded-lg border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-emerald-700">Solde Actuel</p>
                </div>
                <p class="text-2xl font-bold {{ $banque->solde_actuel >= 0 ? 'text-emerald-900' : 'text-red-900' }}">
                    {{ number_format($banque->solde_actuel, 2, ',', ' ') }} {{ $banque->devise }}
                </p>
            </div>

            {{-- Solde initial card --}}
            <div class="rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-blue-700">Solde Initial</p>
                </div>
                <p class="text-2xl font-bold text-blue-900">
                    {{ number_format($banque->solde_initial, 2, ',', ' ') }} {{ $banque->devise }}
                </p>
            </div>

            {{-- Variation card --}}
            <div class="rounded-lg border border-slate-200 bg-gradient-to-br from-slate-50 to-gray-50 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-500">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700">Variation</p>
                </div>
                <p class="text-2xl font-bold {{ ($banque->solde_actuel - $banque->solde_initial) >= 0 ? 'text-emerald-900' : 'text-red-900' }}">
                    {{ number_format($banque->solde_actuel - $banque->solde_initial, 2, ',', ' ') }} {{ $banque->devise }}
                </p>
            </div>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Informations du compte</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Nom du compte</p>
                    <p class="mt-1 text-base font-semibold text-[#1F2937]">{{ $banque->libelle }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Type</p>
                    <p class="mt-1">
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold 
                            @if(str_contains(strtolower($banque->type_compte ?? ''), 'banque')) bg-blue-100 text-blue-800
                            @elseif(str_contains(strtolower($banque->type_compte ?? ''), 'caisse')) bg-emerald-100 text-emerald-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $banque->type_compte ?? '—' }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Numéro de compte</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->numero_compte ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Devise</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->devise }}</p>
                </div>

                @if($banque->etat)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">État</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->etat }}</p>
                </div>
                @endif

                @if($banque->pays)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Pays du compte</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->pays }}</p>
                </div>
                @endif

                @if($banque->nom_banque)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Nom de la banque</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->nom_banque }}</p>
                </div>
                @endif

                @if($banque->code_iban)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Code IBAN</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->code_iban }}</p>
                </div>
                @endif

                @if($banque->code_bic_swift)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Code BIC/SWIFT</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->code_bic_swift }}</p>
                </div>
                @endif

                @if($banque->nom_proprietaire)
                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Nom du propriétaire</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->nom_proprietaire }}</p>
                </div>
                @endif

                @if($banque->commentaire)
                <div class="col-span-2">
                    <p class="text-sm font-medium text-[#6B7280]">Commentaire</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->commentaire }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Créé le</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-[#6B7280]">Modifié le</p>
                    <p class="mt-1 text-base text-[#1F2937]">{{ $banque->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-[#1F2937]">Historique des transactions</h2>
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="mt-2 text-sm text-gray-500">Aucune transaction pour le moment</p>
            </div>
        </div>
    </div>
@endsection
