@extends('layouts.dashboard')

@section('title', 'Devis ' . $devis->numero)

@section('content')
    <style>
        /* Screen styles */
        .print-btn {
            display: inline-block;
        }
        
        /* Hide print area on screen */
        #print-area {
            display: none;
        }
    </style>
    
    <style media="print">
        @page {
            size: A4;
            margin: 15mm;
        }
        
        * {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        html, body {
            width: 100% !important;
            height: 100% !important;
            background: white !important;
            color: #000 !important;
            font-family: Arial, sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Hide layout wrapper with screen content */
        div.max-w-7xl {
            display: none !important;
        }
        
        div.no-print {
            display: none !important;
        }
        
        /* Hide sidebar, navbar, header, and navigation */
        aside, nav, header, 
        .sidebar, .navbar, .topbar, .dashboard-nav,
        [class*="sidebar"], [class*="navbar"], [class*="header"] {
            display: none !important;
        }
        
        /* Show print area */
        #print-area {
            display: block !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
            position: static !important;
        }
        
        .print-container {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
            page-break-inside: avoid;
            display: block !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            margin: 10px 0 8px 0 !important;
            page-break-after: avoid;
            color: #000 !important;
        }
        
        h1 {
            font-size: 24px !important;
            font-weight: bold !important;
        }
        
        h2 {
            font-size: 14px !important;
            font-weight: bold !important;
            margin-top: 15px !important;
        }
        
        p {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 15px 0 !important;
            page-break-inside: avoid;
        }
        
        th {
            background-color: #f0f0f0 !important;
            border: 1px solid #000 !important;
            padding: 8px !important;
            text-align: left !important;
            font-weight: bold !important;
            font-size: 12px !important;
            color: #000 !important;
        }
        
        td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            font-size: 12px !important;
            color: #000 !important;
        }
        
        tbody tr:nth-child(even) {
            background-color: #fafafa !important;
        }
        
        .print-header {
            text-align: center !important;
            border-bottom: 3px solid #000 !important;
            margin-bottom: 20px !important;
            padding-bottom: 15px !important;
            page-break-after: avoid;
        }
        
        .print-header h1 {
            font-size: 28px !important;
            margin: 0 !important;
            font-weight: bold !important;
            color: #000 !important;
            letter-spacing: 2px !important;
        }
        
        .print-header p {
            font-size: 14px !important;
            margin: 5px 0 0 0 !important;
            color: #333 !important;
        }
        
        .info-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr 1fr !important;
            gap: 20px !important;
            margin: 20px 0 !important;
            page-break-inside: avoid;
        }
        
        .info-box {
            border: 1px solid #000 !important;
            padding: 12px !important;
            background-color: #fff !important;
            font-size: 11px !important;
            line-height: 1.8 !important;
            color: #000 !important;
            display: block !important;
        }
        
        .info-box strong {
            display: block !important;
            font-weight: bold !important;
            margin-bottom: 6px !important;
            font-size: 12px !important;
            text-decoration: underline !important;
            padding-bottom: 3px !important;
        }
        
        .info-box br {
            content: "" !important;
            display: block !important;
        }
        
        .totals-section {
            margin-top: 20px !important;
            page-break-inside: avoid;
            display: block !important;
        }
        
        .total-row {
            display: flex !important;
            justify-content: space-between !important;
            padding: 8px 12px !important;
            border: none !important;
            font-size: 12px !important;
            color: #000 !important;
            margin: 4px 0 !important;
        }
        
        .total-row.highlight {
            font-weight: bold !important;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
            font-size: 13px !important;
            padding: 10px 12px !important;
            margin-top: 6px !important;
            background-color: #f9f9f9 !important;
        }
        
        .signature-section {
            margin-top: 20px !important;
            page-break-inside: avoid;
            display: block !important;
        }
        
        .signature-section h3 {
            font-size: 12px !important;
            margin-bottom: 10px !important;
            font-weight: bold !important;
        }
        
        img {
            max-width: 180px !important;
            max-height: 120px !important;
            border: 1px solid #000 !important;
        }
        
        div {
            page-break-inside: avoid;
        }
    </style>

    <!-- SCREEN VERSION -->
    <div class="max-w-7xl mx-auto px-4 py-8 no-print">
        <!-- Header with Actions -->
        <div class="bg-gradient-to-r from-[#1F2937] to-[#374151] rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">Devis {{ $devis->numero }}</h1>
                    <p class="mt-2 text-[#E5E7EB]">Créé le {{ $devis->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex gap-3 items-center flex-wrap">
                    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-[#10B981] px-5 py-3 text-sm font-semibold text-white hover:bg-[#059669] transition-all duration-200 shadow-md">
                        🖨️ Imprimer
                    </button>
                    @if($devis->canConvertToFacture())
                        <form method="POST" action="{{ route('devis.convert-to-facture', $devis) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#8B5CF6] px-5 py-3 text-sm font-semibold text-white hover:bg-[#7C3AED] transition-all duration-200 shadow-md">
                                ✓ Convertir en facture
                            </button>
                        </form>
                    @endif
                    @if($devis->canBeEdited())
                        <a href="{{ route('devis.edit', $devis) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#3B82F6] px-5 py-3 text-sm font-semibold text-white hover:bg-[#2563EB] transition-all duration-200 shadow-md">
                            ✏️ Modifier
                        </a>
                    @endif
                    <a href="{{ route('devis.index') }}" class="rounded-lg border-2 border-white px-5 py-3 text-sm font-semibold text-white hover:bg-white hover:text-[#1F2937] transition-all duration-200">
                        ← Retour
                    </a>
                    <form method="POST" action="{{ route('devis.destroy', $devis) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-md">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-[#D1FAE5] border-2 border-[#10B981] p-4 mb-6 text-[#065F46] font-medium flex items-center gap-2">
                <span>✓</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Status Badge -->
        <div class="mb-6">
            @if($devis->statut === 'brouillon')
                <span class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold">
                    📋 Brouillon
                </span>
            @elseif($devis->statut === 'envoye')
                <span class="inline-block bg-blue-200 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                    📤 Envoyé
                </span>
            @elseif($devis->statut === 'accepte')
                <span class="inline-block bg-green-200 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    ✓ Accepté
                </span>
            @else
                <span class="inline-block bg-red-200 text-red-800 px-4 py-2 rounded-full text-sm font-semibold">
                    ✗ Refusé
                </span>
            @endif
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <!-- Client Info -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#3B82F6] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">👤 CLIENT</h3>
                <div class="space-y-2 text-[#374151]">
                    <p class="font-semibold text-base">{{ $devis->client->nom_raison_sociale ?? '—' }}</p>
                    @if($devis->client->adresse)
                        <p class="text-sm">{{ $devis->client->adresse }}</p>
                    @endif
                    @if($devis->client->ville)
                        <p class="text-sm">{{ $devis->client->ville }}</p>
                    @endif
                    @if($devis->client->telephone)
                        <p class="text-sm">📞 {{ $devis->client->telephone }}</p>
                    @endif
                </div>
            </div>

            <!-- Devis Info -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#10B981] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📅 INFORMATIONS</h3>
                <div class="space-y-3 text-[#374151]">
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">Date</p>
                        <p class="text-base font-semibold">{{ $devis->date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">Créé le</p>
                        <p class="text-base font-semibold">{{ $devis->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Conditions -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-[#F59E0B] p-6">
                <h3 class="text-lg font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">⚙️ CONDITIONS</h3>
                <div class="space-y-3 text-[#374151]">
                    <div>
                        <p class="text-xs font-semibold text-[#6B7280] uppercase">TVA</p>
                        <p class="text-2xl font-bold text-[#F59E0B]">{{ $devis->tva }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Section -->
        @if($devis->lignes && $devis->lignes->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📦 ARTICLES</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#F3F4F6] border-b-2 border-[#E5E7EB]">
                                <th class="px-4 py-3 text-left font-bold text-[#374151]">Désignation</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Quantité</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Prix unitaire</th>
                                <th class="px-4 py-3 text-right font-bold text-[#374151]">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis->lignes as $ligne)
                                <tr class="border-b border-[#E5E7EB] hover:bg-[#F9FAFB] transition-colors">
                                    <td class="px-4 py-3 text-[#1F2937] font-medium">{{ $ligne->designation }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->quantite, 2) }}</td>
                                    <td class="px-4 py-3 text-right text-[#374151]">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                                    <td class="px-4 py-3 text-right font-bold text-[#1F2937]">{{ number_format($ligne->total_ht, 2, ',', ' ') }} DH</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Totals Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="ml-auto max-w-md">
                <div class="space-y-3 mb-4 pb-4 border-b-2 border-[#E5E7EB]">
                    <div class="flex justify-between items-center text-[#374151]">
                        <span class="font-medium">TOTAL HT</span>
                        <span class="font-semibold">{{ number_format($devis->total_ht, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="flex justify-between items-center text-[#374151]">
                        <span class="font-medium">TVA ({{ $devis->tva }}%)</span>
                        <span class="font-semibold">{{ number_format($devis->total_ttc - $devis->total_ht, 2, ',', ' ') }} DH</span>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-gradient-to-r from-[#1F2937] to-[#374151] rounded-lg p-4 text-white">
                    <span class="text-lg font-bold">TOTAL TTC</span>
                    <span class="text-2xl font-bold">{{ number_format($devis->total_ttc, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        @if($devis->signature_image)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">✍️ SIGNATURE</h3>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $devis->signature_image) }}" alt="Signature" class="max-w-sm max-h-64 rounded-lg border-2 border-[#E5E7EB] shadow-md">
                </div>
            </div>
        @endif

        <!-- Status Management -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-[#1F2937] mb-4 pb-3 border-b-2 border-[#E5E7EB]">📋 GESTION DU STATUT</h3>
            <div class="flex gap-3 flex-wrap">
                @if($devis->canMarkAsSent())
                    <form method="POST" action="{{ route('devis.mark-as-sent', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition-all duration-200 shadow-md">
                            📤 Marquer comme envoyé
                        </button>
                    </form>
                @endif

                @if($devis->canMarkAsAccepted())
                    <form method="POST" action="{{ route('devis.mark-as-accepted', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition-all duration-200 shadow-md">
                            ✓ Marquer comme accepté
                        </button>
                    </form>
                @endif

                @if($devis->canMarkAsRefused())
                    <form method="POST" action="{{ route('devis.mark-as-refused', $devis) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-lg bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-all duration-200 shadow-md" onclick="return confirm('Êtes-vous sûr ?')">
                            ✗ Marquer comme refusé
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- PRINT VERSION (Hidden on screen, visible only in print) -->
    <div id="print-area" class="print-container">
        <!-- Header -->
        <div class="print-header">
            <h1>DEVIS</h1>
            <p>{{ $devis->numero }}</p>
        </div>

        <!-- Client & Document Info -->
        <div class="info-grid">
            <div class="info-box">
                <strong>CLIENT</strong>
                {{ $devis->client->nom_raison_sociale ?? '—' }}<br>
                @if($devis->client->adresse){{ $devis->client->adresse }}<br>@endif
                @if($devis->client->ville){{ $devis->client->ville }}@endif
            </div>
            <div class="info-box">
                <strong>INFORMATIONS</strong>
                Date: {{ $devis->date->format('d/m/Y') }}<br>
                Statut: @if($devis->statut === 'brouillon')Brouillon
                @elseif($devis->statut === 'envoye')Envoyé
                @elseif($devis->statut === 'accepte')Accepté
                @else Refusé@endif<br>
                Créé: {{ $devis->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="info-box">
                <strong>CONDITIONS</strong>
                TVA: {{ $devis->tva }}%
            </div>
        </div>

        <!-- Articles Section -->
        @if($devis->lignes && $devis->lignes->count() > 0)
            <h2>ARTICLES</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Désignation</th>
                        <th style="width: 12%; text-align: right;">Quantité</th>
                        <th style="width: 19%; text-align: right;">Prix unitaire</th>
                        <th style="width: 19%; text-align: right;">Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devis->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->designation }}</td>
                            <td style="text-align: right;">{{ number_format($ligne->quantite, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($ligne->total_ht, 2, ',', ' ') }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Totals Section -->
        <div class="totals-section">
            <div style="width: 50%; margin-left: auto;">
                <div class="total-row">
                    <span>TOTAL HT</span>
                    <span>{{ number_format($devis->total_ht, 2, ',', ' ') }} DH</span>
                </div>
                <div class="total-row">
                    <span>TVA ({{ $devis->tva }}%)</span>
                    <span>{{ number_format($devis->total_ttc - $devis->total_ht, 2, ',', ' ') }} DH</span>
                </div>
                <div class="total-row highlight">
                    <span>TOTAL TTC</span>
                    <span>{{ number_format($devis->total_ttc, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        @if($devis->signature_image)
            <div class="signature-section">
                <h3>SIGNATURE</h3>
                <img src="{{ asset('storage/' . $devis->signature_image) }}" alt="Signature">
            </div>
        @endif

        <!-- Footer -->
        <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #000; font-size: 10px; text-align: center;">
            <p style="margin: 0;">Document généré le {{ now()->format('d/m/Y H:i') }}</p>
            <p style="margin: 4px 0 0 0;">Devis n° {{ $devis->numero }}</p>
        </div>
    </div>
@endsection
