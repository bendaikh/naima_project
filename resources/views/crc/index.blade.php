@extends('layouts.dashboard')

@section('title', 'CRM')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Gestion de la Relation Client (CRM)</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('crc.contacts') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Contacts</p>
                    <p class="text-xs text-slate-500">Base de contacts</p>
                </div>
            </div>
        </a>

        <a href="{{ route('crc.opportunites') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14m-4 0a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Opportunités</p>
                    <p class="text-xs text-slate-500">Pipeline commercial</p>
                </div>
            </div>
        </a>

        <a href="{{ route('crc.campagnes') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 001-2.868V7a1 1 0 00-1-1h-1.468c-.596 0-1.150.182-1.616.48M7 14m0 0a2 2 0 110 4 2 2 0 010-4zm0 0a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 110 4 2 2 0 010-4zm6-2a2 2 0 110-4 2 2 0 010 4z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600">Campagnes</p>
                    <p class="text-xs text-slate-500">Campagnes marketing</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
