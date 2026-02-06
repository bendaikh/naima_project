@extends('layouts.dashboard')

@section('title', 'Créer une facture')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Créer une facture</h1>
            <p class="text-sm text-slate-600 mt-1">Créer une facture</p>
        </div>

        <form method="POST" action="{{ route('factures.store') }}" class="space-y-6">
            @csrf

            <!-- Top Section: Account Type, Client, Facturation Type, Model -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label for="compte_type" class="block text-sm font-medium text-slate-700">Type de compte*</label>
                    <select name="compte_type" id="compte_type" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Sélectionner un type de compte</option>
                        <option value="client" {{ old('compte_type') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="fournisseur" {{ old('compte_type') == 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                    </select>
                    @error('compte_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-slate-700">Client*</label>
                    <select name="client_id" id="client_id" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Veuillez sélectionner</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $devis?->client_id) == $client->id ? 'selected' : '' }}>{{ $client->nom_raison_sociale }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type_facturation" class="block text-sm font-medium text-slate-700">Type de facturation*</label>
                    <select name="type_facturation" id="type_facturation" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="facture" {{ old('type_facturation') == 'facture' ? 'selected' : '' }}>Facture</option>
                        <option value="facture_simplifiee" {{ old('type_facturation') == 'facture_simplifiee' ? 'selected' : '' }}>Facture simplifiée</option>
                    </select>
                    @error('type_facturation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="modele" class="block text-sm font-medium text-slate-700">Modèle</label>
                    <input type="text" name="modele" id="modele" value="{{ old('modele') }}" placeholder="Hong Kong" class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <!-- Dates and Category Section -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label for="date_emission" class="block text-sm font-medium text-slate-700">Date d'émission*</label>
                    <input type="date" name="date_emission" id="date_emission" value="{{ old('date_emission', date('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('date_emission') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date_echeance" class="block text-sm font-medium text-slate-700">Date d'échéance*</label>
                    <input type="date" name="date_echeance" id="date_echeance" value="{{ old('date_echeance') }}" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('date_echeance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-slate-700">Catégorie*</label>
                    <select name="categorie" id="categorie" required class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Cat</option>
                        <option value="electronique" {{ old('categorie') == 'electronique' ? 'selected' : '' }}>Électronique</option>
                        <option value="electromenager" {{ old('categorie') == 'electromenager' ? 'selected' : '' }}>Électroménager</option>
                        <option value="informatique" {{ old('categorie') == 'informatique' ? 'selected' : '' }}>Informatique</option>
                    </select>
                    @error('categorie') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="numero_facture" class="block text-sm font-medium text-slate-700">Numéro de facture</label>
                    <input type="text" name="numero_facture" id="numero_facture" value="{{ old('numero_facture') }}" placeholder="#FACT0333" class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <!-- Articles Section -->
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Articles</h3>
                    <button type="button" id="addArticleBtn" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter un article
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-slate-200">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">TYPE D'ARTICLE</th>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">ARTICLES</th>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">QUANTITÉ</th>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">PRIX</th>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">REMISE</th>
                                <th class="text-left px-4 py-3 font-semibold text-slate-600">IMPÔT (%)</th>
                                <th class="text-right px-4 py-3 font-semibold text-slate-600">MONTANT</th>
                            </tr>
                        </thead>
                        <tbody id="articlesContainer" class="divide-y divide-slate-200">
                            <!-- Articles will be added here dynamically -->
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Aucun article ajouté</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description" id="description" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Détails supplémentaires..."></textarea>
                </div>

                <!-- Totals Section -->
                <div class="mt-6 space-y-3 border-t border-slate-200 pt-6 text-right text-sm">
                    <div class="flex justify-end gap-4">
                        <span class="text-slate-600">Sous-total (dhs):</span>
                        <span class="w-24 font-medium text-slate-800">0.00</span>
                    </div>
                    <div class="flex justify-end gap-4">
                        <span class="text-slate-600">Remise (dhs):</span>
                        <span class="w-24 font-medium text-slate-800">0.00</span>
                    </div>
                    <div class="flex justify-end gap-4">
                        <span class="text-slate-600">Impôt (dhs):</span>
                        <span class="w-24 font-medium text-slate-800">0.00</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-end gap-4 text-base font-bold">
                        <span class="text-slate-800">Montant total (dhs):</span>
                        <span class="w-24 text-slate-800">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('factures.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
                <button type="submit" class="rounded-lg bg-red-600 px-6 py-2 text-sm font-semibold text-white hover:bg-red-700">Créer</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('addArticleBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.getElementById('articlesContainer');
            if (container.querySelector('tr td[colspan="7"]')) {
                container.innerHTML = '';
            }
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="px-4 py-3">
                    <select name="article_type[]" class="w-full rounded border border-slate-300 px-2 py-1 text-sm">
                        <option value="">-</option>
                        <option value="product">Produit</option>
                        <option value="service">Service</option>
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="article_name[]" class="w-full rounded border border-slate-300 px-2 py-1 text-sm" placeholder="Nom article">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="quantity[]" class="w-full rounded border border-slate-300 px-2 py-1 text-sm" placeholder="Quantité" value="1">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="price[]" step="0.01" class="w-full rounded border border-slate-300 px-2 py-1 text-sm" placeholder="Prix">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="discount[]" step="0.01" class="w-full rounded border border-slate-300 px-2 py-1 text-sm" placeholder="Remise" value="0">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="tax[]" step="0.01" class="w-full rounded border border-slate-300 px-2 py-1 text-sm" placeholder="%" value="20">
                </td>
                <td class="text-right px-4 py-3">
                    <span class="font-medium dhs">0.00</span>
                </td>
            `;
            container.appendChild(newRow);
        });
    </script>
@endsection
