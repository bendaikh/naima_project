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
            margin: 12mm 14mm 14mm 14mm;
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
            padding: 14mm;
            background: #fff;
        }

        /* ===== Header / Title ===== */
        .doc-title {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 4px;
            color: #000;
        }

        .doc-meta {
            font-size: 10pt;
            margin-bottom: 2px;
            color: #000;
        }

        /* ===== Two-column block (Émetteur / Adressé à) ===== */
        .parties {
            width: 100%;
            margin-top: 18px;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .parties td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .parties .party-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 4px;
            color: #000;
        }

        .parties .party-body {
            font-size: 9.5pt;
            line-height: 1.5;
            color: #000;
        }

        .parties .party-body strong {
            font-weight: bold;
        }

        /* ===== Reference line ===== */
        .ref-line {
            margin: 12px 0 6px 0;
            font-size: 10pt;
            font-weight: bold;
        }

        /* ===== Items table ===== */
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9.5pt;
        }

        .items th {
            background: #f3f3f3;
            border: 1px solid #999;
            padding: 6px 8px;
            font-weight: bold;
            text-align: left;
            color: #000;
            font-size: 9.5pt;
        }

        .items td {
            border: 1px solid #999;
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

        .currency-note {
            margin-top: 6px;
            font-size: 9pt;
            font-style: italic;
            color: #333;
        }

        /* ===== Bottom area: payment + totals ===== */
        .bottom {
            width: 100%;
            margin-top: 16px;
            border-collapse: collapse;
        }

        .bottom td {
            vertical-align: top;
            padding: 0;
        }

        .bottom td.payment {
            width: 60%;
            padding-right: 16px;
            font-size: 9pt;
            line-height: 1.55;
        }

        .bottom td.totals {
            width: 40%;
        }

        .payment p {
            margin: 0 0 2px 0;
        }

        .payment .pay-title {
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 2px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .totals-table td {
            padding: 5px 8px;
            border: 1px solid #999;
        }

        .totals-table td.label {
            font-weight: bold;
            background: #f9f9f9;
        }

        .totals-table td.value {
            text-align: right;
            white-space: nowrap;
        }

        .totals-table tr.grand td {
            font-weight: bold;
            background: #ececec;
        }

        /* ===== Signature mention ===== */
        .signature-mention {
            margin-top: 22px;
            font-size: 9.5pt;
            font-style: italic;
        }

        /* ===== Footer ===== */
        .doc-footer {
            position: fixed;
            bottom: 8mm;
            left: 14mm;
            right: 14mm;
            text-align: center;
            font-size: 8pt;
            color: #333;
            border-top: 1px solid #aaa;
            padding-top: 4px;
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
        </div>
    @else
        <div class="doc-footer">
            Société à responsabilité limitée (SARL) - Capital de 100 000 MAD - R.C.: 437783<br>
            I.F.: 37581256 - C.N.S.S.: 1780191 - ICE: 002264979000090 - Numéro TVA: 30700232
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
