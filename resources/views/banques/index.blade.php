@extends('layouts.dashboard')

@section('title', 'Banques et Caisse')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Banques et Caisse</h1>
            <a href="{{ route('banques.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">+ Ajouter un compte</a>
        </div>

        {{-- Total Solde Card --}}
        <div class="rounded-lg border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-700">Solde Total</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-900">{{ number_format($totalSolde, 2, ',', ' ') }} DH</p>
                </div>
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 shadow-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <form method="get" class="flex gap-4">
                <input type="search" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..." class="flex-1 rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                <select name="type_compte" class="rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="">Tous les types</option>
                    <option value="Compte bancaire, chèque, courant ou carte" {{ request('type_compte') === 'Compte bancaire, chèque, courant ou carte' ? 'selected' : '' }}>Compte bancaire</option>
                    <option value="Caisse" {{ request('type_compte') === 'Caisse' ? 'selected' : '' }}>Caisse</option>
                    <option value="Épargne" {{ request('type_compte') === 'Épargne' ? 'selected' : '' }}>Épargne</option>
                    <option value="Autres" {{ request('type_compte') === 'Autres' ? 'selected' : '' }}>Autres</option>
                </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">N° Compte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Solde Initial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-[#6B7280]">Solde Actuel</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-[#6B7280]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] bg-white">
                    @forelse($banques as $banque)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-[#1F2937]">{{ $banque->libelle }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold 
                                    @if(str_contains(strtolower($banque->type_compte ?? ''), 'banque')) bg-blue-100 text-blue-800
                                    @elseif(str_contains(strtolower($banque->type_compte ?? ''), 'caisse')) bg-emerald-100 text-emerald-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ Str::limit($banque->type_compte ?? '—', 20) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ $banque->numero_compte ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-[#6B7280]">{{ number_format($banque->solde_initial, 2, ',', ' ') }} {{ $banque->devise }}</td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $banque->solde_actuel >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ number_format($banque->solde_actuel, 2, ',', ' ') }} {{ $banque->devise }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('banques.show', $banque) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 w-8 h-8 text-white hover:bg-blue-600 transition-colors" title="Voir">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    </a>
                                    <a href="{{ route('banques.edit', $banque) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-500 w-8 h-8 text-white hover:bg-amber-600 transition-colors" title="Modifier">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/><path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    </a>
                                    <form method="post" action="{{ route('banques.destroy', $banque) }}" onsubmit="return confirm('Supprimer ce compte ?');" style="display: inline;">
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
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-[#6B7280]">Aucun compte bancaire ou caisse.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $banques->links() }}
    </div>
@endsection
