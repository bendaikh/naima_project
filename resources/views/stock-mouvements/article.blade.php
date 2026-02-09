@extends('layouts.dashboard')

@section('title', 'Historique Stock - ' . $article->nom)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('stock-mouvements.index') }}" class="text-sm text-[#1860E1] hover:underline">← Mouvements de stock</a>
                <h1 class="mt-2 text-2xl font-semibold text-[#1F2937]">{{ $article->nom }}</h1>
                <p class="text-sm text-[#6B7280]">Historique et mouvements de stock</p>
            </div>
        </div>

        <!-- Stock Summary Cards -->
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-[#6B7280]">Entrées Totales</p>
                <p class="mt-2 text-3xl font-bold text-[#10B981]">{{ number_format($summary['entrees'], 2, ',', ' ') }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-[#6B7280]">Sorties Totales</p>
                <p class="mt-2 text-3xl font-bold text-[#EF4444]">{{ number_format($summary['sorties'], 2, ',', ' ') }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-[#6B7280]">Stock Actuel</p>
                <p class="mt-2 text-3xl font-bold {{ $summary['balance'] >= 0 ? 'text-[#1860E1]' : 'text-[#EF4444]' }}">
                    {{ number_format($summary['balance'], 2, ',', ' ') }}
                </p>
                <p class="mt-1 text-xs text-[#6B7280]">{{ $article->unite }}</p>
            </div>
        </div>

        <!-- Filters -->
        <form method="get" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="type" class="block text-sm font-medium text-[#374151]">Type</label>
                    <select name="type" id="type" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        <option value="">Tous les types</option>
                        <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="sortie" {{ request('type') === 'sortie' ? 'selected' : '' }}>Sortie</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-[#374151]">Date de</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-[#374151]">Date au</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">
                    Filtrer
                </button>
                <a href="{{ route('stock-mouvements.article', $article) }}" class="inline-flex items-center justify-center rounded-lg bg-[#E5E7EB] px-4 py-2 text-sm font-semibold text-[#374151] hover:bg-[#D1D5DB]">
                    Réinitialiser
                </a>
            </div>
        </form>

        <!-- Movements Table -->
        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-[#E5E7EB]">
                <thead class="bg-[#F9FAFB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-[#6B7280]">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] bg-white">
                    @forelse($mouvements as $mouvement)
                        <tr class="hover:bg-[#F9FAFB]">
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $mouvement->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $mouvement->type === 'entree' ? 'bg-[#DBEAFE] text-[#1E40AF]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                                    {{ $mouvement->getTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <span class="{{ $mouvement->type === 'entree' ? 'text-[#10B981]' : 'text-[#EF4444]' }}">
                                    {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ number_format($mouvement->quantite, 2, ',', ' ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $mouvement->getMotifLabel() }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">
                                @if($mouvement->reference_type)
                                    {{ $mouvement->reference_type }}#{{ $mouvement->reference_id }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $mouvement->description ?? '—' }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('stock-mouvements.show', $mouvement) }}" class="text-[#1860E1] hover:underline">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucun mouvement.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $mouvements->links() }}
    </div>
@endsection
