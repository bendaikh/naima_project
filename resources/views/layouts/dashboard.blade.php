<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tableau de bord') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:300,400,500,600,700,800" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 font-sans text-slate-800 antialiased">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-2xl">
            <div class="flex h-20 items-center gap-3 px-6 border-b border-white/10 backdrop-blur-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                    <span class="text-xl font-bold">G</span>
                </div>
                <div>
                    <span class="text-lg font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">{{ config('app.name') }}</span>
                    <p class="text-xs text-slate-400 font-medium">ERP SaaS Business</p>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-6">
                <ul class="space-y-1 px-4">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </div>
                            <span class="tracking-wide">Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('clients.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-[#1860E1] text-white' : 'text-[#D1D5DB] hover:bg-[#374151] hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('fournisseurs.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('fournisseurs.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('fournisseurs.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <span class="tracking-wide">Fournisseurs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('devis.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('devis.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('devis.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="tracking-wide">Devis</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('factures.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('factures.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('factures.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2m-4-1V9a2 2 0 012-2h2a2 2 0 012 2v1m-4 1a2 2 0 01-2 2h-2a2 2 0 01-2-2"/></svg>
                            </div>
                            <span class="tracking-wide">Facturation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rapports.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('rapports.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('rapports.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14m-4 0a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="tracking-wide">Rapports</span>
                        </a>
                    </li>
                </ul>
                <p class="mt-8 px-6 text-xs font-bold uppercase tracking-widest text-slate-500">Administration</p>
                <ul class="mt-3 space-y-1 px-4">
                    <li>
                        <a href="{{ route('parametres.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('parametres.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('parametres.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 text-slate-300 hover:bg-white/5 hover:text-white">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Aide & Support</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="border-t border-white/10 p-6">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-4 shadow-xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 text-white">
                            <svg class="h-5 w-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-bold">Version Pro</span>
                        </div>
                        <p class="mt-2 text-xs text-white/90">Débloquez toutes les fonctionnalités avancées.</p>
                        <a href="#" class="mt-3 block w-full rounded-xl bg-white px-4 py-2.5 text-center text-sm font-bold text-orange-600 hover:bg-white/90 transition-all duration-200 shadow-lg">Mettre à niveau</a>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex flex-1 flex-col pl-64">
            {{-- Header --}}
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-[#E5E7EB] bg-white px-6 shadow-sm">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative w-80">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#9CA3AF]">Q</span>
                        <input type="search" placeholder="Rechercher..." class="block w-full rounded-lg border-0 bg-[#F3F4F6] py-2 pl-9 pr-4 text-sm text-[#1F2937] placeholder-[#9CA3AF] focus:ring-2 focus:ring-[#1860E1]/20" />
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" class="relative rounded-lg p-2 text-[#6B7280] hover:bg-[#F3F4F6] hover:text-[#1F2937]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-[#EF4444]"></span>
                    </button>
                    <a href="{{ route('factures.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nouvelle Facture
                    </a>
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Exporter
                    </button>
                    <div class="flex items-center gap-4 border-l border-slate-200 pl-6">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                            <p class="text-xs text-slate-500">Super Admin</p>
                        </div>
                        <div class="relative group">
                            <div class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white shadow-lg ring-2 ring-white hover:scale-105 transition-all duration-200">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                            <div class="absolute right-0 top-full z-50 mt-3 hidden w-48 rounded-2xl border border-slate-200 bg-white py-2 shadow-2xl group-hover:block">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-left text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
