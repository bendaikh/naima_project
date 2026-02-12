@extends('layouts.dashboard')

@section('title', 'Modifier un article')

@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-semibold text-slate-800">Modifier un article</h1>
    
    <form method="POST" action="{{ route('articles.update', $article) }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="nom" class="block text-sm font-medium text-slate-700">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $article->nom) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="categorie_id" class="block text-sm font-medium text-slate-700">Catégorie *</label>
            <select name="categorie_id" id="categorie_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">-- Sélectionner une catégorie --</option>
                @foreach(\App\Models\Categorie::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description', $article->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="prix_achat" class="block text-sm font-medium text-slate-700">Prix d'achat *</label>
                <input type="number" name="prix_achat" id="prix_achat" value="{{ old('prix_achat', $article->prix_achat) }}" required step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('prix_achat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="prix_vente" class="block text-sm font-medium text-slate-700">Prix de vente *</label>
                <input type="number" name="prix_vente" id="prix_vente" value="{{ old('prix_vente', $article->prix_vente) }}" required step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('prix_vente') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="quantite" class="block text-sm font-medium text-slate-700">Quantité initiale *</label>
                <input type="number" name="quantite" id="quantite" value="{{ old('quantite', $article->quantite) }}" required step="1" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('quantite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="unite" class="block text-sm font-medium text-slate-700">Unité *</label>
                <select name="unite" id="unite" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Sélectionner une unité --</option>
                    <option value="pcs" {{ old('unite', $article->unite) === 'pcs' ? 'selected' : '' }}>Pièce (pcs)</option>
                    <option value="kg" {{ old('unite', $article->unite) === 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="g" {{ old('unite', $article->unite) === 'g' ? 'selected' : '' }}>Gramme (g)</option>
                    <option value="litre" {{ old('unite', $article->unite) === 'litre' ? 'selected' : '' }}>Litre (l)</option>
                    <option value="ml" {{ old('unite', $article->unite) === 'ml' ? 'selected' : '' }}>Millilitre (ml)</option>
                    <option value="m" {{ old('unite', $article->unite) === 'm' ? 'selected' : '' }}>Mètre (m)</option>
                    <option value="cm" {{ old('unite', $article->unite) === 'cm' ? 'selected' : '' }}>Centimètre (cm)</option>
                    <option value="m2" {{ old('unite', $article->unite) === 'm2' ? 'selected' : '' }}>Mètre carré (m²)</option>
                    <option value="m3" {{ old('unite', $article->unite) === 'm3' ? 'selected' : '' }}>Mètre cube (m³)</option>
                    <option value="box" {{ old('unite', $article->unite) === 'box' ? 'selected' : '' }}>Boîte (box)</option>
                    <option value="lot" {{ old('unite', $article->unite) === 'lot' ? 'selected' : '' }}>Lot</option>
                    <option value="hr" {{ old('unite', $article->unite) === 'hr' ? 'selected' : '' }}>Heure (hr)</option>
                </select>
                @error('unite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Stock Management Section -->
        <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 space-y-4">
            <h3 class="font-medium text-blue-900">Gestion du stock</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="quantite_stock" class="block text-sm font-medium text-slate-700">Stock actuel</label>
                    <div class="flex gap-2">
                        <input type="number" name="quantite_stock" id="quantite_stock" value="{{ old('quantite_stock', $article->quantite_stock) }}" step="1" min="0" class="mt-1 flex-1 rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    @error('quantite_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-600 mt-1">Ajusté automatiquement lors des livraisons/retours</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-slate-700 mb-2">État du stock</h4>
                    <div class="bg-white rounded-lg p-3 border border-slate-200">
                        <p class="text-2xl font-bold {{ $article->quantite_stock > $article->quantite * 0.5 ? 'text-green-600' : ($article->quantite_stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $article->quantite_stock }}
                        </p>
                        <p class="text-xs text-slate-600 mt-1">/ {{ $article->quantite }} initial</p>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($article->quantite_stock / max($article->quantite, 1)) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded border border-blue-200 p-3">
                <p class="text-xs text-slate-600">
                    <strong>Notes:</strong> Ce stock est géré automatiquement. Ne le modifier que si vous devez corriger une erreur ou faire un ajustement manuel d'inventaire.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="ugs" class="block text-sm font-medium text-slate-700">UGS</label>
                <input type="text" name="ugs" id="ugs" value="{{ old('ugs', $article->ugs) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('ugs') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="numero_facture" class="block text-sm font-medium text-slate-700">Numéro de facture</label>
                <input type="text" name="numero_facture" id="numero_facture" value="{{ old('numero_facture', $article->numero_facture) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('numero_facture') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="compte_revenu" class="block text-sm font-medium text-slate-700">Compte revenu</label>
                <input type="text" name="compte_revenu" id="compte_revenu" value="{{ old('compte_revenu', $article->compte_revenu) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('compte_revenu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="compte_depense" class="block text-sm font-medium text-slate-700">Compte dépense</label>
                <input type="text" name="compte_depense" id="compte_depense" value="{{ old('compte_depense', $article->compte_depense) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('compte_depense') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="entrepot" class="block text-sm font-medium text-slate-700">Entrepôt</label>
                <input type="text" name="entrepot" id="entrepot" value="{{ old('entrepot', $article->entrepot) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('entrepot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="impot" class="block text-sm font-medium text-slate-700">Impôt (%)</label>
                <input type="number" name="impot" id="impot" value="{{ old('impot', $article->impot) }}" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('impot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-slate-700">Image du produit</label>
            @if($article->image)
                <div class="mt-2 mb-4">
                    <p class="text-sm text-slate-600 mb-2">Image actuelle:</p>
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->nom }}" class="max-h-32 rounded-lg border border-slate-300">
                </div>
            @endif
            <div class="mt-2">
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                <p class="text-xs text-slate-600 mt-2">Formats acceptés: JPG, PNG, GIF (max 5 MB)</p>
            </div>
            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <div id="imagePreview" class="mt-4"></div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('articles.show', $article) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>

<script>
// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<div class="mt-4"><p class="text-sm text-slate-600 mb-2">Aperçu de la nouvelle image:</p><img src="${e.target.result}" class="max-h-48 rounded-lg border border-slate-300"></div>`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endsection
