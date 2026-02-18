@extends('layouts.dashboard')
@section('title', 'Créer un Retour Fournisseur')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-[#1F2937]">Créer un retour fournisseur</h1>

    <form action="{{ route('achats.bon-retour-fournisseur.store') }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-6">
        @csrf

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

        <!-- Sélection du Bon de Commande -->
        <div>
            <label for="bon_de_commande_id" class="mb-2 block text-sm font-medium text-[#374151]">Bon de Commande *</label>
            <select id="bon_de_commande_id" name="bon_de_commande_id" required onchange="loadBonDeCommandeData()" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                <option value="">Sélectionner un bon de commande</option>
                @foreach($bonsDeCommande as $bon)
                    <option value="{{ $bon->id }}" {{ old('bon_de_commande_id') == $bon->id ? 'selected' : '' }}>
                        #{{ $bon->id }} - {{ $bon->fournisseur->nom }} ({{ $bon->order_date->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
            @error('bon_de_commande_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Supplier (auto-filled) -->
        <div>
            <label for="fournisseur_id" class="mb-2 block text-sm font-medium text-[#374151]">Fournisseur</label>
            <input type="text" id="fournisseur_nom" class="w-full rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] px-4 py-2 text-sm" readonly>
            <input type="hidden" id="fournisseur_id" name="fournisseur_id">
        </div>

        <!-- Return Date -->
        <div>
            <label for="return_date" class="mb-2 block text-sm font-medium text-[#374151]">Date du retour *</label>
            <input type="date" id="return_date" name="return_date" required value="{{ old('return_date', date('Y-m-d')) }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            @error('return_date')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Return Type -->
        <div>
            <label for="return_type" class="mb-2 block text-sm font-medium text-[#374151]">Type de retour *</label>
            <select id="return_type" name="return_type" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                <option value="REFUND" {{ old('return_type') == 'REFUND' ? 'selected' : '' }}>Remboursement (argent retourné)</option>
                <option value="REPLACEMENT" {{ old('return_type') == 'REPLACEMENT' ? 'selected' : '' }}>Remplacement (nouveaux produits envoyés)</option>
            </select>
            @error('return_type')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Return Reason -->
        <div>
            <label for="return_reason" class="mb-2 block text-sm font-medium text-[#374151]">Raison du retour</label>
            <input type="text" id="return_reason" name="return_reason" value="{{ old('return_reason') }}" placeholder="Ex: Défectueux, Mauvaise quantité, etc." class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
        </div>

        <!-- Return Lines -->
        <div class="border-t border-[#E5E7EB] pt-6">
            <h3 class="mb-4 text-lg font-semibold text-[#1F2937]">Produits à retourner</h3>
            <div id="lignes-container" class="space-y-4">
                <p class="text-sm text-[#6B7280]">Sélectionnez un bon de commande pour charger les produits.</p>
            </div>
        </div>

        <!-- Return Total -->
        <div class="rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4">
            <p class="text-lg font-semibold text-[#1F2937]">
                Total du retour: <span id="return-total" class="text-[#1860E1]">0.00</span> DH
            </p>
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="mb-2 block text-sm font-medium text-[#374151]">Remarques</label>
            <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('notes') }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7] transition-colors">
                Créer le retour fournisseur
            </button>
            <a href="{{ route('achats.bon-retour-fournisseur.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
function loadBonDeCommandeData() {
    const bonId = document.getElementById('bon_de_commande_id').value;
    if (!bonId) {
        document.getElementById('lignes-container').innerHTML = '<p class="text-sm text-[#6B7280]">Sélectionnez un bon de commande pour charger les produits.</p>';
        document.getElementById('fournisseur_nom').value = '';
        document.getElementById('fournisseur_id').value = '';
        return;
    }

    fetch(`/achats/bon-de-commande/${bonId}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('fournisseur_nom').value = data.fournisseur_nom;
            document.getElementById('fournisseur_id').value = data.fournisseur_id;

            const container = document.getElementById('lignes-container');
            container.innerHTML = '';

            if (data.lignes.length === 0) {
                container.innerHTML = '<p class="text-sm text-[#6B7280]">Aucun produit dans ce bon de commande.</p>';
                return;
            }

            data.lignes.forEach((ligne, index) => {
                const div = document.createElement('div');
                div.className = 'rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] p-4 ligne-item';
                div.innerHTML = `
                    <input type="hidden" name="lignes[${index}][bon_de_commande_ligne_id]" value="${ligne.id}">
                    <div class="grid gap-4 sm:grid-cols-7 items-end">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Produit</label>
                            <input type="text" class="w-full rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm" readonly value="${ligne.product_name}">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Image</label>
                            ${ligne.image_url
                                ? `<img src="${ligne.image_url}" alt="${ligne.product_name}" class="h-12 w-12 rounded-lg object-cover border border-[#E5E7EB]">`
                                : `<div class="h-12 w-12 rounded-lg bg-[#F3F4F6] flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                   </div>`
                            }
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Reçu</label>
                            <input type="text" class="w-full rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm" readonly value="${ligne.quantity_received}">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Déjà retourné</label>
                            <input type="text" class="w-full rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm" readonly value="${ligne.quantity_already_returned}">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Peut retourner</label>
                            <input type="text" class="w-full rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm" readonly value="${ligne.max_returnable}">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Qté à retourner *</label>
                            <input type="number" name="lignes[${index}][return_quantity]" step="0.01" max="${ligne.max_returnable}" placeholder="0.00" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm return-qty-input focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]" oninput="updateReturnTotal()">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-[#374151]">Prix</label>
                            <input type="text" class="w-full rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm price-input" readonly value="${ligne.purchase_price}">
                        </div>
                    </div>
                `;
                container.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('lignes-container').innerHTML = '<p class="text-sm text-red-500">Erreur lors du chargement des produits.</p>';
        });
}

function updateReturnTotal() {
    let total = 0;
    document.querySelectorAll('.return-qty-input').forEach(input => {
        const qty = parseFloat(input.value) || 0;
        const ligneItem = input.closest('.ligne-item');
        const priceInput = ligneItem.querySelector('.price-input');
        const price = parseFloat(priceInput?.value) || 0;
        total += qty * price;
    });
    document.getElementById('return-total').textContent = total.toFixed(2);
}
</script>
@endsection
