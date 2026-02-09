@extends('layouts.dashboard')

@section('title', 'Détail - Article')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $article->nom ?? 'Article' }}</h1>
            <p class="text-sm text-slate-600 mt-1">{{ $article->categorie }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('articles.edit', $article) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Modifier</a>
            <a href="{{ route('stock-mouvements.article', $article) }}" class="rounded-lg bg-[#10B981] px-4 py-2 text-sm font-semibold text-white hover:bg-[#059669]">📊 Historique Stock</a>
            <form method="POST" action="{{ route('articles.destroy', $article) }}" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    <!-- Pricing & Stock Section -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Prix de vente</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Prix d'achat</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ number_format($article->prix_achat, 2, ',', ' ') }} DH</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Quantité initiale</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ $article->quantite }} {{ $article->unite }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Stock disponible</p>
            <div class="mt-2">
                <p class="text-lg font-semibold
                    @if($article->quantite_stock > $article->quantite * 0.5)
                        text-green-600
                    @elseif($article->quantite_stock > 0)
                        text-yellow-600
                    @else
                        text-red-600
                    @endif
                ">{{ $article->quantite_stock }} {{ $article->unite }}</p>
                <div class="mt-2 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($article->quantite_stock / max($article->quantite, 1)) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
        <h3 class="font-semibold text-slate-800 mb-4">Informations</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Marge brute</span>
                    @if($article->prix_vente > 0)
                        <span class="font-medium">{{ number_format(($article->prix_vente - $article->prix_achat) / $article->prix_vente * 100, 2) }}%</span>
                    @else
                        <span class="font-medium">N/A</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Total stock</span>
                    <span class="font-medium">{{ number_format($article->quantite * $article->prix_vente, 2, ',', ' ') }} DH</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Impôt</span>
                    <span class="font-medium">{{ $article->impot ? $article->impot . '%' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">UGS</span>
                    <span class="font-medium">{{ $article->ugs ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($article->description)
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                <h3 class="font-semibold text-slate-800 mb-4">Description</h3>
                <p class="text-slate-700">{{ $article->description }}</p>
            </div>
        @endif

        @if($article->numero_facture || $article->compte_revenu || $article->compte_depense)
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                <h3 class="font-semibold text-slate-800 mb-4">Comptabilité</h3>
                <div class="space-y-2 text-sm">
                    @if($article->numero_facture)
                        <div>
                            <span class="text-slate-600">Numéro facture:</span>
                            <span class="font-medium">{{ $article->numero_facture }}</span>
                        </div>
                    @endif
                    @if($article->compte_revenu)
                        <div>
                            <span class="text-slate-600">Compte revenu:</span>
                            <span class="font-medium">{{ $article->compte_revenu }}</span>
                        </div>
                    @endif
                    @if($article->compte_depense)
                        <div>
                            <span class="text-slate-600">Compte dépense:</span>
                            <span class="font-medium">{{ $article->compte_depense }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if($article->entrepot || $article->image_path)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($article->entrepot)
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                    <h3 class="font-semibold text-slate-800 mb-4">Localisation</h3>
                    <p class="text-slate-700">Entrepôt: <strong>{{ $article->entrepot }}</strong></p>
                </div>
            @endif

            @if($article->image_path)
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                    <h3 class="font-semibold text-slate-800 mb-4">Image</h3>
                    <p class="text-slate-700 text-sm">{{ $article->image_path }}</p>
                </div>
            @endif
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('articles.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</a>
    </div>
</div>
@endsection
