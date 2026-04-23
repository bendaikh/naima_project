@extends('prints.layout')

@section('title', 'Facture ' . $facture->numero)

@section('content')
    @php
        $params = \App\Models\ParametresEntreprise::get();
        $client = $facture->client;
        $totalHT  = (float) $facture->total_ht;
        $totalTTC = (float) $facture->total_ttc;
        $totalTva = $totalTTC - $totalHT;
        $tvaRate  = (float) ($facture->tva ?? 20);
        $bonLivraison = $facture->bonsLivraison->first() ?? null;
        $devis = $facture->devis ?? null;
        $refCommande = $bonLivraison->reference_commande ?? null;
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
                <div class="doc-title">Facture {{ $facture->numero }}</div>
                <div class="doc-meta">Date facturation : {{ $facture->date->format('d/m/Y') }}</div>
                @if($facture->date_echeance)
                    <div class="doc-meta">Date échéance : {{ $facture->date_echeance->format('d/m/Y') }}</div>
                @endif
                @if($bonLivraison)
                    <div class="doc-meta">Réf. commande : {{ $bonLivraison->numero }} / {{ $bonLivraison->date->format('d/m/Y') }}</div>
                @elseif($devis)
                    <div class="doc-meta">Réf. devis : {{ $devis->numero }} / {{ $devis->date->format('d/m/Y') }}</div>
                @endif
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

    {{-- Reference number row (Bon de Commande), if available --}}
    @if(!empty($refCommande))
        <div class="ref-line">Bon de Commande : {{ $refCommande }}</div>
    @endif

    {{-- Currency note (right aligned, above the table) --}}
    <div class="currency-note">Montants exprimés en Dirham</div>

    {{-- Items table --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 40%;">Désignation</th>
                <th class="center" style="width: 12%;">Catégorie</th>
                <th class="center" style="width: 10%;">TVA</th>
                <th class="num" style="width: 12%;">P.U. HT</th>
                <th class="num" style="width: 8%;">Qté</th>
                <th class="num" style="width: 18%;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facture->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->designation }}</td>
                    <td class="center">{{ $ligne->categorie ?? '—' }}</td>
                    <td class="center">{{ rtrim(rtrim(number_format((float) ($ligne->tva ?? $facture->tva ?? 20), 2, ',', ' '), '0'), ',') }}%</td>
                    <td class="num">{{ number_format((float) $ligne->prix_unitaire, 2, ',', ' ') }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format((float) $ligne->quantite, 2, ',', ' '), '0'), ',') }}</td>
                    <td class="num">{{ number_format((float) $ligne->total_ht, 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; font-style:italic;">Aucun article</td></tr>
            @endforelse
            <tr class="spacer"><td colspan="6">&nbsp;</td></tr>
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
