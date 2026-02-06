@extends('layouts.dashboard')

@section('title', 'Nouveau devis')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Nouveau devis</h1>
        <form method="post" action="{{ route('devis.store') }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            <div>
                <label for="client_id" class="block text-sm font-medium text-[#374151]">Client *</label>
                <select name="client_id" id="client_id" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="">Choisir un client</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->nom_raison_sociale }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-[#374151]">Date *</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label for="tva" class="block text-sm font-medium text-[#374151]">TVA (%)</label>
                <input type="number" name="tva" id="tva" value="{{ old('tva', 20) }}" step="0.01" min="0" max="100" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Créer le devis</button>
                <a href="{{ route('devis.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>
@endsection
