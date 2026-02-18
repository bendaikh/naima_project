@extends('layouts.dashboard')

@section('title', 'Créer un article')

@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-semibold text-slate-800">Créer un article</h1>
    
    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf

        <div>
            <label for="nom" class="block text-sm font-medium text-slate-700">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="categorie_id" class="block text-sm font-medium text-slate-700">Catégorie *</label>
            <select name="categorie_id" id="categorie_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">-- Sélectionner une catégorie --</option>
                @foreach(\App\Models\Categorie::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="prix_achat" class="block text-sm font-medium text-slate-700">Prix d'achat *</label>
                <input type="number" name="prix_achat" id="prix_achat" value="{{ old('prix_achat') }}" required step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('prix_achat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="prix_vente" class="block text-sm font-medium text-slate-700">Prix de vente *</label>
                <input type="number" name="prix_vente" id="prix_vente" value="{{ old('prix_vente') }}" required step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('prix_vente') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="quantite" class="block text-sm font-medium text-slate-700">Quantité initiale *</label>
                <input type="number" name="quantite" id="quantite" value="{{ old('quantite') }}" required step="1" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('quantite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="unite" class="block text-sm font-medium text-slate-700">Unité *</label>
                <select name="unite" id="unite" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Sélectionner une unité --</option>
                    <option value="pcs" {{ old('unite', 'pcs') === 'pcs' ? 'selected' : '' }}>Pièce (pcs)</option>
                    <option value="kg" {{ old('unite') === 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="g" {{ old('unite') === 'g' ? 'selected' : '' }}>Gramme (g)</option>
                    <option value="litre" {{ old('unite') === 'litre' ? 'selected' : '' }}>Litre (l)</option>
                    <option value="ml" {{ old('unite') === 'ml' ? 'selected' : '' }}>Millilitre (ml)</option>
                    <option value="m" {{ old('unite') === 'm' ? 'selected' : '' }}>Mètre (m)</option>
                    <option value="cm" {{ old('unite') === 'cm' ? 'selected' : '' }}>Centimètre (cm)</option>
                    <option value="m2" {{ old('unite') === 'm2' ? 'selected' : '' }}>Mètre carré (m²)</option>
                    <option value="m3" {{ old('unite') === 'm3' ? 'selected' : '' }}>Mètre cube (m³)</option>
                    <option value="box" {{ old('unite') === 'box' ? 'selected' : '' }}>Boîte (box)</option>
                    <option value="lot" {{ old('unite') === 'lot' ? 'selected' : '' }}>Lot</option>
                    <option value="hr" {{ old('unite') === 'hr' ? 'selected' : '' }}>Heure (hr)</option>
                </select>
                @error('unite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Stock Management Section -->
        <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 space-y-4">
            <h3 class="font-medium text-blue-900">Gestion du stock</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="quantite_stock" class="block text-sm font-medium text-slate-700">Stock initial *</label>
                    <input type="number" name="quantite_stock" id="quantite_stock" value="{{ old('quantite_stock') }}" step="1" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('quantite_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-600 mt-1">La quantité initiale que vous avez en stock</p>
                </div>

                <div class="flex flex-col justify-end">
                    <button type="button" onclick="syncStock()" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                        Synchroniser avec la quantité
                    </button>
                    <p class="text-xs text-slate-600 mt-2">Copie la valeur de "Quantité initiale" ci-dessus</p>
                </div>
            </div>

            <div class="bg-white rounded border border-blue-200 p-3">
                <p class="text-xs text-slate-600">
                    <strong>Comment ça marche:</strong> Le stock initial définit la quantité actuelle disponible. Lors des livraisons et retours, ce stock sera automatiquement ajusté.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="ugs" class="block text-sm font-medium text-slate-700">UGS</label>
                <input type="text" name="ugs" id="ugs" value="{{ old('ugs') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('ugs') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="numero_facture" class="block text-sm font-medium text-slate-700">Numéro de facture</label>
                <input type="text" name="numero_facture" id="numero_facture" value="{{ old('numero_facture') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('numero_facture') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="compte_revenu" class="block text-sm font-medium text-slate-700">Compte revenu</label>
                <input type="text" name="compte_revenu" id="compte_revenu" value="{{ old('compte_revenu') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('compte_revenu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="compte_depense" class="block text-sm font-medium text-slate-700">Compte dépense</label>
                <input type="text" name="compte_depense" id="compte_depense" value="{{ old('compte_depense') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('compte_depense') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="entrepot" class="block text-sm font-medium text-slate-700">Entrepôt</label>
                <input type="text" name="entrepot" id="entrepot" value="{{ old('entrepot') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('entrepot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="impot" class="block text-sm font-medium text-slate-700">Impôt (%)</label>
                <input type="number" name="impot" id="impot" value="{{ old('impot') }}" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('impot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-slate-700">Image du produit</label>
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
            <div class="mt-3 flex items-center gap-3">
                <button type="button" id="clearImageBtn" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100" disabled>
                    Supprimer l'image sélectionnée
                </button>
                <span id="clearImageHint" class="text-xs text-slate-500"></span>
            </div>
            <div id="imagePreview" class="mt-4"></div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Créer l'article</button>
            <a href="{{ route('articles.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>

<script>
function syncStock() {
    const quantite = document.getElementById('quantite').value;
    const quantiteStock = document.getElementById('quantite_stock');
    
    if (quantite) {
        quantiteStock.value = quantite;
    } else {
        alert('Veuillez d\'abord entrer la quantité initiale');
    }
}

// Auto-sync stock when quantite changes
document.getElementById('quantite').addEventListener('change', function() {
    if (document.getElementById('quantite_stock').value === '') {
        document.getElementById('quantite_stock').value = this.value;
    }
});

const imageInput = document.getElementById('image');
const clearImageBtn = document.getElementById('clearImageBtn');
const clearImageHint = document.getElementById('clearImageHint');

function resetSelectedImage() {
    imageInput.value = '';
    document.getElementById('imagePreview').innerHTML = '';
    clearImageBtn.setAttribute('disabled', 'disabled');
    clearImageHint.textContent = '';
}

clearImageBtn.addEventListener('click', function() {
    resetSelectedImage();
});

// Image preview
imageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        clearImageBtn.removeAttribute('disabled');
        clearImageHint.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<div class="mt-4"><p class="text-sm text-slate-600 mb-2">Aperçu:</p><img src="${e.target.result}" class="max-h-48 rounded-lg border border-slate-300"></div>`;
        };
        reader.readAsDataURL(file);
    } else {
        resetSelectedImage();
    }
});
</script>
@endsection
