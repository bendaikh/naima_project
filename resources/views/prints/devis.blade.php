@extends('prints.layout')

@section('title', 'Devis ' . $devis->numero)

@section('content')
    @php
        $params = \App\Models\ParametresEntreprise::get();
        $client = $devis->client;
        $totalHT  = (float) $devis->total_ht;
        $totalTTC = (float) $devis->total_ttc;
        $totalTva = $totalTTC - $totalHT;
        $tvaRate  = (float) $devis->tva;
        $dateValidite = $devis->date->copy()->addDays(30);
    @endphp

    {{-- Header: Logo (left) + Title (right) --}}
    <table class="doc-header">
        <tr>
            <td class="logo-cell">
                @if($params->logo && file_exists(public_path('storage/' . $params->logo)))
                    <img src="{{ asset('storage/' . $params->logo) }}" alt="Logo">
                @endif
            </td>
            <td class="title-cell">
                <div class="doc-title">Devis {{ $devis->numero }}</div>
                <div class="doc-meta">Date : {{ $devis->date->format('d/m/Y') }}</div>
                <div class="doc-meta">Date de fin de validité : {{ $dateValidite->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- Émetteur / Adressé à --}}
    @include('prints._parties', [
        'params' => $params,
        'recipientLabel' => 'Adressé à',
        'recipientName'  => $client->nom_raison_sociale ?? '—',
        'recipientLines' => $client?->adresse,
        'recipientIce'   => $client?->ice,
    ])

    {{-- Currency note (right aligned, above the table) --}}
    <div class="currency-note">Montants exprimés en Dirham</div>

    {{-- Items table --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 50%;">Désignation</th>
                <th class="center" style="width: 10%;">TVA</th>
                <th class="num" style="width: 14%;">P.U. HT</th>
                <th class="num" style="width: 8%;">Qté</th>
                <th class="num" style="width: 18%;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devis->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->designation }}</td>
                    <td class="center">{{ rtrim(rtrim(number_format((float) ($ligne->tva ?? $devis->tva), 2, ',', ' '), '0'), ',') }}%</td>
                    <td class="num">{{ number_format((float) $ligne->prix_unitaire, 2, ',', ' ') }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format((float) $ligne->quantite, 2, ',', ' '), '0'), ',') }}</td>
                    <td class="num">{{ number_format((float) $ligne->total_ht, 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; font-style:italic;">Aucun article</td></tr>
            @endforelse
            <tr class="spacer"><td colspan="5">&nbsp;</td></tr>
        </tbody>
    </table>

    {{-- Bottom: payment info + totals --}}
    @include('prints._bottom', [
        'params'   => $params,
        'totalHT'  => $totalHT,
        'totalTva' => $totalTva,
        'totalTTC' => $totalTTC,
        'tvaRate'  => $tvaRate,
    ])

    {{-- Signature zone (specific to devis) --}}
    <div class="signature-zone">
        <div class="signature-label">Cachet, Date, Signature et mention "Bon pour Accord"</div>
        <div class="signature-box"></div>
    </div>
@endsection
