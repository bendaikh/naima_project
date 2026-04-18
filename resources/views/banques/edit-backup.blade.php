@extends('layouts.dashboard')

@section('title', 'Ajouter un compte')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Ajouter un compte</h1>
            <a href="{{ route('banques.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour
            </a>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('banques.store') }}" class="space-y-6">
                @csrf

                {{-- Réf. --}}
                <div>
                    <label for="ref" class="mb-2 block text-sm font-medium text-[#1F2937]">Réf.</label>
                    <input type="text" id="ref" name="ref" value="{{ old('ref') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('ref') border-red-500 @enderror" placeholder="Auto-généré si vide">
                    @error('ref')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Libellé compte ou caisse --}}
                <div>
                    <label for="libelle" class="mb-2 block text-sm font-medium text-[#1F2937]">Libellé compte ou caisse</label>
                    <input type="text" id="libelle" name="libelle" value="{{ old('libelle') }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('libelle') border-red-500 @enderror">
                    @error('libelle')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type de compte --}}
                <div>
                    <label for="type_compte" class="mb-2 block text-sm font-medium text-[#1F2937]">Type de compte</label>
                    <select id="type_compte" name="type_compte" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('type_compte') border-red-500 @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="Compte bancaire, chèque, courant ou carte" {{ old('type_compte') === 'Compte bancaire, chèque, courant ou carte' ? 'selected' : '' }}>Compte bancaire, chèque, courant ou carte</option>
                        <option value="Caisse" {{ old('type_compte') === 'Caisse' ? 'selected' : '' }}>Caisse</option>
                        <option value="Épargne" {{ old('type_compte') === 'Épargne' ? 'selected' : '' }}>Épargne</option>
                        <option value="Autres" {{ old('type_compte') === 'Autres' ? 'selected' : '' }}>Autres</option>
                    </select>
                    @error('type_compte')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Devise --}}
                <div>
                    <label for="devise" class="mb-2 block text-sm font-medium text-[#1F2937]">Devise</label>
                    <select id="devise" name="devise" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('devise') border-red-500 @enderror">
                        <option value="MAD" {{ old('devise', 'MAD') === 'MAD' ? 'selected' : '' }}>Dirham (MAD)</option>
                        <option value="EUR" {{ old('devise') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        <option value="USD" {{ old('devise') === 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                    </select>
                    @error('devise')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- État --}}
                <div>
                    <label for="etat" class="mb-2 block text-sm font-medium text-[#1F2937]">État</label>
                    <select id="etat" name="etat" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('etat') border-red-500 @enderror">
                        <option value="Ouvert" {{ old('etat', 'Ouvert') === 'Ouvert' ? 'selected' : '' }}>Ouvert</option>
                        <option value="Fermé" {{ old('etat') === 'Fermé' ? 'selected' : '' }}>Fermé</option>
                        <option value="Suspendu" {{ old('etat') === 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                    @error('etat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pays du compte --}}
                <div>
                    <label for="pays" class="mb-2 block text-sm font-medium text-[#1F2937]">Pays du compte</label>
                    <select id="pays" name="pays" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('pays') border-red-500 @enderror">
                        <option value="">Sélectionner un pays</option>
                        <option value="Maroc (MA)" {{ old('pays', 'Maroc (MA)') === 'Maroc (MA)' ? 'selected' : '' }}>Maroc (MA)</option>
                        <option value="France (FR)" {{ old('pays') === 'France (FR)' ? 'selected' : '' }}>France (FR)</option>
                        <option value="Belgique (BE)" {{ old('pays') === 'Belgique (BE)' ? 'selected' : '' }}>Belgique (BE)</option>
                        <option value="Suisse (CH)" {{ old('pays') === 'Suisse (CH)' ? 'selected' : '' }}>Suisse (CH)</option>
                    </select>
                    @error('pays')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Département / Canton --}}
                <div>
                    <label for="departement" class="mb-2 block text-sm font-medium text-[#1F2937]">Département / Canton</label>
                    <input type="text" id="departement" name="departement" value="{{ old('departement') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('departement') border-red-500 @enderror">
                    @error('departement')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Domiciliation du compte --}}
                <div>
                    <label for="domiciliation" class="mb-2 block text-sm font-medium text-[#1F2937]">Domiciliation du compte</label>
                    <input type="text" id="domiciliation" name="domiciliation" value="{{ old('domiciliation') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('domiciliation') border-red-500 @enderror">
                    @error('domiciliation')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Web --}}
                <div>
                    <label for="web" class="mb-2 block text-sm font-medium text-[#1F2937]">Web</label>
                    <input type="url" id="web" name="web" value="{{ old('web') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('web') border-red-500 @enderror" placeholder="https://">
                    @error('web')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Commentaire --}}
                <div>
                    <label for="commentaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Commentaire</label>
                    <textarea id="commentaire" name="commentaire" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('commentaire') border-red-500 @enderror">{{ old('commentaire') }}</textarea>
                    @error('commentaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-[#E5E7EB]">

                {{-- Solde initial --}}
                <div>
                    <label for="solde_initial" class="mb-2 block text-sm font-medium text-[#1F2937]">Solde initial</label>
                    <input type="number" id="solde_initial" name="solde_initial" value="{{ old('solde_initial', 0) }}" step="0.01" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('solde_initial') border-red-500 @enderror">
                    @error('solde_initial')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label for="date" class="mb-2 block text-sm font-medium text-[#1F2937]">Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Solde minimum autorisé --}}
                <div>
                    <label for="solde_minimum_autorise" class="mb-2 block text-sm font-medium text-[#1F2937]">Solde minimum autorisé</label>
                    <input type="number" id="solde_minimum_autorise" name="solde_minimum_autorise" value="{{ old('solde_minimum_autorise') }}" step="0.01" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('solde_minimum_autorise') border-red-500 @enderror">
                    @error('solde_minimum_autorise')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Solde minimum désiré --}}
                <div>
                    <label for="solde_minimum_desire" class="mb-2 block text-sm font-medium text-[#1F2937]">Solde minimum désiré</label>
                    <input type="number" id="solde_minimum_desire" name="solde_minimum_desire" value="{{ old('solde_minimum_desire') }}" step="0.01" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('solde_minimum_desire') border-red-500 @enderror">
                    @error('solde_minimum_desire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-[#E5E7EB]">

                {{-- Nom de la banque --}}
                <div>
                    <label for="nom_banque" class="mb-2 block text-sm font-medium text-[#1F2937]">Nom de la banque</label>
                    <input type="text" id="nom_banque" name="nom_banque" value="{{ old('nom_banque') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('nom_banque') border-red-500 @enderror">
                    @error('nom_banque')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Code IBAN --}}
                <div>
                    <label for="code_iban" class="mb-2 block text-sm font-medium text-[#1F2937]">Code IBAN</label>
                    <input type="text" id="code_iban" name="code_iban" value="{{ old('code_iban') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('code_iban') border-red-500 @enderror">
                    @error('code_iban')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Code BIC/SWIFT --}}
                <div>
                    <label for="code_bic_swift" class="mb-2 block text-sm font-medium text-[#1F2937]">Code BIC/SWIFT</label>
                    <input type="text" id="code_bic_swift" name="code_bic_swift" value="{{ old('code_bic_swift') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('code_bic_swift') border-red-500 @enderror">
                    @error('code_bic_swift')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro de compte --}}
                <div>
                    <label for="numero_compte" class="mb-2 block text-sm font-medium text-[#1F2937]">Numéro de compte</label>
                    <input type="text" id="numero_compte" name="numero_compte" value="{{ old('numero_compte') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('numero_compte') border-red-500 @enderror">
                    @error('numero_compte')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-[#E5E7EB]">

                {{-- Nom du propriétaire du compte --}}
                <div>
                    <label for="nom_proprietaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Nom du propriétaire du compte</label>
                    <input type="text" id="nom_proprietaire" name="nom_proprietaire" value="{{ old('nom_proprietaire') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('nom_proprietaire') border-red-500 @enderror">
                    @error('nom_proprietaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Adresse du propriétaire du compte --}}
                <div>
                    <label for="adresse_proprietaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Adresse du propriétaire du compte</label>
                    <textarea id="adresse_proprietaire" name="adresse_proprietaire" rows="2" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('adresse_proprietaire') border-red-500 @enderror">{{ old('adresse_proprietaire') }}</textarea>
                    @error('adresse_proprietaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Code postal du propriétaire du compte --}}
                <div>
                    <label for="code_postal_proprietaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Code postal du propriétaire du compte</label>
                    <input type="text" id="code_postal_proprietaire" name="code_postal_proprietaire" value="{{ old('code_postal_proprietaire') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('code_postal_proprietaire') border-red-500 @enderror">
                    @error('code_postal_proprietaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ville du propriétaire du compte --}}
                <div>
                    <label for="ville_proprietaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Ville du propriétaire du compte</label>
                    <input type="text" id="ville_proprietaire" name="ville_proprietaire" value="{{ old('ville_proprietaire') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('ville_proprietaire') border-red-500 @enderror">
                    @error('ville_proprietaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pays du propriétaire du compte --}}
                <div>
                    <label for="pays_proprietaire" class="mb-2 block text-sm font-medium text-[#1F2937]">Pays du propriétaire du compte</label>
                    <select id="pays_proprietaire" name="pays_proprietaire" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('pays_proprietaire') border-red-500 @enderror">
                        <option value="">Sélectionner un pays</option>
                        <option value="Maroc" {{ old('pays_proprietaire') === 'Maroc' ? 'selected' : '' }}>Maroc</option>
                        <option value="France" {{ old('pays_proprietaire') === 'France' ? 'selected' : '' }}>France</option>
                        <option value="Belgique" {{ old('pays_proprietaire') === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                        <option value="Suisse" {{ old('pays_proprietaire') === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                    </select>
                    @error('pays_proprietaire')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-[#E5E7EB]">

                {{-- Compte comptable --}}
                <div>
                    <label for="compte_comptable" class="mb-2 block text-sm font-medium text-[#1F2937]">Compte comptable</label>
                    <input type="text" id="compte_comptable" name="compte_comptable" value="{{ old('compte_comptable') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('compte_comptable') border-red-500 @enderror">
                    @error('compte_comptable')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Code journal comptable --}}
                <div>
                    <label for="code_journal_comptable" class="mb-2 block text-sm font-medium text-[#1F2937]">Code journal comptable</label>
                    <input type="text" id="code_journal_comptable" name="code_journal_comptable" value="{{ old('code_journal_comptable') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('code_journal_comptable') border-red-500 @enderror">
                    @error('code_journal_comptable')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-center gap-4 pt-4">
                    <button type="submit" class="rounded-lg bg-purple-600 px-8 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition-colors">CRÉER COMPTE</button>
                    <a href="{{ route('banques.index') }}" class="rounded-lg bg-purple-400 px-8 py-3 text-sm font-semibold text-white hover:bg-purple-500 transition-colors">ANNULER</a>
                </div>
            </form>
        </div>
    </div>
@endsection
