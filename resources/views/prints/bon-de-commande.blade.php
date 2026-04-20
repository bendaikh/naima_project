@extends('prints.layout')

@section('title', 'Commande ' . $bonDeCommande->id)

@section('content')
    @php
        $params = \App\Models\ParametresEntreprise::get();
        $fournisseur = $bonDeCommande->fournisseur;
        $totalHT = (float) $bonDeCommande->getTotalAmount();
        $tvaRate = 20;
        $totalTva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $totalTva;
        $numeroDoc = $bonDeCommande->reference ?? ('26BL' . str_pad((string) $bonDeCommande->id, 5, '0', STR_PAD_LEFT));
    @endphp

    {{-- Document title --}}
    <div class="doc-title">Commande {{ $numeroDoc }}</div>
    <div class="doc-meta">Date de commande : {{ $bonDeCommande->order_date->format('d/m/Y') }}</div>

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
                <div class="party-title">Adressé à</div>
                <div class="party-body">
                    <strong>{{ $fournisseur->nom ?? '—' }}</strong><br>
                    @if($fournisseur && $fournisseur->adresse)
                        {!! nl2br(e($fournisseur->adresse)) !!}<br>
                    @endif
                    @if($fournisseur && !empty($fournisseur->ice ?? null))
                        ICE: {{ $fournisseur->ice }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Reference number (Bon de Commande) --}}
    @if(!empty($bonDeCommande->reference))
        <div class="ref-line">Bon de Commande : {{ $bonDeCommande->reference }}</div>
    @endif

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
                    <td>{{ $ligne->product_name ?? $ligne->article?->nom ?? 'N/A' }}</td>
                    <td class="center">{{ $tvaRate }}%</td>
                    <td class="num">{{ number_format((float) $ligne->purchase_price, 2, ',', ' ') }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format((float) $ligne->quantity, 2, ',', ' '), '0'), ',') }}</td>
                    <td class="num">{{ number_format((float) $ligne->getLineTotal(), 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; font-style:italic;">Aucun article</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="currency-note">Montants exprimés en Dirham</div>

    {{-- Bottom: payment info + totals --}}
    <table class="bottom">
        <tr>
            <td class="payment">
                <p class="pay-title">Règlement TTC par chèque à l'ordre de {{ $params->nom ?: 'SCIMAT' }} envoyé à</p>
                <p>80, bd Moulay Slimane, Business centre, attiat allah ETG 2 APPT 12, Quartier: Oukacha, Casablanca, Maroc</p>
                <p class="pay-title">Règlement par virement sur le compte bancaire suivant:</p>
                <p>Banque: Attijariwabank</p>
                <p>Numéro de compte: 007 780 0002755000000422 57</p>
                <p>Nom du propriétaire du compte: {{ $params->nom ?: 'SCIMAT' }}</p>
            </td>
            <td class="totals">
                <table class="totals-table">
                    <tr>
                        <td class="label">Total HT</td>
                        <td class="value">{{ number_format($totalHT, 2, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total TVA {{ $tvaRate }}%</td>
                        <td class="value">{{ number_format($totalTva, 2, ',', ' ') }}</td>
                    </tr>
                    <tr class="grand">
                        <td class="label">Total TTC</td>
                        <td class="value">{{ number_format($totalTTC, 2, ',', ' ') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
