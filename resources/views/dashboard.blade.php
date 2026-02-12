@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-8">
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-8 shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-600/10"></div>
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-indigo-600/20 blur-3xl"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">Bonjour, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }} 👋</h1>
                    <p class="mt-3 text-lg text-slate-300">Bienvenue dans votre magasin d'électronique. Voici un résumé de votre activité</p>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur-xl opacity-50"></div>
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                            <div class="text-center text-white">
                                <div class="text-4xl font-bold">{{ now()->format('d') }}</div>
                                <div class="text-sm text-slate-300 mt-1">{{ now()->format('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main KPI Cards - Sales, Finances & Stock --}}
        <div class="grid gap-6 lg:grid-cols-3 md:grid-cols-2">
            {{-- Sales Card --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-100 p-8 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-600/10"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">💰 Chiffre d'Affaires</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ number_format($chiffreAffaires, 0, ',', ' ') }} DH</p>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    +12.5%
                                </span>
                                <span class="text-xs text-slate-500 font-medium">vs mois dernier</span>
                            </div>
                        </div>
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Unpaid Invoices Card --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50 via-orange-100 to-amber-100 p-8 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-orange-600/10"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">⚠️ Factures Impayées</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ number_format($facturesImpayees, 0, ',', ' ') }} DH</p>
                            <div class="mt-3">
                                <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1.5 text-xs font-bold text-orange-700">
                                    <span class="h-2 w-2 rounded-full bg-orange-500 mr-2 animate-pulse"></span>
                                    {{ $totalFactures }} factures
                                </span>
                            </div>
                        </div>
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Value Card --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 via-green-100 to-emerald-100 p-8 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-600/10"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">📦 Valeur du Stock</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ number_format($valeurStockTotal, 0, ',', ' ') }} DH</p>
                            <div class="mt-3">
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                    {{ $totalArticles }} produits
                                </span>
                            </div>
                        </div>
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Electronics Inventory Section --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Inventory Status --}}
            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">État du Stock</h2>
                        <p class="text-sm text-slate-500 mt-1">Situation de votre inventaire</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- In Stock -->
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-green-50 p-6 border border-emerald-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-slate-600">En stock</p>
                                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $articlesEnStock }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600">Produits disponibles</p>
                    </div>

                    <!-- Out of Stock -->
                    <div class="rounded-2xl bg-gradient-to-br from-red-50 to-rose-50 p-6 border border-red-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Rupture stock</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ $articlesEnRupture }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600">À réapprovisionner</p>
                    </div>

                    <!-- Total Items -->
                    <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 p-6 border border-blue-200 col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Quantité totale</p>
                                <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($stockTotal, 0, ',', ' ') }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-8 0m32 0a4 4 0 01-8 0m0-4v4m0-11v3m0 0a9 9 0 010 18"/></svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600">Unités en inventaire</p>
                    </div>
                </div>
            </div>

            {{-- Top Selling Products --}}
            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Produits Populaires</h2>
                        <p class="text-sm text-slate-500 mt-1">Top 5 des articles vendus</p>
                    </div>
                </div>
                <div class="space-y-3 max-h-[350px] overflow-y-auto">
                    @forelse($meilleursProduits as $product)
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors duration-200 group">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm truncate group-hover:text-blue-600">{{ $product['nom'] }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ number_format($product['prix'], 0, ',', ' ') }} DH/unité</p>
                            </div>
                            <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                                <div class="text-right">
                                    <p class="font-bold text-lg text-slate-900">{{ (int)$product['quantite'] }}</p>
                                    <p class="text-xs text-slate-500">vendus</p>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 group-hover:from-blue-200 group-hover:to-indigo-200 transition-colors">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <svg class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="text-sm font-medium text-slate-500">Aucune vente enregistrée</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Revenue & Activities Grid --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Revenue Chart --}}
            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Aperçu des Revenus</h2>
                        <p class="text-sm text-slate-500 mt-1">Performances mensuelles</p>
                    </div>
                </div>
                <div class="mt-8 flex items-end justify-between gap-3" style="height: 220px;">
                    @foreach($chartData as $i => $bar)
                        @php $vals = array_column($chartData, 'value'); $max = !empty($vals) ? max($vals) : 1; $h = $max > 0 ? (($bar['value'] / $max) * 100) : 0; @endphp
                        <div class="group flex flex-1 flex-col items-center gap-3 cursor-pointer">
                            <div class="relative w-full">
                                <div class="w-full rounded-t-2xl bg-gradient-to-t from-blue-500 to-indigo-400 shadow-lg transition-all duration-300 group-hover:from-blue-600 group-hover:to-indigo-500 group-hover:shadow-xl" style="height: {{ $h }}%; min-height: 12px;"></div>
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 hidden group-hover:block">
                                    <div class="bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-xl whitespace-nowrap">
                                        {{ number_format($bar['value'], 0, ',', ' ') }} DH
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-blue-600 transition-colors duration-200">{{ $bar['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activities --}}
            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Activités Récentes</h2>
                        <p class="text-sm text-slate-500 mt-1">Derniers événements</p>
                    </div>
                </div>
                <ul class="space-y-3 max-h-[280px] overflow-y-auto">
                    @forelse($activites as $act)
                        <li class="group flex gap-4 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 hover:border-slate-200 transition-all duration-200 cursor-pointer">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-200
                                @if($act['type'] === 'facture_payee') bg-gradient-to-br from-emerald-400 to-green-500 text-white
                                @elseif($act['type'] === 'facture_impayee' || $act['type'] === 'facture_partiel') bg-gradient-to-br from-amber-400 to-orange-500 text-white
                                @elseif($act['type'] === 'devis') bg-gradient-to-br from-blue-400 to-indigo-500 text-white
                                @else bg-gradient-to-br from-purple-400 to-violet-500 text-white
                                @endif">
                                @if($act['type'] === 'facture_payee')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @elseif(in_array($act['type'], ['facture_impayee', 'facture_partiel']))
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @elseif($act['type'] === 'devis')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @else
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">{{ $act['titre'] }}</p>
                                <p class="text-sm text-slate-600 mt-1">{{ $act['client'] }}@if($act['montant'] !== null) <span class="font-semibold text-slate-900">— {{ number_format($act['montant'], 0, ',', ' ') }} DH</span>@endif</p>
                                <p class="mt-1.5 text-xs text-slate-400 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $act['date']->diffForHumans() }}
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="py-12 text-center">
                            <svg class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="text-sm font-medium text-slate-500">Aucune activité récente</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Secondary Stats --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 group-hover:from-blue-200 group-hover:to-indigo-200 transition-colors duration-300">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Clients</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($nombreClients, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 group-hover:from-slate-200 group-hover:to-slate-300 transition-colors duration-300">
                        <svg class="h-7 w-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Fournisseurs</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($nombreFournisseurs, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-100 to-green-100 group-hover:from-emerald-200 group-hover:to-green-200 transition-colors duration-300">
                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Devis</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totalDevis, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 group-hover:from-amber-200 group-hover:to-orange-200 transition-colors duration-300">
                        <svg class="h-7 w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Factures</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totalFactures, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
