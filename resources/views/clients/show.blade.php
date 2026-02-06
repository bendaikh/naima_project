@extends('layouts.dashboard')

@section('title', $client->nom_raison_sociale)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">{{ $client->nom_raison_sociale }}</h1>
            <a href="{{ route('clients.edit', $client) }}" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Modifier</a>
        </div>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div><dt class="text-xs font-medium text-[#6B7280]">Téléphone</dt><dd class="mt-1 text-sm text-[#1F2937]">{{ $client->telephone ?? '—' }}</dd></div>
                <div><dt class="text-xs font-medium text-[#6B7280]">Email</dt><dd class="mt-1 text-sm text-[#1F2937]">{{ $client->email ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-medium text-[#6B7280]">Adresse</dt><dd class="mt-1 text-sm text-[#1F2937]">{{ $client->adresse ?? '—' }}</dd></div>
            </dl>
        </div>
        <p class="text-sm text-[#6B7280]">Historique (devis, factures, bons) à intégrer ici.</p>
    </div>
@endsection
