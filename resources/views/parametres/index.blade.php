@extends('layouts.dashboard')

@section('title', 'Paramètres')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Paramètres</h1>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
        <form method="post" action="{{ route('parametres.update') }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-medium text-[#1F2937]">Informations de l'entreprise</h2>
            <div>
                <label for="nom" class="block text-sm font-medium text-[#374151]">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $params->nom) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label for="adresse" class="block text-sm font-medium text-[#374151]">Adresse</label>
                <textarea name="adresse" id="adresse" rows="2" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('adresse', $params->adresse) }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="telephone" class="block text-sm font-medium text-[#374151]">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $params->telephone) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-[#374151]">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $params->email) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
            </div>
            <h2 class="pt-4 text-lg font-medium text-[#1F2937]">TVA et numérotation</h2>
            <div>
                <label for="tva_par_defaut" class="block text-sm font-medium text-[#374151]">TVA par défaut (%)</label>
                <input type="number" name="tva_par_defaut" id="tva_par_defaut" value="{{ old('tva_par_defaut', $params->tva_par_defaut) }}" step="0.01" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="prefixe_devis" class="block text-sm font-medium text-[#374151]">Préfixe devis</label>
                    <input type="text" name="prefixe_devis" id="prefixe_devis" value="{{ old('prefixe_devis', $params->prefixe_devis) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
                <div>
                    <label for="prefixe_facture" class="block text-sm font-medium text-[#374151]">Préfixe facture</label>
                    <input type="text" name="prefixe_facture" id="prefixe_facture" value="{{ old('prefixe_facture', $params->prefixe_facture) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
            </div>
            <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Enregistrer</button>
        </form>
    </div>
@endsection
