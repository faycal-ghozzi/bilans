@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-6">Analyse Financière</h1>

        <div class="grid grid-cols-2 gap-4">
            <!-- Profitability Ratios -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Ratios de Rentabilité</h2>
                <ul>
                    <li>Retour sur capitaux propres (Résultat d'exploitation) : {{ number_format($ratios['profitability']['operating_return_on_equity'], 2) }}</li>
                    <li>Retour sur capitaux propres (Résultat net) : {{ number_format($ratios['profitability']['net_return_on_equity'], 2) }}</li>
                    <li>EBE / CA : {{ number_format($ratios['profitability']['ebe_to_revenue'], 2) }}</li>
                    <li>Résultat net / CA : {{ number_format($ratios['profitability']['net_return_on_revenue'], 2) }}</li>
                    <li>CAF / CA : {{ number_format($ratios['profitability']['caf_to_revenue'], 2) }}</li>
                </ul>
            </div>

            <!-- Financial Structure Ratios -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Ratios de Structure Financière</h2>
                <ul>
                    <li>Ressources stables - Actifs immobilisés : {{ number_format($ratios['financial_structure']['stable_resources_minus_fixed_assets'], 2) }}</li>
                    <li>Actif circulant - Passif circulant : {{ number_format($ratios['financial_structure']['working_capital'], 2) }}</li>
                    <li>FR - BFR : {{ number_format($ratios['financial_structure']['fr_bfr'], 2) }}</li>
                    <li>Encours clients / CA (en jours) : {{ number_format($ratios['financial_structure']['client_turnover'], 2) }}</li>
                    <li>Stocks / Achats TTC (en jours) : {{ number_format($ratios['financial_structure']['stock_turnover'], 2) }}</li>
                    <li>Dettes fournisseurs / Achats TTC (en jours) : {{ number_format($ratios['financial_structure']['supplier_payment_period'], 2) }}</li>
                </ul>
            </div>

            <!-- Liquidity Ratios -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Ratios de Liquidité</h2>
                <ul>
                    <li>Liquidité générale : {{ number_format($ratios['liquidity']['current_liquidity'], 2) }}</li>
                    <li>Liquidité réduite : {{ number_format($ratios['liquidity']['quick_ratio'], 2) }}</li>
                    <li>Liquidité de trésorerie : {{ number_format($ratios['liquidity']['cash_ratio'], 2) }}</li>
                </ul>
            </div>

            <!-- Indebtedness Ratios -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Ratios d'Endettement</h2>
                <ul>
                    <li>Charges financières / CA : {{ number_format($ratios['indebtedness']['financial_charges_to_revenue'], 2) }}</li>
                    <li>Charges financières / EBE : {{ number_format($ratios['indebtedness']['financial_charges_to_ebe'], 2) }}</li>
                    <li>Dettes financières / Capitaux propres : {{ number_format($ratios['indebtedness']['financial_debt_to_equity'], 2) }}</li>
                    <li>EBITDA / Charges financières : {{ number_format($ratios['indebtedness']['ebitda_to_financial_charges'], 2) }}</li>
                    <li>Dettes financières / EBITDA : {{ number_format($ratios['indebtedness']['financial_debt_to_ebitda'], 2) }}</li>
                </ul>
            </div>

            <!-- Solvency Ratios -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Ratios de Solvabilité</h2>
                <ul>
                    <li>Capitaux propres / Ressources stables : {{ number_format($ratios['solvency']['equity_to_stable_resources'], 2) }}</li>
                    <li>Capitaux propres / Total du bilan : {{ number_format($ratios['solvency']['equity_to_total_assets'], 2) }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
