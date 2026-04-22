@extends('layouts.dashboard')

@section('title', 'Modifier la facture')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Modifier la facture {{ $facture->numero }}</h1>
        <form method="post" action="{{ route('factures.update', $facture) }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label for="numero" class="block text-sm font-medium text-[#374151]">Numéro *</label>
                <input type="text" name="numero" id="numero" value="{{ old('numero', $facture->numero) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('numero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="client_id" class="block text-sm font-medium text-[#374151]">Client *</label>
                <select name="client_id" id="client_id" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $facture->client_id) == $c->id ? 'selected' : '' }}>{{ $c->nom_raison_sociale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="date" class="block text-sm font-medium text-[#374151]">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $facture->date->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div>
                    <label for="date_echeance" class="block text-sm font-medium text-[#374151]">Date d'échéance</label>
                    <input type="date" name="date_echeance" id="date_echeance" value="{{ old('date_echeance', $facture->date_echeance?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
            </div>
            <div>
                <label for="statut" class="block text-sm font-medium text-[#374151]">Statut</label>
                <select name="statut" id="statut" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @foreach(['payee','non_payee','partiellement_payee'] as $s)
                        <option value="{{ $s }}" {{ old('statut', $facture->statut) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="montant_paye" class="block text-sm font-medium text-[#374151]">Montant payé (MAD)</label>
                <input type="number" name="montant_paye" id="montant_paye" value="{{ old('montant_paye', $facture->montant_paye) }}" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Enregistrer</button>
                <a href="{{ route('factures.show', $facture) }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>
@endsection
