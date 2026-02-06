@extends('layouts.dashboard')

@section('title', 'Clients')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Clients</h1>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">+ Ajouter un client</a>
        </div>
        <form method="get" class="flex gap-2">
            <input type="search" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..." class="rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            <button type="submit" class="rounded-lg bg-[#1F2937] px-4 py-2 text-sm font-medium text-white hover:bg-[#374151]">Rechercher</button>
        </form>
        @if(session('success'))
            <p class="rounded-lg bg-[#E8F5E9] p-3 text-sm text-[#2E7D32]">{{ session('success') }}</p>
        @endif
        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-[#E5E7EB]">
                <thead class="bg-[#F9FAFB]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Nom / Raison sociale</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Email</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-[#6B7280]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] bg-white">
                    @forelse($clients as $client)
                        <tr>
                            <td class="px-6 py-4 text-sm text-[#1F2937]">{{ $client->nom_raison_sociale }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $client->telephone ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $client->email ?? '—' }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('clients.show', $client) }}" class="text-[#1860E1] hover:underline">Voir</a>
                                <a href="{{ route('clients.edit', $client) }}" class="ml-3 text-[#1860E1] hover:underline">Modifier</a>
                                <form method="post" action="{{ route('clients.destroy', $client) }}" class="inline ml-3" onsubmit="return confirm('Supprimer ce client ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#DC2626] hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucun client.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $clients->links() }}
    </div>
@endsection
