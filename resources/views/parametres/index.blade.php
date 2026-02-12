@extends('layouts.dashboard')

@section('title', 'Paramètres')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-[#1F2937]">Paramètres</h1>

    @if(session('success'))
        <div class="rounded-lg bg-[#E8F5E9] p-4 text-sm text-[#2E7D32] border-l-4 border-[#4CAF50]">{{ session('success') }}</div>
    @endif

    <!-- Company Information Section -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-4">
        <h2 class="text-lg font-semibold text-[#1F2937]">Informations de l'entreprise</h2>
        
        <form method="post" action="{{ route('parametres.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="nom" class="block text-sm font-medium text-[#374151]">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $params->nom) }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>

            <div>
                <label for="adresse" class="block text-sm font-medium text-[#374151]">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('adresse', $params->adresse) }}</textarea>
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

            <hr class="my-4 border-[#E5E7EB]">

            <h3 class="font-semibold text-[#1F2937]">TVA et numérotation</h3>

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

            <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">Enregistrer</button>
        </form>
    </div>

    <!-- Categories Management Section -->
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-6">
        <h2 class="text-lg font-semibold text-[#1F2937]">Gestion des catégories d'articles</h2>
        
        <!-- Add Category Form -->
        <div class="space-y-4 p-4 bg-[#F9FAFB] rounded-lg border border-[#E5E7EB]">
            <h3 class="font-semibold text-[#374151]">Ajouter une catégorie</h3>
            <form method="post" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="cat_nom" class="block text-sm font-medium text-[#374151]">Nom de la catégorie *</label>
                    <input type="text" name="nom" id="cat_nom" placeholder="Ex: Électronique, Vêtements, etc." required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cat_description" class="block text-sm font-medium text-[#374151]">Description (optionnel)</label>
                    <textarea name="description" id="cat_description" rows="2" placeholder="Description de la catégorie..." class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]"></textarea>
                </div>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Ajouter</button>
            </form>
        </div>

        <!-- Categories List -->
        @if($categories->isEmpty())
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="mt-4 text-[#6B7280]">Aucune catégorie créée. Commencez par en ajouter une ci-dessus.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#E5E7EB]">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-[#374151]">Description</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-[#374151]">Articles</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-[#374151]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @foreach($categories as $categorie)
                            <tr class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $categorie->nom }}</td>
                                <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $categorie->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-[#1F2937]">
                                    <span class="inline-flex items-center justify-center rounded-full bg-[#DBEAFE] px-3 py-1 text-xs font-semibold text-[#1E40AF]">
                                        {{ $categorie->articles->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" onclick="editCategory({{ $categorie->id }}, '{{ addslashes($categorie->nom) }}', '{{ addslashes($categorie->description ?? '') }}')" class="inline-flex items-center justify-center rounded-lg bg-amber-500 w-8 h-8 text-white hover:bg-amber-600 transition-colors" title="Éditer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('categories.destroy', $categorie->id) }}" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr ? Les articles avec cette catégorie ne seront pas supprimés.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-500 w-8 h-8 text-white hover:bg-red-600 transition-colors" title="Supprimer">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal for editing category -->
<div id="editModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold text-[#1F2937] mb-4">Éditer la catégorie</h3>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="editNom" class="block text-sm font-medium text-[#374151]">Nom</label>
                <input type="text" name="nom" id="editNom" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label for="editDescription" class="block text-sm font-medium text-[#374151]">Description</label>
                <textarea name="description" id="editDescription" rows="2" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]"></textarea>
            </div>
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" onclick="closeEditModal()" class="rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB] transition-colors">Annuler</button>
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, nom, description) {
    document.getElementById('editNom').value = nom;
    document.getElementById('editDescription').value = description;
    document.getElementById('editForm').action = '{{ route("categories.update", ":id") }}'.replace(':id', id);
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>
@endsection

