@extends('layouts.dashboard')

@section('title', 'Détail - Article')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $article->nom }}</h1>
            <p class="text-sm text-slate-600 mt-1">{{ $article->categorie }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('articles.edit', $article) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Modifier</a>
            <form method="POST" action="{{ route('articles.destroy', $article) }}" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    <!-- Details Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">UGS (Code Produit)</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ $article->ugs }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Prix de vente</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <p class="text-sm font-medium text-slate-600">Quantité</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">{{ $article->quantite }} {{ ucfirst($article->unite) }}</p>
        </div>
    </div>

    <!-- Pricing Info -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <h3 class="font-semibold text-slate-800 mb-4">Tarification</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Prix d'achat</span>
                    <span class="font-medium">{{ number_format($article->prix_achat, 2, ',', ' ') }} DH</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Prix de vente</span>
                    <span class="font-medium">{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</span>
                </div>
                <div class="border-t border-slate-200 pt-3 flex justify-between">
                    <span class="text-slate-600">Marge brute</span>
                    <span class="font-bold text-green-600">{{ number_format($article->profit_margin, 2) }}%</span>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <h3 class="font-semibold text-slate-800 mb-4">Informations</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Impôt</span>
                    <span class="font-medium">{{ $article->impot }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Catégorie</span>
                    <span class="font-medium">{{ ucfirst($article->categorie) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Entrepôt</span>
                    <span class="font-medium">{{ $article->entrepot ? ucfirst($article->entrepot) : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($article->description)
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <h3 class="font-semibold text-slate-800 mb-4">Description</h3>
            <p class="text-slate-700">{{ $article->description }}</p>
        </div>
    @endif

    @if($article->image_path)
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow">
            <h3 class="font-semibold text-slate-800 mb-4">Image</h3>
            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->nom }}" class="h-48 w-48 rounded object-cover">
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('articles.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</a>
    </div>
</div>
@endsection
