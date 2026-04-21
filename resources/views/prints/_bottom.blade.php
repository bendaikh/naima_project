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
            <p>Règlement par virement sur le compte bancaire ou Règlement TTC par chèque</p>
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
