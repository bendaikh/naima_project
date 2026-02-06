@extends('layouts.dashboard')

@section('title', 'Créer un article')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Création d'un article</h1>
        <div class="mt-2 flex items-center gap-2 text-sm text-slate-600">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Tableau de bord</a>
            <span>›</span>
            <span>Articles</span>
        </div>
    </div>

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
    <div class="flex gap-2 border-b border-slate-200 bg-white rounded-t-lg">
        <button type="button" id="tab-details" class="tab-button active px-6 py-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600">
            Détails
        </button>
        <button type="button" id="tab-tarifs" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800">
            Tarifs
        </button>
        <button type="button" id="tab-medias" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800">
            Médias
        </button>
        <button type="button" id="tab-entrepot" class="tab-button px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-600 hover:text-slate-800">
            Détails de l'entrepôt
        </button>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-8 shadow">
        @csrf

        <!-- DETAILS TAB -->
        <div id="content-details" class="tab-content space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Type de compte<span class="text-red-600">*</span></label>
                    <select name="type_compte" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Sélectionnez le type de compte</option>
                        <option value="client" {{ old('type_compte') === 'client' ? 'selected' : '' }}>Client</option>
                        <option value="fournisseur" {{ old('type_compte') === 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                        <option value="general" {{ old('type_compte') === 'general' ? 'selected' : '' }}>Compte Général</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Client<span class="text-red-600">*</span></label>
                    <select name="client_id" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Veuillez sélectionner</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name ?? $client->nom_raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Type de facturation<span class="text-red-600">*</span></label>
                    <select name="type_facturation" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Modèle</option>
                        <option value="standard" {{ old('type_facturation') === 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="hk" {{ old('type_facturation') === 'hk' ? 'selected' : '' }}>Hong Kong</option>
                        <option value="international" {{ old('type_facturation') === 'international' ? 'selected' : '' }}>International</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catégorie<span class="text-red-600">*</span></label>
                    <select name="categorie" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Cat</option>
                        <option value="electronique" {{ old('categorie') === 'electronique' ? 'selected' : '' }}>Électronique</option>
                        <option value="materiel" {{ old('categorie') === 'materiel' ? 'selected' : '' }}>Matériel</option>
                        <option value="logiciel" {{ old('categorie') === 'logiciel' ? 'selected' : '' }}>Logiciel</option>
                        <option value="service" {{ old('categorie') === 'service' ? 'selected' : '' }}>Service</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date d'émission<span class="text-red-600">*</span></label>
                    <input type="date" name="date_emission" value="{{ old('date_emission', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date d'échéance<span class="text-red-600">*</span></label>
                    <input type="date" name="date_echeance" value="{{ old('date_echeance', now()->addDays(30)->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro de facture</label>
                <input type="text" name="numero_facture" value="{{ old('numero_facture', '#FACT' . str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT)) }}" readonly class="w-full rounded-lg border border-slate-300 bg-slate-100 px-4 py-2 text-slate-600">
            </div>

            <div class="space-y-4 rounded-lg bg-slate-50 p-4">
                <h3 class="font-semibold text-slate-800">Articles</h3>
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-slate-600">Type d'article</th>
                            <th class="px-3 py-2 text-left font-medium text-slate-600">Articles</th>
                            <th class="px-3 py-2 text-right font-medium text-slate-600">Quantité</th>
                            <th class="px-3 py-2 text-right font-medium text-slate-600">Prix</th>
                            <th class="px-3 py-2 text-right font-medium text-slate-600">Remise</th>
                            <th class="px-3 py-2 text-right font-medium text-slate-600">Impôt (%)</th>
                            <th class="px-3 py-2 text-right font-medium text-slate-600">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="articles-empty" class="text-center text-slate-500">
                            <td colspan="7" class="px-3 py-8">Aucun article ajouté</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between pt-4 border-t border-slate-200">
                    <div class="space-y-2">
                        <div class="flex justify-between gap-8">
                            <span class="text-slate-600">Prix total</span>
                            <span class="font-semibold text-slate-900">-- --</span>
                        </div>
                        <div class="flex justify-between gap-8">
                            <span class="text-slate-600">Quantité</span>
                            <span class="font-semibold text-slate-900" id="total-qty">0</span>
                        </div>
                        <div class="flex justify-between gap-8">
                            <span class="text-slate-600">Prix</span>
                            <span class="font-semibold text-slate-900" id="total-price">0 dhs</span>
                        </div>
                        <div class="flex justify-between gap-8">
                            <span class="text-slate-600">Remise</span>
                            <span class="font-semibold text-slate-900" id="total-discount">0.00 dhs</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- TARIFS TAB -->
        <div id="content-tarifs" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prix de vente<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="prix_vente" value="{{ old('prix_vente') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prix d'achat<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="prix_achat" value="{{ old('prix_achat') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Compte de revenu</label>
                    <select name="compte_revenu" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select Account</option>
                        <option value="4111" {{ old('compte_revenu') === '4111' ? 'selected' : '' }}>4111 - Ventes</option>
                        <option value="4112" {{ old('compte_revenu') === '4112' ? 'selected' : '' }}>4112 - Services</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Compte de dépenses</label>
                    <select name="compte_depense" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select Account</option>
                        <option value="6011" {{ old('compte_depense') === '6011' ? 'selected' : '' }}>6011 - Achats</option>
                        <option value="6012" {{ old('compte_depense') === '6012' ? 'selected' : '' }}>6012 - Services</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Unité<span class="text-red-600">*</span></label>
                    <select name="unite" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">DH</option>
                        <option value="dh" {{ old('unite') === 'dh' ? 'selected' : '' }}>DH</option>
                        <option value="piece" {{ old('unite') === 'piece' ? 'selected' : '' }}>Pièce</option>
                        <option value="kg" {{ old('unite') === 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="m" {{ old('unite') === 'm' ? 'selected' : '' }}>Mètre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Quantité<span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="quantite" value="{{ old('quantite', 0) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- MEDIAS TAB -->
        <div id="content-medias" class="tab-content hidden space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-4">Image</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 cursor-pointer hover:bg-slate-50">
                        <span class="text-sm font-medium text-slate-700">Choose File</span>
                        <input type="file" name="image" accept="image/*" class="hidden">
                    </label>
                    <span class="text-sm text-slate-500" id="file-name">No file chosen</span>
                </div>
                <div class="mt-6">
                    <div id="image-preview" class="inline-block rounded-lg border-2 border-dashed border-slate-300 p-6">
                        <svg class="h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- ENTREPOT TAB -->
        <div id="content-entrepot" class="tab-content hidden space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Entrepôt</label>
                <select name="entrepot" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">Select Warehouse</option>
                    <option value="principal" {{ old('entrepot') === 'principal' ? 'selected' : '' }}>Entrepôt Principal</option>
                    <option value="secondaire" {{ old('entrepot') === 'secondaire' ? 'selected' : '' }}>Entrepôt Secondaire</option>
                </select>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex gap-4 border-t border-slate-200 pt-6">
            <button type="button" id="btn-previous" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <span>‹</span>
                <span>Précédent</span>
            </button>

            <button type="button" id="btn-next" class="ml-auto inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <span>Suivant</span>
                <span>›</span>
            </button>

            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2 text-sm font-semibold text-white hover:bg-red-700">
                <span>Soumettre</span>
            </button>
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
}

// File input handler
document.querySelector('input[name="image"]').addEventListener('change', (e) => {
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
