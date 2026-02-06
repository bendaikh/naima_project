@extends('layouts.dashboard')

@section('title', 'Créer un article')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Créer un article</h1>
    </div>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 mb-6 text-red-700">
            <p class="font-semibold mb-2">Erreurs détectées:</p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" id="articleForm" class="space-y-6">
        @csrf

        <!-- Tab Navigation -->
        <div class="flex gap-2 border-b border-slate-200">
            <button type="button" class="tab-button active px-4 py-3 text-sm font-medium text-slate-700 border-b-2 border-blue-600" data-tab="details">
                Détails
            </button>
            <button type="button" class="tab-button px-4 py-3 text-sm font-medium text-slate-700 border-b-2 border-transparent hover:border-slate-300" data-tab="tarifs">
                Tarifs
            </button>
            <button type="button" class="tab-button px-4 py-3 text-sm font-medium text-slate-700 border-b-2 border-transparent hover:border-slate-300" data-tab="medias">
                Médias
            </button>
            <button type="button" class="tab-button px-4 py-3 text-sm font-medium text-slate-700 border-b-2 border-transparent hover:border-slate-300" data-tab="entrepot">
                Détails de l'entrepôt
            </button>
        </div>

        <!-- Tab Content -->
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">

            <!-- Détails Tab -->
            <div id="tab-details" class="tab-content space-y-4">
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('nom') border-red-500 @enderror" placeholder="Nom de l'article" />
                    @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- UGS & Generate Button -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700">UGS *</label>
                        <input type="text" name="ugs" value="{{ old('ugs') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('ugs') border-red-500 @enderror" placeholder="Code UGS" />
                        @error('ugs') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="generateUgs" class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">Générer</button>
                    </div>
                </div>

                <!-- Impôt -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Impôt *</label>
                    <select name="impot" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('impot') border-red-500 @enderror">
                        <option value="">Please Select</option>
                        <option value="5" {{ old('impot') == '5' ? 'selected' : '' }}>5%</option>
                        <option value="10" {{ old('impot') == '10' ? 'selected' : '' }}>10%</option>
                        <option value="20" {{ old('impot') == '20' ? 'selected' : '' }}>20%</option>
                    </select>
                    @error('impot') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Catégorie -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Catégorie *</label>
                    <div class="flex gap-2">
                        <select name="categorie" required class="mt-1 flex-1 rounded-lg border border-slate-300 px-3 py-2 @error('categorie') border-red-500 @enderror">
                            <option value="">Rail Magnetique</option>
                            <option value="electronique" {{ old('categorie') == 'electronique' ? 'selected' : '' }}>Électronique</option>
                            <option value="materiel" {{ old('categorie') == 'materiel' ? 'selected' : '' }}>Matériel</option>
                            <option value="logiciel" {{ old('categorie') == 'logiciel' ? 'selected' : '' }}>Logiciel</option>
                            <option value="service" {{ old('categorie') == 'service' ? 'selected' : '' }}>Service</option>
                        </select>
                        <button type="button" class="mt-1 rounded-lg bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">+</button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Veuillez ajouter une catégorie constante. <a href="#" class="text-blue-600 hover:underline">Ajouter une catégorie</a></p>
                    @error('categorie') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('description') border-red-500 @enderror" placeholder="Description de l'article"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tarifs Tab -->
            <div id="tab-tarifs" class="tab-content space-y-4 hidden">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Prix de vente (DH)</label>
                        <input type="number" step="0.01" name="prix_vente" value="{{ old('prix_vente') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('prix_vente') border-red-500 @enderror" />
                        @error('prix_vente') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Prix d'achat (DH)</label>
                        <input type="number" step="0.01" name="prix_achat" value="{{ old('prix_achat') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('prix_achat') border-red-500 @enderror" />
                        @error('prix_achat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Compte revenu</label>
                        <input type="text" name="compte_revenu" value="{{ old('compte_revenu') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('compte_revenu') border-red-500 @enderror" placeholder="ex: 701000" />
                        @error('compte_revenu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Compte dépense</label>
                        <input type="text" name="compte_depense" value="{{ old('compte_depense') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('compte_depense') border-red-500 @enderror" placeholder="ex: 601000" />
                        @error('compte_depense') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Unité</label>
                        <select name="unite" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('unite') border-red-500 @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="dh" {{ old('unite') == 'dh' ? 'selected' : '' }}>DH</option>
                            <option value="piece" {{ old('unite') == 'piece' ? 'selected' : '' }}>Pièce</option>
                            <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>KG</option>
                            <option value="m" {{ old('unite') == 'm' ? 'selected' : '' }}>M</option>
                        </select>
                        @error('unite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Quantité</label>
                        <input type="number" step="0.01" name="quantite" value="{{ old('quantite') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('quantite') border-red-500 @enderror" />
                        @error('quantite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Médias Tab -->
            <div id="tab-medias" class="tab-content space-y-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Image</label>
                    <div class="mt-1 flex items-center gap-4">
                        <div class="flex-1">
                            <input type="file" name="image_path" id="imageInput" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            @error('image_path') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4"></div>
                </div>
            </div>

            <!-- Entrepôt Tab -->
            <div id="tab-entrepot" class="tab-content space-y-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Entrepôt</label>
                    <select name="entrepot" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 @error('entrepot') border-red-500 @enderror">
                        <option value="">Sélectionner un entrepôt...</option>
                        <option value="principal" {{ old('entrepot') == 'principal' ? 'selected' : '' }}>Entrepôt Principal</option>
                        <option value="secondaire" {{ old('entrepot') == 'secondaire' ? 'selected' : '' }}>Entrepôt Secondaire</option>
                        <option value="zone_a" {{ old('entrepot') == 'zone_a' ? 'selected' : '' }}>Zone A</option>
                        <option value="zone_b" {{ old('entrepot') == 'zone_b' ? 'selected' : '' }}>Zone B</option>
                    </select>
                    @error('entrepot') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('articles.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
            <div class="flex gap-2">
                <button type="button" id="prevBtn" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hidden">Précédent</button>
                <button type="button" id="nextBtn" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Suivant</button>
                <button type="submit" id="submitBtn" class="rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 hidden">Créer</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = ['details', 'tarifs', 'medias', 'entrepot'];
    let currentTab = 0;

    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    function showTab(index) {
        currentTab = Math.max(0, Math.min(index, tabs.length - 1));

        tabButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === currentTab);
            btn.classList.toggle('border-blue-600', i === currentTab);
            btn.classList.toggle('border-transparent', i !== currentTab);
            btn.classList.toggle('text-blue-600', i === currentTab);
            btn.classList.toggle('text-slate-700', i !== currentTab);
        });

        tabContents.forEach((content, i) => {
            content.classList.toggle('hidden', i !== currentTab);
        });

        prevBtn.classList.toggle('hidden', currentTab === 0);
        nextBtn.classList.toggle('hidden', currentTab === tabs.length - 1);
        submitBtn.classList.toggle('hidden', currentTab !== tabs.length - 1);
    }

    tabButtons.forEach((btn, index) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            showTab(index);
        });
    });

    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showTab(currentTab + 1);
    });

    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showTab(currentTab - 1);
    });

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.innerHTML = `<img src="${event.target.result}" class="h-32 w-32 rounded-lg object-cover border border-slate-200" />`;
            };
            reader.readAsDataURL(file);
        }
    });

    showTab(0);
});
</script>
@endsection
