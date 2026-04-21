{{--
    Partial: payment info (left) + totals (right).
    Required variables:
        $params   - ParametresEntreprise instance
        $totalHT  - float
        $totalTva - float
        $totalTTC - float
        $tvaRate  - numeric (for label "Total TVA xx%")
--}}
@php
    $fmt = fn($v) => number_format((float) $v, 2, ',', ' ');
    $tvaRateLabel = rtrim(rtrim(number_format((float) $tvaRate, 2, ',', ' '), '0'), ',');
@endphp

<table class="bottom">
    <tr>
        <td class="payment">
            <p class="pay-title">Règlement TTC par chèque à l'ordre de {{ $params->nom ?: 'SCIMAT' }} envoyé à</p>
            <p>80, bd Moulay Slimane, Business centre, attiat allah ETG 2 APPT 12, Quartier: Oukacha, Casablanca, Maroc</p>
            <p class="pay-title">Règlement par virement sur le compte bancaire suivant:</p>
            <p>Banque: <strong>Attijariwabank</strong></p>
            <p>Numéro de compte: <strong>007 780 0002755000000422 57</strong></p>
            <p>Nom du propriétaire du compte: <strong>{{ $params->nom ?: 'SCIMAT' }}</strong></p>
        </td>
        <td class="totals">
            <table class="totals-table">
                <tr>
                    <td class="label">Total HT</td>
                    <td class="value">{{ $fmt($totalHT) }}</td>
                </tr>
                <tr>
                    <td class="label">Total TVA {{ $tvaRateLabel }}%</td>
                    <td class="value">{{ $fmt($totalTva) }}</td>
                </tr>
                <tr class="grand">
                    <td class="label">Total TTC</td>
                    <td class="value">{{ $fmt($totalTTC) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
