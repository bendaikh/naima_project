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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <span class="text-lg font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">{{ $companyParams->nom ?? config('app.name') }}</span>
                    <p class="text-xs text-slate-400 font-medium">ERP SaaS Business</p>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-6">
                <ul class="space-y-1 px-4">
                    {{-- Tableau de bord --}}
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-red-700' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.343a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 11a1 1 0 100-2h-1a1 1 0 100 2h1zM15.657 15.657a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 18a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.343 15.657a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM5 11a1 1 0 100-2H4a1 1 0 100 2h1zM5.343 5.343a1 1 0 001.414-1.414L6.05 3.222a1 1 0 00-1.414 1.414l.707.707z"/></svg>
                            </div>
                            <span class="tracking-wide">Tableau de bord</span>
                        </a>
                    </li>

                    {{-- Achats Dropdown --}}
                    <li>
                        <button type="button" onclick="toggleAchats()" class="w-full flex items-center justify-between gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('achats.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('achats.*') ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : 'bg-slate-700' }}">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <span class="tracking-wide">Achats</span>
                            </div>
                            <svg id="achats-arrow" class="h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </button>
                        
                        {{-- Achats Submenu --}}
                        <ul id="achats-menu" class="hidden space-y-2 pl-4 pt-2">
                            <li>
                                <a href="{{ route('achats.bon-de-commande.index') }}" class="flex items-center gap-4 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('achats.bon-de-commande.*') ? 'bg-emerald-500/20 text-emerald-200' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                                    <span class="h-2 w-2 rounded-full {{ request()->routeIs('achats.bon-de-commande.*') ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                    <span>Bons de Commande</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('achats.bon-retour-fournisseur.index') }}" class="flex items-center gap-4 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('achats.bon-retour-fournisseur.*') ? 'bg-emerald-500/20 text-emerald-200' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                                    <span class="h-2 w-2 rounded-full {{ request()->routeIs('achats.bon-retour-fournisseur.*') ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                    <span>Retours Fournisseurs</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('achats.avoir-fournisseur.index') }}" class="flex items-center gap-4 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('achats.avoir-fournisseur.*') ? 'bg-emerald-500/20 text-emerald-200' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                                    <span class="h-2 w-2 rounded-full {{ request()->routeIs('achats.avoir-fournisseur.*') ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                    <span>Avoirs Fournisseurs</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Gestion des utilisateurs --}}
                    <li>
                        <a href="{{ route('clients.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('clients.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('clients.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Clients</span>
                        </a>
                    </li>

                    {{-- Fournisseurs --}}
                    <li>
                        <a href="{{ route('fournisseurs.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('fournisseurs.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('fournisseurs.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="tracking-wide">Fournisseurs</span>
                        </a>
                    </li>

                    {{-- Articles --}}
                    <li>
                        <a href="{{ route('articles.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('articles.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('articles.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4"/></svg>
                            </div>
                            <span class="tracking-wide">Articles</span>
                        </a>
                    </li>

                    {{-- Devis --}}
                    <li>
                        <a href="{{ route('devis.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('devis.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('devis.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="tracking-wide">Devis</span>
                        </a>
                    </li>

                    {{-- Bon de livraison --}}
                    <li>
                        <a href="{{ route('bon-livraison.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('bon-livraison.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('bon-livraison.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Bon de livraison</span>
                        </a>
                    </li>

                    {{-- Bon de retour --}}
                    <li>
                        <a href="{{ route('bon-retour.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('bon-retour.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('bon-retour.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4 4h.01M19 6H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2z"/></svg>
                            </div>
                            <span class="tracking-wide">Bon de retour</span>
                        </a>
                    </li>

                    {{-- Facture --}}
                    <li>
                        <a href="{{ route('factures.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('factures.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('factures.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="tracking-wide">Facture</span>
                        </a>
                    </li>

                    {{-- Avoir --}}
                    <li>
                        <a href="{{ route('avoir.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('avoir.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('avoir.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Avoir</span>
                        </a>
                    </li>

                    {{-- Banques | caisse (with submenu) --}}
                    <li x-data="{ open: {{ request()->routeIs('banques.*') || request()->routeIs('ecritures.*') || request()->routeIs('virement-interne.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex w-full items-center justify-between gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('banques.*') || request()->routeIs('ecritures.*') || request()->routeIs('virement-interne.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-4">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('banques.*') || request()->routeIs('ecritures.*') || request()->routeIs('virement-interne.*') ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : 'bg-slate-700' }}">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <span class="tracking-wide">Banques | caisse</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <ul x-show="open" x-collapse class="mt-2 space-y-1 pl-12">
                            <li>
                                <a href="{{ route('banques.index') }}" class="block rounded-lg px-4 py-2 text-sm transition-all duration-200 {{ request()->routeIs('banques.index') ? 'bg-emerald-500/20 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                    Liste compte
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ecritures.index') }}" class="block rounded-lg px-4 py-2 text-sm transition-all duration-200 {{ request()->routeIs('ecritures.index') ? 'bg-emerald-500/20 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                    Liste écritures
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ecritures.categories') }}" class="block rounded-lg px-4 py-2 text-sm transition-all duration-200 {{ request()->routeIs('ecritures.categories') ? 'bg-emerald-500/20 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                    Liste écritures/catégories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('virement-interne.index') }}" class="block rounded-lg px-4 py-2 text-sm transition-all duration-200 {{ request()->routeIs('virement-interne.*') ? 'bg-emerald-500/20 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                    Virement interne
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Mouvements de Stock --}}
                    <li>
                        <a href="{{ route('stock-mouvements.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('stock-mouvements.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('stock-mouvements.*') ? 'bg-gradient-to-br from-emerald-400 to-green-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <span class="tracking-wide">Stock</span>
                        </a>
                    </li>

                    {{-- Paramètres --}}
                    <li>
                        <a href="{{ route('parametres.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('parametres.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('parametres.*') ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-slate-700' }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="tracking-wide">Paramètres</span>
                        </a>
                    </li>

                    {{-- Gestion Utilisateur (Superadmin only) --}}
                    @if(auth()->user()->isSuperAdmin())
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('users.*') ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 'bg-slate-700' }}">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="tracking-wide">Gestion Utilisateur</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
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

    <script>
        function toggleAchats() {
            const menu = document.getElementById('achats-menu');
            const arrow = document.getElementById('achats-arrow');
            
            menu.classList.toggle('hidden');
            arrow.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(-180deg)';
        }

        // Auto-expand Achats menu if we're on an achats route
        document.addEventListener('DOMContentLoaded', function() {
            const currentRoute = '{{ request()->route()->getName() }}';
            if (currentRoute.startsWith('achats.')) {
                const menu = document.getElementById('achats-menu');
                const arrow = document.getElementById('achats-arrow');
                menu.classList.remove('hidden');
                arrow.style.transform = 'rotate(-180deg)';
            }
        });
    </script>
</body>
</html>
