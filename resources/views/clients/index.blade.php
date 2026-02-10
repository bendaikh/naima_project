@extends('layouts.dashboard')

@section('title', 'Clients')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Clients</h1>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">+ Ajouter un client</a>
        </div>
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <form method="get" class="flex gap-4">
                <input type="search" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..." class="flex-1 rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-medium text-white hover:bg-[#1557C7] transition-colors">Rechercher</button>
            </form>
        </div>
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
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 w-8 h-8 text-white hover:bg-blue-600 transition-colors" title="Voir">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-500 w-8 h-8 text-white hover:bg-amber-600 transition-colors" title="Modifier">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    </a>
                                    <form method="post" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Supprimer ce client ?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-500 w-8 h-8 text-white hover:bg-red-600 transition-colors" title="Supprimer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                                        </button>
                                    </form>
                                </div>
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
