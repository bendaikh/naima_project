@extends('layouts.dashboard')

@section('title', 'Articles')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-800">Articles</h1>
        <a href="{{ route('articles.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Créer un article
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    @if($articles->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white p-8 text-center shadow">
            <p class="text-slate-600">Aucun article enregistré.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Description</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Prix de vente</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Stock</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($articles as $article)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $article->nom }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($article->description, 50) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                                    @if($article->quantite_stock > $article->quantite * 0.5)
                                        bg-green-100 text-green-700
                                    @elseif($article->quantite_stock > 0)
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ $article->quantite_stock }} {{ $article->unite }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-700">Voir</a>
                                <a href="{{ route('articles.edit', $article) }}" class="text-amber-600 hover:text-amber-700">Modifier</a>
                                <form method="POST" action="{{ route('articles.destroy', $article) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
