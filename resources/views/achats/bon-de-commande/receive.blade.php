@extends('layouts.dashboard')
@section('title', 'Réceptionner le Bon de Commande')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-[#1F2937]">Réceptionner le Bon de Commande #{{ $bonDeCommande->id }}</h1>

    @if(session('success'))
        <div class="rounded-lg bg-[#ECFDF5] p-4 text-sm text-[#065F46] border-l-4 border-[#10B981]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-[#FEE2E2] p-4 text-sm text-[#991B1B] border-l-4 border-[#DC2626]">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('achats.bon-de-commande.receive', $bonDeCommande) }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-6" id="receive-form">
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

        <div class="rounded-lg border border-[#DBEAFE] bg-[#EFF6FF] p-4">
            <p class="text-sm text-[#1E40AF]">
                <strong>Remarque :</strong> Entrez les quantités réellement reçues. Le stock sera mis à jour automatiquement.
            </p>
        </div>

        <!-- Ordered Lines -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#E5E7EB]">
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Produit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Quantité commandée</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Déjà reçu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Receptionner maintenant *</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#374151] uppercase">Prix d'achat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($bonDeCommande->lignes as $ligne)
                        <tr class="hover:bg-[#F9FAFB]">
                            <td class="px-4 py-3 text-sm text-[#1F2937]">
                                {{ $ligne->designation ?? $ligne->article?->nom ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                @php($imagePath = $ligne->image ?? $ligne->article?->image)
                                @if($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $ligne->designation ?? $ligne->article?->nom ?? 'Produit' }}" class="h-10 w-10 rounded-lg object-cover border border-[#E5E7EB]">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-[#F3F4F6] flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->quantity, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->received_quantity, 2) }}</td>
                            <td class="px-4 py-3">
                                <input type="number" 
                                    name="received[{{ $ligne->id }}]" 
                                    step="0.01" 
                                    min="0"
                                    value="{{ old('received.' . $ligne->id, 0) }}"
                                    placeholder="0.00" 
                                    class="w-24 rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                                @error('received.' . $ligne->id)
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3 text-sm text-[#6B7280]">{{ number_format($ligne->purchase_price, 2) }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-[#10B981] px-6 py-2 text-sm font-semibold text-white hover:bg-[#059669] transition-colors">
                Confirmer la réception et mettre à jour le stock
            </button>
            <a href="{{ route('achats.bon-de-commande.show', $bonDeCommande) }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-semibold text-[#374151] hover:bg-[#F9FAFB] transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('receive-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Log form submission
            const formData = new FormData(form);
            const entries = Array.from(formData.entries());
            console.log('Form is being submitted with data:', entries);
            
            // Check received data
            const received = {};
            for (const [key, value] of entries) {
                if (key.match(/^received\[\d+\]$/)) {
                    received[key] = value;
                }
            }
            console.log('Received items:', received);
            
            // Don't prevent submission - let it go through
        });
        console.log('Form submit handler attached');
    } else {
        console.error('Could not find form with id receive-form');
    }
});
</script>
@endsection
