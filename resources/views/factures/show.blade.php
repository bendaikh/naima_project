@extends('layouts.dashboard')

@section('title', 'Facture ' . $facture->numero)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Facture {{ $facture->numero }}</h1>
            <a href="{{ route('factures.edit', $facture) }}" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Modifier</a>
        </div>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <p class="text-sm text-[#6B7280]">Client : {{ $facture->client->nom_raison_sociale ?? '—' }}</p>
            <p class="text-sm text-[#6B7280]">Date : {{ $facture->date->format('d/m/Y') }}</p>
            <p class="text-sm text-[#6B7280]">Statut : {{ $facture->statut }}</p>
            <p class="mt-2 font-semibold text-[#1F2937]">Total TTC : {{ number_format($facture->total_ttc, 2, ',', ' ') }} €</p>
            <p class="text-sm text-[#6B7280]">Montant payé : {{ number_format($facture->montant_paye, 2, ',', ' ') }} €</p>
        </div>
    </div>
@endsection
