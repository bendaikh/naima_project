@extends('prints.layout')

@section('title', 'Commande ' . ($bonDeCommande->reference ?? $bonDeCommande->id))

@section('content')
    @php
        $params = \App\Models\ParametresEntreprise::get();
        $fournisseur = $bonDeCommande->fournisseur;
        $totalHT  = (float) $bonDeCommande->getTotalAmount();
        $tvaRate  = 20;
        $totalTva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $totalTva;
        $numeroDoc = $bonDeCommande->reference
            ?? ('26BL' . str_pad((string) $bonDeCommande->id, 5, '0', STR_PAD_LEFT));
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
                <div class="doc-title">Commande {{ $numeroDoc }}</div>
                <div class="doc-meta">Date de commande : {{ $bonDeCommande->order_date->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- Émetteur / Adressé à --}}
    @include('prints._parties', [
        'params' => $params,
        'recipientLabel' => 'Adressé à',
        'recipientName'  => $fournisseur->nom ?? '—',
        'recipientLines' => $fournisseur?->adresse,
        'recipientIce'   => $fournisseur?->ice,
    ])

    {{-- Reference number row (Bon de Commande) --}}
    @if(!empty($bonDeCommande->reference))
        <div class="ref-line">Bon de Commande : {{ $bonDeCommande->reference }}</div>
    @endif

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
            @forelse($bonDeCommande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->designation ?? $ligne->article?->nom ?? 'N/A' }}</td>
                    <td class="center">{{ $tvaRate }}%</td>
                    <td class="num">{{ number_format((float) $ligne->purchase_price, 2, ',', ' ') }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format((float) $ligne->quantity, 2, ',', ' '), '0'), ',') }}</td>
                    <td class="num">{{ number_format((float) $ligne->getLineTotal(), 2, ',', ' ') }}</td>
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
@endsection
