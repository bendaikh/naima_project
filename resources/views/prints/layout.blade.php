<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 12mm 14mm 18mm 14mm;
        }

        html, body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
            line-height: 1.35;
        }

        body {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm 14mm 14mm 14mm;
            background: #fff;
        }

        /* ===== Header row: Logo (left) + Title (right) ===== */
        .doc-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .doc-header td {
            vertical-align: top;
            padding: 0;
        }

        .doc-header td.logo-cell {
            width: 50%;
            text-align: left;
        }

        .doc-header td.logo-cell img {
            max-width: 140px;
            max-height: 70px;
            object-fit: contain;
        }

        .doc-header td.title-cell {
            width: 50%;
            text-align: right;
        }

        .doc-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 4px;
            color: #000;
        }

        .doc-meta {
            font-size: 9.5pt;
            margin-bottom: 1px;
            color: #000;
        }

        /* ===== Two-column block (Émetteur / Adressé à) ===== */
        .parties {
            width: 100%;
            margin-top: 6px;
            margin-bottom: 10px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .parties td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .parties .party-label {
            font-size: 9pt;
            color: #000;
            margin-bottom: 2px;
            padding-left: 2px;
        }

        .parties .party-box {
            border: 1px solid #9aa0a6;
            padding: 10px 12px;
            min-height: 110px;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #000;
        }

        .parties .party-box.emetteur {
            background: #e9edf2;
        }

        .parties .party-box.recipient {
            background: #ffffff;
        }

        .parties .party-box strong {
            font-weight: bold;
        }

        /* ===== Reference line (bordered row) ===== */
        .ref-line {
            margin: 6px 0 4px 0;
            padding: 6px 10px;
            font-size: 9.5pt;
            border: 1px solid #9aa0a6;
        }

        /* ===== Currency note above items table ===== */
        .currency-note {
            margin: 8px 0 2px 0;
            font-size: 9pt;
            font-style: italic;
            color: #000;
            text-align: right;
        }

        /* ===== Items table ===== */
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .items th {
            background: #ffffff;
            border: 1px solid #9aa0a6;
            padding: 6px 8px;
            font-weight: bold;
            text-align: left;
            color: #000;
            font-size: 9.5pt;
        }

        .items td {
            border: 1px solid #9aa0a6;
            padding: 6px 8px;
            color: #000;
            vertical-align: top;
        }

        .items th.num, .items td.num {
            text-align: right;
            white-space: nowrap;
        }

        .items th.center, .items td.center {
            text-align: center;
            white-space: nowrap;
        }

        /* Spacer row to give the items table a consistent tall appearance like in the sample PDFs */
        .items tr.spacer td {
            border-left: 1px solid #9aa0a6;
            border-right: 1px solid #9aa0a6;
            border-top: none;
            border-bottom: none;
            height: 260px;
        }

        /* ===== Bottom area: payment + totals ===== */
        .bottom {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .bottom td {
            vertical-align: top;
            padding: 0;
        }

        .bottom td.payment {
            width: 60%;
            padding-right: 16px;
            font-size: 8.5pt;
            line-height: 1.45;
        }

        .bottom td.totals {
            width: 40%;
        }

        .payment p {
            margin: 0 0 1px 0;
        }

        .payment .pay-title {
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 1px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .totals-table td {
            padding: 4px 8px;
        }

        .totals-table td.label {
            font-weight: normal;
            text-align: left;
        }

        .totals-table td.value {
            text-align: right;
            white-space: nowrap;
            border-bottom: 1px solid #c0c4c8;
        }

        .totals-table tr.grand td {
            font-weight: bold;
            background: #d6dde4;
            color: #b22222;
        }

        /* ===== Signature zone (devis) ===== */
        .signature-zone {
            margin-top: 10px;
            width: 40%;
            margin-left: auto;
        }

        .signature-zone .signature-label {
            font-size: 9pt;
            margin-bottom: 2px;
        }

        .signature-zone .signature-box {
            border: 1px solid #9aa0a6;
            height: 70px;
        }

        /* ===== Footer ===== */
        .doc-footer {
            margin-top: 40px;
            padding-top: 8px;
            border-top: 1px solid #999;
            text-align: center;
            font-size: 7.5pt;
            color: #333;
            line-height: 1.4;
        }

        .doc-footer .page-num {
            margin-top: 4px;
            text-align: right;
            font-size: 8pt;
        }

        @media print {
            .doc-footer {
                page-break-inside: avoid;
            }
        }

        /* ===== Print toolbar (hidden when printing) ===== */
        .print-toolbar {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #1F2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 11pt;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .print-toolbar button, .print-toolbar a {
            background: #1860E1;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 10pt;
            cursor: pointer;
            text-decoration: none;
            margin-left: 6px;
        }

        .print-toolbar button:hover, .print-toolbar a:hover {
            background: #1557C7;
        }

        .print-toolbar a.back {
            background: #6B7280;
        }

        .print-toolbar a.back:hover {
            background: #4B5563;
        }

        @media print {
            .print-toolbar { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="print-toolbar">
        <button onclick="window.print()">🖨️ Imprimer</button>
        <a href="{{ $backUrl ?? url()->previous() }}" class="back">← Retour</a>
    </div>

    @yield('content')

    @php
        $footerParams = \App\Models\ParametresEntreprise::get();
    @endphp

    @if($footerParams->footer_legal_text)
        <div class="doc-footer">
            {!! nl2br(e($footerParams->footer_legal_text)) !!}
            <div class="page-num">1 / 1</div>
        </div>
    @else
        <div class="doc-footer">
            Société à responsabilité limitée (SARL) - Capital de 100 000 MAD - R.C.: 437783<br>
            I.F.: 37581256 - C.N.S.S.: 1780191 - ICE: 002264979000090 - Numéro TVA: 30700232
            <div class="page-num">1 / 1</div>
        </div>
    @endif

    <script>
        // Auto-open print dialog if requested via URL parameter
        if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
            window.addEventListener('load', () => setTimeout(() => window.print(), 300));
        }
    </script>
</body>
</html>
