@extends('prints.layout')

@section('title', 'Bon de Livraison ' . $bonLivraison->numero)

@section('content')
    @php
        $params = \App\Models\ParametresEntreprise::get();
        $client = $bonLivraison->client;
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
                <div class="doc-title">Bon de Livraison {{ $bonLivraison->numero }}</div>
                <div class="doc-meta">Date : {{ $bonLivraison->date->format('d/m/Y') }}</div>
                @if($bonLivraison->devis)
                    <div class="doc-meta">Référence Devis : {{ $bonLivraison->devis->numero }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Émetteur / Livré à --}}
    @include('prints._parties', [
        'params' => $params,
        'recipientLabel' => 'Livré à',
        'recipientName'  => $client->nom_raison_sociale ?? '—',
        'recipientLines' => trim(
            ($client?->adresse ? $client->adresse . "\n" : '') .
            ($client?->ville ? $client->ville . "\n" : '') .
            ($client?->telephone ? 'Tél.: ' . $client->telephone : '')
        ),
        'recipientIce'   => $client?->ice,
    ])

    {{-- Items table (no prices) --}}
    <table class="items">
        <thead>
            <tr>
                <th class="center" style="width: 10%;">N°</th>
                <th style="width: 70%;">Désignation</th>
                <th class="num" style="width: 20%;">Quantité</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bonLivraison->lignes as $index => $ligne)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $ligne->designation }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format((float) $ligne->quantite, 2, ',', ' '), '0'), ',') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center; font-style:italic;">Aucun article</td></tr>
            @endforelse
            <tr class="spacer"><td colspan="3">&nbsp;</td></tr>
        </tbody>
    </table>

    {{-- Summary section --}}
    <div style="margin-top: 16px; padding: 10px 12px; background-color: #f3f3f3; border: 1px solid #9aa0a6;">
        <table style="width: 100%; border: none; font-size: 9.5pt;">
            <tr>
                <td style="border: none; padding: 0;">
                    <strong>Nombre total d'articles:</strong> {{ $bonLivraison->lignes->count() }}
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <strong>Quantité totale:</strong> {{ rtrim(rtrim(number_format((float) $bonLivraison->lignes->sum('quantite'), 2, ',', ' '), '0'), ',') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Signature sections --}}
    <table style="width: 100%; margin-top: 30px; border-collapse: separate; border-spacing: 10px 0;">
        <tr>
            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                <div style="text-align: center; border: 1px solid #9aa0a6; padding: 30px 20px; min-height: 80px;">
                    <div style="font-weight: 600; margin-bottom: 6px; font-size: 9.5pt;">Signature du livreur</div>
                    <div style="color: #666; font-size: 8.5pt; margin-top: 30px;">Date : ____ / ____ / ________</div>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                <div style="text-align: center; border: 1px solid #9aa0a6; padding: 30px 20px; min-height: 80px;">
                    <div style="font-weight: 600; margin-bottom: 6px; font-size: 9.5pt;">Signature du destinataire</div>
                    <div style="color: #666; font-size: 8.5pt; margin-top: 30px;">Date : ____ / ____ / ________</div>
                </div>
            </td>
        </tr>
    </table>
@endsection
