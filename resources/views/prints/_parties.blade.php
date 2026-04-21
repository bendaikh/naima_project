{{--
    Partial: Émetteur / Adressé à (same layout for devis, bon de commande, facture)
    Required variables:
        $params          - ParametresEntreprise instance (company info)
        $recipientName   - string (client or fournisseur name)
        $recipientLines  - string|null (address block, may contain newlines)
        $recipientIce    - string|null
        $recipientLabel  - string (default "Adressé à")
--}}
@php
    $recipientLabel = $recipientLabel ?? 'Adressé à';
@endphp

<table class="parties">
    <tr>
        <td>
            <div class="party-label">Émetteur</div>
            <div class="party-box emetteur">
                <strong>{{ $params->nom ?: 'SCIMAT' }}</strong><br>
                @if($params->adresse)
                    {!! nl2br(e($params->adresse)) !!}<br>
                @else
                    80, bd Moulay Slimane, Business centre, attiat allah<br>
                    ETG 2 APPT 12, Quartier: Oukacha, Casablanca,<br>
                    Maroc<br>
                    20153 Casablanca<br>
                @endif
                @if($params->telephone || $params->fax)
                    Tél.: {{ $params->telephone ?: '05 22 38 22 32' }} - Fax: {{ $params->fax ?: '05 22 21 13 46' }}<br>
                @else
                    Tél.: 05 22 38 22 32 - Fax: 05 22 21 13 46<br>
                @endif
                @if($params->email)
                    Email: {{ $params->email }}<br>
                @else
                    Email: scimat@scimat.ma<br>
                @endif
                @if($params->website)
                    Web: {{ $params->website }}
                @else
                    Web: https://scimat-labo.ma/
                @endif
            </div>
        </td>
        <td>
            <div class="party-label">{{ $recipientLabel }}</div>
            <div class="party-box recipient">
                <strong>{{ $recipientName ?? '—' }}</strong><br>
                @if(!empty($recipientLines))
                    {!! nl2br(e($recipientLines)) !!}<br>
                @endif
                @if(!empty($recipientIce))
                    ICE: {{ $recipientIce }}
                @endif
            </div>
        </td>
    </tr>
</table>
