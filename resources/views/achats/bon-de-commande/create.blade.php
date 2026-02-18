@extends('layouts.dashboard')
@section('title', 'Créer un Bon de Commande')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-[#1F2937]">Créer un Bon de Commande</h1>
        <a href="{{ route('achats.bon-de-commande.index') }}" class="text-[#6B7280] hover:text-[#1F2937]">← Retour</a>
    </div>

    <form action="{{ route('achats.bon-de-commande.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-6">
        @csrf

        @if(session('error'))
            <div class="rounded-lg bg-[#FEE2E2] p-4 text-sm text-[#991B1B] border-l-4 border-[#DC2626]">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg bg-[#FEE2E2] p-4 text-sm text-[#991B1B] border-l-4 border-[#DC2626]">
                <p class="font-semibold mb-2">Veuillez corriger les erreurs suivantes:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section: Informations Générales -->
        <div class="space-y-4 border-b border-[#E5E7EB] pb-4">
            <h3 class="font-semibold text-[#1F2937]">Informations Générales</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="fournisseur_id" class="block text-sm font-medium text-[#374151] mb-2">Fournisseur *</label>
                    <select id="fournisseur_id" name="fournisseur_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">-- Sélectionner un fournisseur --</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('fournisseur_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order_date" class="block text-sm font-medium text-[#374151] mb-2">Date de Commande *</label>
                    <input type="date" id="order_date" name="order_date" required value="{{ old('order_date', date('Y-m-d')) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('order_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expected_delivery_date" class="block text-sm font-medium text-[#374151] mb-2">Date de Livraison Prévue</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    @error('expected_delivery_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-[#374151] mb-2">Statut *</label>
                    <select id="status" name="status" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="DRAFT" {{ old('status') == 'DRAFT' ? 'selected' : '' }}>Brouillon</option>
                        <option value="CONFIRMED" {{ old('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmée</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-[#374151] mb-2">Remarques</label>
                <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Section: Articles -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-[#1F2937]">Articles</h3>
                <button type="button" id="add-ligne" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors">
                    + Ajouter une ligne
                </button>
            </div>

            <div id="lignes-container" class="space-y-4">
                <div class="rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4 ligne-item">
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Article Image -->
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-[#374151] mb-2">Photo</label>
                            <div class="h-24 w-24 rounded-lg border border-[#E5E7EB] bg-white overflow-hidden flex items-center justify-center article-image cursor-pointer hover:bg-[#F9FAFB]" onclick="this.parentElement.querySelector('input[type=file]').click()">
                                <svg class="h-8 w-8 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m-6-6H6m0 0H0"/>
                                </svg>
                            </div>
                            <input type="file" name="lignes[0][image]" accept="image/*" class="hidden image-file-input">
                            <p class="text-xs text-[#6B7280] mt-1">Cliquer pour ajouter une photo</p>
                        </div>

                        <!-- Article Selection -->
                        <div class="col-span-10 space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Article *</label>
                                    <select name="lignes[0][article_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] article-select">
                                        <option value="">-- Sélectionner ou créer --</option>
                                        @foreach($articles as $article)
                                            <option value="{{ $article->id }}"
                                                data-nom="{{ $article->nom }}"
                                                data-prix="{{ $article->prix_achat ?? 0 }}"
                                                data-image="{{ $article->image ?? '' }}"
                                                data-categorie-id="{{ $article->categorie_id ?? '' }}">
                                                {{ $article->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lignes.0.article_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Nom du Produit (nouveau)</label>
                                    <input type="text" name="lignes[0][product_name]" placeholder="Ex: Nouveau Produit" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] product-name-input">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Catégorie (nouveau)</label>
                                    <select name="lignes[0][categorie_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] categorie-select">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Quantité *</label>
                                    <input type="number" name="lignes[0][quantity]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] quantity-input">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Prix d'Achat *</label>
                                    <input type="number" name="lignes[0][purchase_price]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] price-input">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#374151] mb-1">Total Ligne</label>
                                    <div class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm font-semibold text-[#1860E1] bg-[#FFFFFF]">
                                        <span class="ligne-total">0.00</span> DH
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-span-1 flex items-end justify-center pb-1">
                            <button type="button" class="remove-ligne rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors" title="Supprimer cette ligne">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="rounded-lg border border-[#E5E7EB] bg-gradient-to-r from-[#F0FEFF] to-[#F3F4F6] p-4 space-y-2">
            <div class="flex items-center justify-between text-lg font-semibold text-[#1F2937]">
                <span>Total Commande:</span>
                <span id="order-total" class="text-[#1860E1]">0.00</span>
                <span class="text-[#1860E1]">DH</span>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" id="submit-btn" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                Créer le Bon de Commande
            </button>
            <a href="{{ route('achats.bon-de-commande.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
let ligneIndex = 1;

// Handle file selection
function handleFileSelect(fileInput, imageDiv) {
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imageDiv.style.backgroundImage = `url('${event.target.result}')`;
                imageDiv.style.backgroundSize = 'cover';
                imageDiv.style.backgroundPosition = 'center';
                const icon = imageDiv.querySelector('svg');
                if (icon) icon.style.display = 'none';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

// Add line button
document.addEventListener('DOMContentLoaded', function() {
    const addLigneBtn = document.getElementById('add-ligne');
    if (addLigneBtn) {
        addLigneBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.getElementById('lignes-container');
            const newLigne = document.createElement('div');
            newLigne.className = 'rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4 ligne-item';
            newLigne.innerHTML = `
                <div class="grid grid-cols-12 gap-4">
                    <!-- Article Image -->
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-[#374151] mb-2">Photo</label>
                        <div class="h-24 w-24 rounded-lg border border-[#E5E7EB] bg-white overflow-hidden flex items-center justify-center article-image cursor-pointer hover:bg-[#F9FAFB]" onclick="this.parentElement.querySelector('input[type=file]').click()">
                            <svg class="h-8 w-8 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m-6-6H6m0 0H0"/>
                            </svg>
                        </div>
                        <input type="file" name="lignes[${ligneIndex}][image]" accept="image/*" class="hidden image-file-input">
                        <p class="text-xs text-[#6B7280] mt-1">Cliquer pour ajouter une photo</p>
                    </div>

                    <!-- Article Selection -->
                    <div class="col-span-10 space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Article *</label>
                                <select name="lignes[${ligneIndex}][article_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] article-select">
                                    <option value="">-- Sélectionner ou créer --</option>
                                    @foreach($articles as $article)
                                        <option value="{{ $article->id }}"
                                            data-nom="{{ $article->nom }}"
                                            data-prix="{{ $article->prix_achat ?? 0 }}"
                                            data-image="{{ $article->image ?? '' }}"
                                            data-categorie-id="{{ $article->categorie_id ?? '' }}">
                                            {{ $article->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Nom du Produit (nouveau)</label>
                                <input type="text" name="lignes[${ligneIndex}][product_name]" placeholder="Ex: Nouveau Produit" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] product-name-input">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Catégorie (nouveau)</label>
                                <select name="lignes[${ligneIndex}][categorie_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] categorie-select">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Quantité *</label>
                                <input type="number" name="lignes[${ligneIndex}][quantity]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] quantity-input">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Prix d'Achat *</label>
                                <input type="number" name="lignes[${ligneIndex}][purchase_price]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] price-input">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#374151] mb-1">Total Ligne</label>
                                <div class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm font-semibold text-[#1860E1] bg-[#FFFFFF]">
                                    <span class="ligne-total">0.00</span> DH
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <div class="col-span-1 flex items-end justify-center pb-1">
                        <button type="button" class="remove-ligne rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors" title="Supprimer cette ligne">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newLigne);
            attachLigneListeners(newLigne);
            ligneIndex++;
        });
    }
});

// Attach listeners to ligne items
function attachLigneListeners(ligneElement) {
    const fileInput = ligneElement.querySelector('.image-file-input');
    const imageDiv = ligneElement.querySelector('.article-image');
    const articleSelect = ligneElement.querySelector('.article-select');
    const categorieSelect = ligneElement.querySelector('.categorie-select');
    const quantityInput = ligneElement.querySelector('.quantity-input');
    const priceInput = ligneElement.querySelector('.price-input');
    const removeBtn = ligneElement.querySelector('.remove-ligne');
    const ligneTotal = ligneElement.querySelector('.ligne-total');

    // Handle image file selection
    handleFileSelect(fileInput, imageDiv);

    // Update price when article is selected
    const syncCategoryState = () => {
        if (!categorieSelect || !articleSelect) {
            return;
        }
        const option = articleSelect.options[articleSelect.selectedIndex];
        const categorieId = option?.dataset?.categorieId || '';

        if (articleSelect.value) {
            categorieSelect.value = categorieId;
            categorieSelect.setAttribute('disabled', 'disabled');
            categorieSelect.classList.add('opacity-70', 'cursor-not-allowed');
            categorieSelect.dataset.locked = '1';
        } else {
            categorieSelect.removeAttribute('disabled');
            categorieSelect.classList.remove('opacity-70', 'cursor-not-allowed');
            if (categorieSelect.dataset.locked === '1') {
                categorieSelect.value = '';
                categorieSelect.dataset.locked = '0';
            }
        }
    };

    articleSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const prix = option.dataset.prix || 0;
        const image = option.dataset.image;
        
        if (this.value && prix) {
            priceInput.value = prix;
        }

        if (image) {
            let imagePath = image;
            if (!imagePath.startsWith('http') && !imagePath.startsWith('/')) {
                imagePath = '/storage/' + imagePath;
            }
            imageDiv.style.backgroundImage = `url('${imagePath}')`;
            imageDiv.style.backgroundSize = 'cover';
            imageDiv.style.backgroundPosition = 'center';
            const icon = imageDiv.querySelector('svg');
            if (icon) icon.style.display = 'none';
        }
        
        updateLineTotal();
        syncCategoryState();
    });

    // Update line total on quantity or price change
    const updateLineTotal = () => {
        const qty = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = (qty * price).toFixed(2);
        ligneTotal.textContent = total;
        updateOrderTotal();
    };

    quantityInput.addEventListener('input', updateLineTotal);
    priceInput.addEventListener('input', updateLineTotal);

    // Remove line
    removeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        ligneElement.remove();
        updateOrderTotal();
    });

    syncCategoryState();
}

// Calculate order total
function updateOrderTotal() {
    let total = 0;
    document.querySelectorAll('.ligne-item').forEach(item => {
        const qty = parseFloat(item.querySelector('.quantity-input')?.value) || 0;
        const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('order-total').textContent = total.toFixed(2);
}

// Attach listeners to initial line and setup form submission
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.ligne-item').forEach(item => attachLigneListeners(item));
    updateOrderTotal();
    
    // Handle form submission with proper validation
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validate fournisseur is selected
            const fournisseurId = document.getElementById('fournisseur_id').value;
            if (!fournisseurId) {
                e.preventDefault();
                alert('Veuillez sélectionner un fournisseur');
                return false;
            }
            
            // Validate that at least one ligne has quantity > 0
            const quantityInputs = Array.from(document.querySelectorAll('.quantity-input'));
            const hasQuantity = quantityInputs.some(input => {
                const val = input.value ? parseFloat(input.value) : 0;
                return val > 0;
            });
            
            if (!hasQuantity) {
                e.preventDefault();
                alert('Veuillez ajouter au moins une quantité pour une ligne');
                return false;
            }
            
            // Show loading state on submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Création en cours...</span>';
            }
            
            // Form will submit naturally
        });
    }
});
</script>
@endsection
