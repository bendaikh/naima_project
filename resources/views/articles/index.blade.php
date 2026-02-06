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
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">NON</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">IMAGE</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">NOM</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">SKU</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">CATÉGORIE</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">PRIX VENTE</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($articles as $article)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->nom }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <div class="h-10 w-10 rounded bg-slate-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $article->nom }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $article->ugs }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($article->categorie) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-700 font-medium">Voir</a>
                                <a href="{{ route('articles.edit', $article) }}" class="text-amber-600 hover:text-amber-700 font-medium">Modifier</a>
                                <form method="POST" action="{{ route('articles.destroy', $article) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
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
