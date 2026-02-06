@extends('layouts.dashboard')

@section('title', 'Devis ' . $devis->numero)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Devis {{ $devis->numero }}</h1>
            <a href="{{ route('devis.edit', $devis) }}" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Modifier</a>
        </div>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <p class="text-sm text-[#6B7280]">Client : {{ $devis->client->nom_raison_sociale ?? '—' }}</p>
            <p class="text-sm text-[#6B7280]">Date : {{ $devis->date->format('d/m/Y') }}</p>
            <p class="text-sm text-[#6B7280]">Statut : {{ $devis->statut }}</p>
            <p class="mt-2 font-semibold text-[#1F2937]">Total TTC : {{ number_format($devis->total_ttc, 2, ',', ' ') }} €</p>
            <p class="mt-4 text-sm text-[#6B7280]">Lignes du devis et conversion en facture à compléter.</p>
        </div>
    </div>
@endsection
