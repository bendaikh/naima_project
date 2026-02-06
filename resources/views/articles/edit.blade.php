@extends('layouts.dashboard')

@section('title', 'Modifier - Article')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Modifier l'article</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4">
            <ul class="list-inside list-disc text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="flex gap-2 border-b border-slate-200 bg-white rounded-t-lg overflow-x-auto">
        <button type="button" id="tab-details" class="tab-button active px-6 py-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
            Détails
        </button>
        <button type="button" id="tab-tarifs" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800 whitespace-nowrap">
            Tarifs
        </button>
        <button type="button" id="tab-medias" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800 whitespace-nowrap">
            Médias
        </button>
        <button type="button" id="tab-entrepot" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800 whitespace-nowrap">
            Détails de l'entrepôt
        </button>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('articles.update', $article) }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-8 shadow">
        @csrf @method('PUT')

        <!-- DETAILS TAB -->
        <div id="content-details" class="tab-content space-y-6">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom<span class="text-red-600">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $article->nom) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <!-- UGS & Generate Button -->
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">UGS<span class="text-red-600">*</span></label>
                    <input type="text" name="ugs" value="{{ old('ugs', $article->ugs) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="button" id="generateUgs" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Générer</button>
                </div>
            </div>

            <!-- Impôt -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Impôt<span class="text-red-600">*</span></label>
                <select name="impot" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">Please Select</option>
                    <option value="5" {{ old('impot', $article->impot) == '5' ? 'selected' : '' }}>5%</option>
                    <option value="10" {{ old('impot', $article->impot) == '10' ? 'selected' : '' }}>10%</option>
                    <option value="20" {{ old('impot', $article->impot) == '20' ? 'selected' : '' }}>20%</option>
                </select>
            </div>

            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Catégorie<span class="text-red-600">*</span></label>
                <div class="flex gap-2">
                    <select name="categorie" required class="flex-1 rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Rail Magnetique</option>
                        <option value="electronique" {{ old('categorie', $article->categorie) === 'electronique' ? 'selected' : '' }}>Électronique</option>
                        <option value="materiel" {{ old('categorie', $article->categorie) === 'materiel' ? 'selected' : '' }}>Matériel</option>
                        <option value="logiciel" {{ old('categorie', $article->categorie) === 'logiciel' ? 'selected' : '' }}>Logiciel</option>
                        <option value="service" {{ old('categorie', $article->categorie) === 'service' ? 'selected' : '' }}>Service</option>
                    </select>
                    <button type="button" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">+</button>
                </div>
                <p class="mt-1 text-xs text-slate-500">Veuillez ajouter une catégorie constante. <a href="#" class="text-blue-600 hover:underline">Ajouter une catégorie</a></p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description', $article->description) }}</textarea>
            </div>
        </div>

        <!-- TARIFS TAB -->
        <div id="content-tarifs" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prix de vente<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="prix_vente" value="{{ $article->prix_vente }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prix d'achat<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="prix_achat" value="{{ $article->prix_achat }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Compte de revenu</label>
                    <select name="compte_revenu" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select Account</option>
                        <option value="4111" {{ $article->compte_revenu === '4111' ? 'selected' : '' }}>4111 - Ventes</option>
                        <option value="4112" {{ $article->compte_revenu === '4112' ? 'selected' : '' }}>4112 - Services</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Compte de dépenses</label>
                    <select name="compte_depense" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select Account</option>
                        <option value="6011" {{ $article->compte_depense === '6011' ? 'selected' : '' }}>6011 - Achats</option>
                        <option value="6012" {{ $article->compte_depense === '6012' ? 'selected' : '' }}>6012 - Services</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Unité<span class="text-red-600">*</span></label>
                    <select name="unite" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="dh" {{ $article->unite === 'dh' ? 'selected' : '' }}>DH</option>
                        <option value="piece" {{ $article->unite === 'piece' ? 'selected' : '' }}>Pièce</option>
                        <option value="kg" {{ $article->unite === 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="m" {{ $article->unite === 'm' ? 'selected' : '' }}>Mètre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Quantité<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="quantite" value="{{ $article->quantite }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- MEDIAS TAB -->
        <div id="content-medias" class="tab-content hidden space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-4">Image</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 cursor-pointer hover:bg-slate-50">
                        <span class="text-sm font-medium text-slate-700">Choisir un fichier</span>
                        <input type="file" name="image_path" accept="image/*" class="hidden">
                    </label>
                    <span class="text-sm text-slate-500" id="file-name">{{ $article->image_path ? 'Image actuelle' : 'Aucun fichier' }}</span>
                </div>
                <div class="mt-6">
                    <div id="image-preview" class="inline-block rounded-lg border-2 border-dashed border-slate-300 p-6 bg-slate-50">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" class="h-32 w-32 rounded object-cover">
                        @else
                            <svg class="h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ENTREPOT TAB -->
        <div id="content-entrepot" class="tab-content hidden space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Entrepôt</label>
                <select name="entrepot" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">Sélectionner un entrepôt...</option>
                    <option value="principal" {{ old('entrepot', $article->entrepot) === 'principal' ? 'selected' : '' }}>Entrepôt Principal</option>
                    <option value="secondaire" {{ old('entrepot', $article->entrepot) === 'secondaire' ? 'selected' : '' }}>Entrepôt Secondaire</option>
                    <option value="zone_a" {{ old('entrepot', $article->entrepot) === 'zone_a' ? 'selected' : '' }}>Zone A</option>
                    <option value="zone_b" {{ old('entrepot', $article->entrepot) === 'zone_b' ? 'selected' : '' }}>Zone B</option>
                </select>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex gap-4 border-t border-slate-200 pt-6">
            <button type="button" id="btn-previous" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" style="display: none;">
                <span>‹</span>
                <span>Précédent</span>
            </button>

            <button type="button" id="btn-next" class="ml-auto inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <span>Suivant</span>
                <span>›</span>
            </button>

            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700" style="display: none;" id="btn-submit">
                <span>Mettre à jour</span>
            </button>

            <a href="{{ route('articles.show', $article) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>

<script>
const tabs = ['details', 'tarifs', 'medias', 'entrepot'];
let currentTab = 0;

// Tab switching
document.querySelectorAll('.tab-button').forEach((btn, idx) => {
    btn.addEventListener('click', () => goToTab(idx));
});

document.getElementById('btn-next').addEventListener('click', () => {
    if (currentTab < tabs.length - 1) goToTab(currentTab + 1);
});

document.getElementById('btn-previous').addEventListener('click', () => {
    if (currentTab > 0) goToTab(currentTab - 1);
});

function goToTab(idx) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-blue-600', 'text-blue-600');
        el.classList.add('border-transparent', 'text-slate-600');
    });

    // Show current tab
    currentTab = idx;
    document.getElementById(`content-${tabs[idx]}`).classList.remove('hidden');
    document.getElementById(`tab-${tabs[idx]}`).classList.add('border-blue-600', 'text-blue-600');
    document.getElementById(`tab-${tabs[idx]}`).classList.remove('border-transparent', 'text-slate-600');

    // Update button visibility
    document.getElementById('btn-previous').style.display = currentTab === 0 ? 'none' : 'inline-flex';
    document.getElementById('btn-next').style.display = currentTab === tabs.length - 1 ? 'none' : 'inline-flex';
    document.getElementById('btn-submit').style.display = currentTab === tabs.length - 1 ? 'inline-flex' : 'none';
}

// File input handler
document.querySelector('input[name="image_path"]').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('file-name').textContent = file.name;
        const reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById('image-preview').innerHTML = `<img src="${event.target.result}" class="h-32 w-32 rounded object-cover">`;
        };
        reader.readAsDataURL(file);
    }
});

// Initialize
goToTab(0);
</script>
@endsection
