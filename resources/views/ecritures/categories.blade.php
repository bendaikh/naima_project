@extends('layouts.dashboard')

@section('title', 'Catégories d\'écritures')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Catégories d'écritures</h1>
            <a href="{{ route('ecritures.categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">+ Nouvelle catégorie</a>
        </div>

        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif

        <div class="overflow-x-auto rounded-lg border border-[#E5E7EB] bg-white shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB]">
                        <th class="px-6 py-3 text-left text-sm font-medium text-[#6B7280]">Tag/catégorie</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-[#6B7280]">Nb</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-[#6B7280]">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-[#6B7280]">Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalNb = 0;
                        $totalMontant = 0;
                    @endphp
                    @forelse($categories as $categorie)
                        @php
                            $total = $categorie->ecritures->sum('credit') - $categorie->ecritures->sum('debit');
                            $moyenne = $categorie->ecritures_count > 0 ? $total / $categorie->ecritures_count : 0;
                            $totalNb += $categorie->ecritures_count;
                            $totalMontant += $total;
                        @endphp
                        <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB]">
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $categorie->nom }}</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $categorie->ecritures_count }}</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ number_format($total, 2, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ number_format($moyenne, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucune catégorie.</td>
                        </tr>
                    @endforelse
                    @if($categories->count() > 0)
                        <tr class="bg-[#F9FAFB] font-semibold">
                            <td class="px-6 py-4 text-sm text-[#1F2937]">Total</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $totalNb }}</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ number_format($totalMontant, 2, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $totalNb > 0 ? number_format($totalMontant / $totalNb, 2, ',', ' ') : '0,00' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
