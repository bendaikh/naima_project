@extends('prints.layout')

@section('title', 'Bon de Livraison ' . $bonLivraison->numero)

@section('content')
    @php
        $params = $parametres ?? \App\Models\ParametresEntreprise::first();
        $client = $bonLivraison->client;
    @endphp

    {{-- Document title --}}
    <div class="doc-title">Bon de Livraison {{ $bonLivraison->numero }}</div>
    <div class="doc-meta">Date : {{ $bonLivraison->date->format('d/m/Y') }}</div>
    @if($bonLivraison->devis)
        <div class="doc-meta">Référence Devis : {{ $bonLivraison->devis->numero }}</div>
    @endif

    {{-- Émetteur / Adressé à --}}
    <table class="parties">
        <tr>
            <td>
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    @if($params->logo && file_exists(public_path('storage/' . $params->logo)))
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset('storage/' . $params->logo) }}" alt="Logo" style="max-width: 80px; max-height: 80px; object-fit: contain;">
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <div class="party-title">Émetteur</div>
                        <div class="party-body">
                            <strong>{{ $params->nom ?: 'SCIMAT' }}</strong><br>
                            @if($params->adresse)
                                {!! nl2br(e($params->adresse)) !!}<br>
                            @else
                                80, bd Moulay Slimane, Business centre, attiat allah<br>
                                ETG 2 APPT 12, Quartier: Oukacha, Casablanca, Maroc<br>
                                20153 Casablanca<br>
                            @endif
                            @if($params->telephone || $params->fax)
                                Tél.: {{ $params->telephone ?: '05 22 38 22 32' }}
                                @if($params->fax) - Fax: {{ $params->fax }} @else - Fax: 05 22 21 13 46 @endif<br>
                            @endif
                            @if($params->email)
                                Email: {{ $params->email }}<br>
                            @endif
                            @if($params->website)
                                Web: {{ $params->website }}
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="party-title">Livré à</div>
                <div class="party-body">
                    <strong>{{ $client->nom_raison_sociale ?? '—' }}</strong><br>
                    @if($client && $client->adresse)
                        {!! nl2br(e($client->adresse)) !!}<br>
                    @endif
                    @if($client && $client->ville)
                        {{ $client->ville }}<br>
                    @endif
                    @if($client && $client->telephone)
                        Tél.: {{ $client->telephone }}<br>
                    @endif
                    @if($client && !empty($client->ice ?? null))
                        ICE: {{ $client->ice }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Items table (without prices) --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 10%;">N°</th>
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
        </tbody>
    </table>

    {{-- Summary section --}}
    <div style="margin-top: 30px; padding: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 5px;">
                    <strong>Nombre total d'articles:</strong> {{ $bonLivraison->lignes->count() }}
                </td>
                <td style="border: none; padding: 5px; text-align: right;">
                    <strong>Quantité totale:</strong> {{ rtrim(rtrim(number_format((float) $bonLivraison->lignes->sum('quantite'), 2, ',', ' '), '0'), ',') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Status badge --}}
    <div style="margin-top: 20px; text-align: center;">
        @if($bonLivraison->statut === 'validé')
            <span style="display: inline-block; padding: 8px 20px; background-color: #dcfce7; color: #166534; border-radius: 20px; font-weight: 600; font-size: 14px;">✓ Livraison Validée</span>
        @elseif($bonLivraison->statut === 'brouillon')
            <span style="display: inline-block; padding: 8px 20px; background-color: #fef3c7; color: #92400e; border-radius: 20px; font-weight: 600; font-size: 14px;">Brouillon</span>
        @else
            <span style="display: inline-block; padding: 8px 20px; background-color: #e0e7ff; color: #3730a3; border-radius: 20px; font-weight: 600; font-size: 14px;">{{ ucfirst($bonLivraison->statut) }}</span>
        @endif
    </div>

    {{-- Signature sections --}}
    <table style="width: 100%; margin-top: 50px; border: none;">
        <tr>
            <td style="width: 50%; vertical-align: top; border: none; padding: 20px;">
                <div style="text-align: center; border: 2px solid #e5e7eb; padding: 40px 20px; min-height: 120px; border-radius: 8px;">
                    <div style="font-weight: 600; margin-bottom: 10px; color: #374151;">Signature du livreur</div>
                    <div style="color: #6b7280; font-size: 12px; margin-top: 40px;">Date : ____ / ____ / ________</div>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; border: none; padding: 20px;">
                <div style="text-align: center; border: 2px solid #e5e7eb; padding: 40px 20px; min-height: 120px; border-radius: 8px;">
                    <div style="font-weight: 600; margin-bottom: 10px; color: #374151;">Signature du destinataire</div>
                    <div style="color: #6b7280; font-size: 12px; margin-top: 40px;">Date : ____ / ____ / ________</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Note --}}
    <div style="margin-top: 30px; padding: 15px; background-color: #fffbeb; border-left: 4px solid #f59e0b; font-size: 12px; color: #92400e;">
        <strong>Note importante :</strong> Ce bon de livraison atteste de la réception des articles mentionnés ci-dessus. 
        Merci de vérifier la conformité des articles et de signaler toute anomalie dans les plus brefs délais.
    </div>
@endsection
