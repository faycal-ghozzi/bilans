<?php

namespace App\Services;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementFile;
use Carbon\Carbon;

class FinancialRatioService
{
    public function calculateRatios($id)
    {
        // Retrieve financial statement data
        $file = FinancialStatementFile::with('company')->findOrFail($id);
        
        $dateCurrentYear = Carbon::parse($file->date)->format('Y-m-d');
        $datePreviousYear = Carbon::parse($file->date)->subYear()->format('Y-m-d');

        $financialStatements = FinancialStatement::with('entryPoint')
            ->where('company_id', $file->company_id)
            ->whereIn('date', [$dateCurrentYear, $datePreviousYear])
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        $currentYearData = $financialStatements[$dateCurrentYear] ?? collect([]);
        $previousYearData = $financialStatements[$datePreviousYear] ?? collect([]);

        // Calculate Ratios
        return [
            'profitability' => $this->calculateProfitabilityRatios($currentYearData, $previousYearData),
            'financial_structure' => $this->calculateFinancialStructureRatios($currentYearData, $previousYearData),
            'liquidity' => $this->calculateLiquidityRatios($currentYearData, $previousYearData),
            'indebtedness' => $this->calculateIndebtednessRatios($currentYearData, $previousYearData),
            'solvency' => $this->calculateSolvencyRatios($currentYearData),
        ];
    }

    private function calculateProfitabilityRatios($currentYearData, $previousYearData)
    {
        $resultatExploitation = $this->getValueByLabel($currentYearData, 'Résultat d\'exploitation');
        $capitauxPropres = $this->getValueByLabel($currentYearData, 'Total des capitaux propres après résultat de l\'exercice');
        $dettesFinancieres = $this->getValueByLabel($currentYearData, 'Total des passifs non courants');
        $resultatNet = $this->getValueByLabel($currentYearData, 'Résultat net de l\'exercice');
        $ebe = $this->getValueByLabel($currentYearData, 'EBE');
        $ca = $this->getValueByLabel($currentYearData, 'Revenus');
        $caf = $this->calculateCAF($currentYearData);

        return [
            'operating_return_on_equity' => ($capitauxPropres + $dettesFinancieres) ? ($resultatExploitation / ($capitauxPropres + $dettesFinancieres)) : 0,
            'net_return_on_equity' => $capitauxPropres ? ($resultatNet / $capitauxPropres) : 0,
            'ebe_to_revenue' => $ca ? ($ebe / $ca) : 0,
            'net_return_on_revenue' => $ca ? ($resultatNet / $ca) : 0,
            'caf_to_revenue' => $ca ? ($caf / $ca) : 0,
        ];
    }

    private function calculateFinancialStructureRatios($currentYearData, $previousYearData)
    {
        $ressourcesStables = $this->getValueByLabel($currentYearData, 'Total des capitaux propres et passifs');
        $actifsImmobiles = $this->getValueByLabel($currentYearData, 'Total des actifs immobilisés');
        $actifCirculant = $this->getValueByLabel($currentYearData, 'Total des actifs courants');
        $passifCirculant = $this->getValueByLabel($currentYearData, 'Total des passifs courants');

        $fr = $ressourcesStables - $actifsImmobiles;
        $bfr = $actifCirculant - $passifCirculant;

        $encoursClients = $this->getValueByLabel($currentYearData, 'Clients et comptes rattachés');
        $achatsTTC = $this->getValueByLabel($currentYearData, 'Achats de marchandises consommés') + $this->getValueByLabel($currentYearData, 'Achats d\'approvisionnements consommés');
        $stocks = $this->getValueByLabel($currentYearData, 'Stocks');
        $dettesFournisseurs = $this->getValueByLabel($currentYearData, 'Fournisseurs et comptes rattachés');

        $caAnnuel = $this->getValueByLabel($currentYearData, 'Revenus');

        return [
            'stable_resources_minus_fixed_assets' => $fr,
            'working_capital' => $bfr,
            'fr_bfr' => $bfr ? ($fr - $bfr) : 0,
            'client_turnover' => $caAnnuel ? (($encoursClients / $caAnnuel) * 365) : 0,
            'stock_turnover' => $achatsTTC ? (($stocks / $achatsTTC) * 365) : 0,
            'supplier_payment_period' => $achatsTTC ? (($dettesFournisseurs / $achatsTTC) * 365) : 0,
            'fr_to_total_assets' => $ressourcesStables ? ($fr / $ressourcesStables) : 0,
            'fr_to_bfr' => $bfr ? ($fr / $bfr) : 0,
        ];
    }

    private function calculateLiquidityRatios($currentYearData, $previousYearData)
    {
        $actifCirculant = $this->getValueByLabel($currentYearData, 'Total des actifs courants');
        $tresorerieActif = $this->getValueByLabel($currentYearData, 'Liquidités et équivalents de liquidités');
        $passifCirculant = $this->getValueByLabel($currentYearData, 'Total des passifs courants');
        $tresoreriePassif = $this->getValueByLabel($currentYearData, 'Concours bancaires et autres passifs financiers');

        return [
            'current_liquidity' => ($passifCirculant + $tresoreriePassif) ? (($actifCirculant + $tresorerieActif) / ($passifCirculant + $tresoreriePassif)) : 0,
            'quick_ratio' => $passifCirculant ? (($actifCirculant) / $passifCirculant) : 0,
            'cash_ratio' => ($passifCirculant + $tresoreriePassif) ? ($tresorerieActif / ($passifCirculant + $tresoreriePassif)) : 0,
        ];
    }

    private function calculateIndebtednessRatios($currentYearData, $previousYearData)
    {
        $chargesFinancieres = $this->getValueByLabel($currentYearData, 'Charges financières nettes');
        $ca = $this->getValueByLabel($currentYearData, 'Revenus');
        $ebe = $this->getValueByLabel($currentYearData, 'EBE');
        $capitauxPropres = $this->getValueByLabel($currentYearData, 'Total des capitaux propres après résultat de l\'exercice');
        $dettesFinancieres = $this->getValueByLabel($currentYearData, 'Total des passifs non courants');
        $ebitda = $this->calculateEBITDA($currentYearData);

        return [
            'financial_charges_to_revenue' => $ca ? ($chargesFinancieres / $ca) : 0,
            'financial_charges_to_ebe' => $ebe ? ($chargesFinancieres / $ebe) : 0,
            'financial_debt_to_equity' => $capitauxPropres ? ($dettesFinancieres / $capitauxPropres) : 0,
            'ebitda_to_financial_charges' => $chargesFinancieres ? ($ebitda / $chargesFinancieres) : 0,
            'financial_debt_to_ebitda' => $ebitda ? ($dettesFinancieres / $ebitda) : 0,
        ];
    }

    private function calculateSolvencyRatios($currentYearData)
    {
        $capitauxPropres = $this->getValueByLabel($currentYearData, 'Total des capitaux propres après résultat de l\'exercice');
        $ressourcesStables = $this->getValueByLabel($currentYearData, 'Total des capitaux propres et passifs');
        $totalBilan = $this->getValueByLabel($currentYearData, 'Total des actifs');

        return [
            'equity_to_stable_resources' => $ressourcesStables ? ($capitauxPropres / $ressourcesStables) : 0,
            'equity_to_total_assets' => $totalBilan ? ($capitauxPropres / $totalBilan) : 0,
        ];
    }

    private function getValueByLabel($data, $label)
    {
        return $data
            ->filter(function ($item) use ($label) {
                return isset($item->entryPoint) && $item->entryPoint->label === $label;
            })
            ->sum('value');
    }

    private function calculateEBITDA($currentYearData)
    {
        // Retrieve required values for EBITDA calculation
        $resultatExploitation = $this->getValueByLabel($currentYearData, 'Résultat d\'exploitation');
        $dotations = $this->getValueByLabel($currentYearData, 'Dotations aux amortissements et aux provisions');
        
        // Calculate EBITDA
        return $resultatExploitation + $dotations;
    }

    private function calculateCAF($currentYearData)
    {
        // Retrieve required values for CAF calculation
        $resultatNet = $this->getValueByLabel($currentYearData, 'Résultat net de l\'exercice');
        $dotations = $this->getValueByLabel($currentYearData, 'Dotations aux amortissements et aux provisions');
        $reprises = $this->getValueByLabel($currentYearData, 'Reprises sur provisions');
        $produitsCession = $this->getValueByLabel($currentYearData, 'Produits de cession d\'immobilisations');
        $valeursComptables = $this->getValueByLabel($currentYearData, 'Valeurs comptables des immobilisations cédées');

        // Calculate CAF
        return $resultatNet + $dotations - $reprises - $produitsCession + $valeursComptables;
    }


}