@extends('layouts.dashboard')

@section('title', 'Comptabilité')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Comptabilité</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('comptabilite.journal-general') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Journal général</p>
                    <p class="text-xs text-slate-500">Consulter les écritures</p>
                </div>
            </div>
        </a>

        <a href="{{ route('comptabilite.bilan') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14m-4 0a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Bilan</p>
                    <p class="text-xs text-slate-500">État financier</p>
                </div>
            </div>
        </a>

        <a href="{{ route('comptabilite.resultat') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7H7v10h6V7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Résultat</p>
                    <p class="text-xs text-slate-500">Compte de résultat</p>
                </div>
            </div>
        </a>

        <a href="{{ route('comptabilite.tva') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">TVA</p>
                    <p class="text-xs text-slate-500">Déclaration TVA</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
