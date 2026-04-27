@extends('layouts.dashboard')

@section('title', 'Historique')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Historique</h1>
        </div>

        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-[#E5E7EB]">
                <thead class="bg-[#F9FAFB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Titre de l'action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] bg-white">
                    @forelse($historiques as $historique)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $historique->title }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $historique->description }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $historique->user?->name ?? 'Système' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $historique->action_date?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucune action enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $historiques->links() }}
    </div>
@endsection
