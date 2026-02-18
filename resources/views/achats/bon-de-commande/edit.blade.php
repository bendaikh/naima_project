@extends('layouts.dashboard')
@section('title', 'Modifier le Bon de Commande')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-[#1F2937]">Modifier le Bon de Commande #{{ $bonDeCommande->id }}</h1>

    <form action="{{ route('achats.bon-de-commande.update', $bonDeCommande) }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="rounded-lg bg-[#FEE2E2] p-4 text-sm text-[#991B1B] border-l-4 border-[#DC2626]">
                <p class="mb-2 font-semibold">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Supplier Selection -->
        <div>
            <label for="fournisseur_id" class="mb-2 block text-sm font-medium text-[#374151]">Fournisseur *</label>
            <select id="fournisseur_id" name="fournisseur_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" {{ $bonDeCommande->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                        {{ $fournisseur->nom }}
                    </option>
                @endforeach
            </select>
            @error('fournisseur_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date Fields -->
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="order_date" class="mb-2 block text-sm font-medium text-[#374151]">Date de commande *</label>
                <input type="date" id="order_date" name="order_date" required value="{{ old('order_date', $bonDeCommande->order_date->format('Y-m-d')) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('order_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="expected_delivery_date" class="mb-2 block text-sm font-medium text-[#374151]">Date de livraison prévue</label>
                <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $bonDeCommande->expected_delivery_date?->format('Y-m-d')) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('expected_delivery_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="mb-2 block text-sm font-medium text-[#374151]">Remarques</label>
            <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('notes', $bonDeCommande->notes) }}</textarea>
        </div>

        <!-- Order Lines -->
        <div class="border-t border-[#E5E7EB] pt-6">
            <h3 class="mb-4 text-lg font-semibold text-[#1F2937]">Lignes de commande</h3>
            <div id="lignes-container">
                @foreach($bonDeCommande->lignes as $index => $ligne)
                    <div class="mb-4 rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4 ligne-item">
                        <div class="mb-3 grid gap-4 sm:grid-cols-6">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-[#374151]">Article (optionnel)</label>
                                <select name="lignes[{{ $index }}][article_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                    <option value="">-- Nouveau produit --</option>
                                    @foreach($articles as $article)
                                        <option value="{{ $article->id }}" {{ $ligne->article_id == $article->id ? 'selected' : '' }}>
                                            {{ $article->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-[#374151]">Nom du produit (si nouveau)</label>
                                <input type="text" name="lignes[{{ $index }}][product_name]" value="{{ old('lignes.' . $index . '.product_name', $ligne->product_name) }}" placeholder="Ex: Nouveau produit" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-[#374151]">Quantité *</label>
                                <input type="number" name="lignes[{{ $index }}][quantity]" step="0.01" required value="{{ old('lignes.' . $index . '.quantity', $ligne->quantity) }}" placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm quantity-input focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-[#374151]">Prix d'achat *</label>
                                <input type="number" name="lignes[{{ $index }}][purchase_price]" step="0.01" required value="{{ old('lignes.' . $index . '.purchase_price', $ligne->purchase_price) }}" placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm price-input focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                            </div>
                            <div class="pt-6">
                                <span class="text-sm font-semibold text-[#374151]">Total: <span class="ligne-total">0.00</span></span>
                            </div>
                            <div class="pt-6">
                                <button type="button" class="remove-ligne font-medium text-red-600 hover:text-red-900 text-sm">Supprimer</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-ligne" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors">
                + Ajouter une ligne
            </button>
        </div>

        <!-- Order Total -->
        <div class="rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4">
            <p class="text-lg font-semibold text-[#1F2937]">
                Total de la commande: <span id="order-total" class="text-[#1860E1]">0.00</span> DH
            </p>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                Modifier le bon de commande
            </button>
            <a href="{{ route('achats.bon-de-commande.show', $bonDeCommande) }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
let ligneIndex = {{ count($bonDeCommande->lignes) }};

document.getElementById('add-ligne').addEventListener('click', function() {
    const container = document.getElementById('lignes-container');
    const newLigne = document.createElement('div');
    newLigne.className = 'mb-4 rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4 ligne-item';
    newLigne.innerHTML = `
        <div class="mb-3 grid gap-4 sm:grid-cols-6">
            <div>
                <label class="mb-1 block text-xs font-medium text-[#374151]">Article (optionnel)</label>
                <select name="lignes[${ligneIndex}][article_id]" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="">-- Nouveau produit --</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}">{{ $article->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-[#374151]">Nom du produit (si nouveau)</label>
                <input type="text" name="lignes[${ligneIndex}][product_name]" placeholder="Ex: Nouveau produit" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-[#374151]">Quantité *</label>
                <input type="number" name="lignes[${ligneIndex}][quantity]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm quantity-input focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-[#374151]">Prix d'achat *</label>
                <input type="number" name="lignes[${ligneIndex}][purchase_price]" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm price-input focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div class="pt-6">
                <span class="text-sm font-semibold text-[#374151]">Total: <span class="ligne-total">0.00</span></span>
            </div>
            <div class="pt-6">
                <button type="button" class="remove-ligne font-medium text-red-600 hover:text-red-900 text-sm">Supprimer</button>
            </div>
        </div>
    `;
    container.appendChild(newLigne);
    attachLigneListeners(newLigne);
    ligneIndex++;
});

function attachLigneListeners(ligneElement) {
    const quantityInput = ligneElement.querySelector('.quantity-input');
    const priceInput = ligneElement.querySelector('.price-input');
    const removeBtn = ligneElement.querySelector('.remove-ligne');

    const updateTotal = () => {
        const qty = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = (qty * price).toFixed(2);
        ligneElement.querySelector('.ligne-total').textContent = total;
        updateOrderTotal();
    };

    quantityInput.addEventListener('change', updateTotal);
    priceInput.addEventListener('change', updateTotal);

    removeBtn.addEventListener('click', () => {
        ligneElement.remove();
        updateOrderTotal();
    });
}

function updateOrderTotal() {
    let total = 0;
    document.querySelectorAll('.ligne-item').forEach(item => {
        const qty = parseFloat(item.querySelector('.quantity-input')?.value) || 0;
        const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('order-total').textContent = total.toFixed(2);
}

// Attach listeners to initial lignes
document.querySelectorAll('.ligne-item').forEach(item => attachLigneListeners(item));
updateOrderTotal();
</script>
@endsection
