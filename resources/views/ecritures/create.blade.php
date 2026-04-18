@extends('layouts.dashboard')

@section('title', 'Nouvelle écriture')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Nouvelle écriture</h1>
            <a href="{{ route('ecritures.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour
            </a>
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ecritures.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Informations générales</h2>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="date_valeur" class="mb-2 block text-sm font-medium text-[#1F2937]">Date valeur <span class="text-red-500">*</span></label>
                        <input type="date" id="date_valeur" name="date_valeur" value="{{ old('date_valeur', date('Y-m-d')) }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>

                    <div>
                        <label for="compte_bancaire_id" class="mb-2 block text-sm font-medium text-[#1F2937]">Compte bancaire <span class="text-red-500">*</span></label>
                        <select id="compte_bancaire_id" name="compte_bancaire_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            <option value="">Sélectionner un compte</option>
                            @foreach($banques as $banque)
                                <option value="{{ $banque->id }}" {{ old('compte_bancaire_id') == $banque->id ? 'selected' : '' }}>
                                    {{ $banque->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type" class="mb-2 block text-sm font-medium text-[#1F2937]">Type</label>
                        <input type="text" id="type" name="type" value="{{ old('type') }}" placeholder="Ex: Virement bancaire" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>

                    <div>
                        <label for="numero" class="mb-2 block text-sm font-medium text-[#1F2937]">Numéro</label>
                        <input type="text" id="numero" name="numero" value="{{ old('numero') }}" placeholder="Ex: LABOMAG" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>

                    <div>
                        <label for="tiers_utilisateur" class="mb-2 block text-sm font-medium text-[#1F2937]">Tiers/Utilisateur</label>
                        <input type="text" id="tiers_utilisateur" name="tiers_utilisateur" value="{{ old('tiers_utilisateur') }}" placeholder="Ex: Attijari" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>

                    <div>
                        <label for="categorie_id" class="mb-2 block text-sm font-medium text-[#1F2937]">Catégorie</label>
                        <select id="categorie_id" name="categorie_id" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            <option value="">Aucune catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="releve" class="mb-2 block text-sm font-medium text-[#1F2937]">Relevé</label>
                        <input type="text" id="releve" name="releve" value="{{ old('releve') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="mb-2 block text-sm font-medium text-[#1F2937]">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="3" required placeholder="Ex: Règlement client" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-[#1F2937]">Montants</h2>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="debit" class="mb-2 block text-sm font-medium text-[#1F2937]">Débit</label>
                        <div class="relative">
                            <input type="number" id="debit" name="debit" step="0.01" min="0" value="{{ old('debit', 0) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 pr-12 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-[#6B7280]">DH</span>
                        </div>
                    </div>

                    <div>
                        <label for="credit" class="mb-2 block text-sm font-medium text-[#1F2937]">Crédit</label>
                        <div class="relative">
                            <input type="number" id="credit" name="credit" step="0.01" min="0" value="{{ old('credit', 0) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 pr-12 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-[#6B7280]">DH</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 rounded-lg bg-blue-50 p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> Le solde sera calculé automatiquement en fonction du solde actuel du compte et des montants saisis.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('ecritures.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                    Annuler
                </a>
                <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">
                    Créer l'écriture
                </button>
            </div>
        </form>
    </div>
@endsection
