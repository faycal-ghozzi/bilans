@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['id' => $file->company, 'date' => $file->date])
@endsection

@section('content')
<div class="container mx-auto p-8">
    <div class="bg-white p-8 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Analyse Financière</h1>

        <table class="w-full border-collapse bg-white rounded-lg shadow">
            <thead class="bg-btlGreen text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Ratio</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Valeur</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Ratios de Rentabilité -->
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td class="px-6 py-4 font-bold">Ratios de Rentabilité</td>
                    <td class="px-6 py-4">Retour sur capitaux propres (Résultat d'exploitation)</td>
                    <td class="px-6 py-4">{{ number_format($ratios['profitability']['operating_return_on_equity'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Retour sur capitaux propres (Résultat net)</td>
                    <td>{{ number_format($ratios['profitability']['net_return_on_equity'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>EBE / CA</td>
                    <td>{{ number_format($ratios['profitability']['ebe_to_revenue'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Résultat net / CA</td>
                    <td>{{ number_format($ratios['profitability']['net_return_on_revenue'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>CAF / CA</td>
                    <td>{{ number_format($ratios['profitability']['caf_to_revenue'], 2) }}</td>
                </tr>

                <!-- Ratios de Structure Financière -->
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td class="px-6 py-4 font-bold">Ratios de Structure Financière</td>
                    <td class="px-6 py-4">Ressources stables - Actifs immobilisés</td>
                    <td class="px-6 py-4">{{ number_format($ratios['financial_structure']['stable_resources_minus_fixed_assets'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Actif circulant - Passif circulant</td>
                    <td>{{ number_format($ratios['financial_structure']['working_capital'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>FR - BFR</td>
                    <td>{{ number_format($ratios['financial_structure']['fr_bfr'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Encours clients / CA (en jours)</td>
                    <td>{{ number_format($ratios['financial_structure']['client_turnover'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Stocks / Achats TTC (en jours)</td>
                    <td>{{ number_format($ratios['financial_structure']['stock_turnover'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Dettes fournisseurs / Achats TTC (en jours)</td>
                    <td>{{ number_format($ratios['financial_structure']['supplier_payment_period'], 2) }}</td>
                </tr>

                <!-- Ratios de Liquidité -->
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td class="px-6 py-4 font-bold">Ratios de Liquidité</td>
                    <td class="px-6 py-4">Liquidité générale</td>
                    <td class="px-6 py-4">{{ number_format($ratios['liquidity']['current_liquidity'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Liquidité réduite</td>
                    <td>{{ number_format($ratios['liquidity']['quick_ratio'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Liquidité de trésorerie</td>
                    <td>{{ number_format($ratios['liquidity']['cash_ratio'], 2) }}</td>
                </tr>

                <!-- Ratios d'Endettement -->
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td class="px-6 py-4 font-bold">Ratios d'Endettement</td>
                    <td class="px-6 py-4">Charges financières / CA</td>
                    <td class="px-6 py-4">{{ number_format($ratios['indebtedness']['financial_charges_to_revenue'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Charges financières / EBE</td>
                    <td>{{ number_format($ratios['indebtedness']['financial_charges_to_ebe'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Dettes financières / Capitaux propres</td>
                    <td>{{ number_format($ratios['indebtedness']['financial_debt_to_equity'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>EBITDA / Charges financières</td>
                    <td>{{ number_format($ratios['indebtedness']['ebitda_to_financial_charges'], 2) }}</td>
                </tr>
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Dettes financières / EBITDA</td>
                    <td>{{ number_format($ratios['indebtedness']['financial_debt_to_ebitda'], 2) }}</td>
                </tr>

                <!-- Ratios de Solvabilité -->
                <tr class="bg-gray-100 hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td class="px-6 py-4 font-bold">Ratios de Solvabilité</td>
                    <td class="px-6 py-4">Capitaux propres / Ressources stables</td>
                    <td class="px-6 py-4">{{ number_format($ratios['solvency']['equity_to_stable_resources'], 2) }}</td>
                </tr>
                <tr class="bg-white hover:bg-btlGreen hover:text-white transition-transform transform hover:scale-105 duration-150 ease-in-out">
                    <td></td>
                    <td>Capitaux propres / Total du bilan</td>
                    <td>{{ number_format($ratios['solvency']['equity_to_total_assets'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
