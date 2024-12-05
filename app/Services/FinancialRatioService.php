<?php

namespace App\Services;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementFile;
use Carbon\Carbon;

class FinancialRatioService
{
    public function calculateRatios($id)
    {
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

        return [
            'Rentabilité' => $this->calculateRentabiliteRatios($currentYearData, $previousYearData),
            'Structure Financière' => $this->calculateStructureFinanciereRatios($currentYearData, $previousYearData),
            'Liquidité' => $this->calculateLiquiditeRatios($currentYearData, $previousYearData),
            'Endettement' => $this->calculateEndettementRatios($currentYearData, $previousYearData),
            'Solvabilité' => $this->calculateSolvabiliteRatios($currentYearData, $previousYearData),
        ];
    }

    private function calculateEvolutions($currentValue, $previousValue)
    {
        $absolute = $currentValue - $previousValue;
        $percentage = $previousValue != 0 ? ($absolute / $previousValue) * 100 : 0;

        return [
            'valeur' => $currentValue,
            'n_1' => $previousValue,
            'absolue' => $absolute,
            'pourcentage' => $percentage,
        ];
    }

    private function calculateRentabiliteRatios($currentYearData, $previousYearData)
    {
        $resultatExploitation = [
            'n' => $this->getValueByLabel($currentYearData, 'Résultat d\'exploitation'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Résultat d\'exploitation'),
        ];

        $capitauxPropres = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des capitaux propres après résultat de l\'exercice'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des capitaux propres après résultat de l\'exercice'),
        ];

        return [
            'Retour sur capitaux propres (Résultat d\'exploitation)' => $this->calculateEvolutions(
                $capitauxPropres['n'] ? $resultatExploitation['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $resultatExploitation['n-1'] / $capitauxPropres['n-1'] : 0
            ),
        ];
    }

    private function calculateStructureFinanciereRatios($currentYearData, $previousYearData)
    {
        $ressourcesStables = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des capitaux propres et passifs'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des capitaux propres et passifs'),
        ];

        $actifsImmobiles = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des actifs immobilisés'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des actifs immobilisés'),
        ];

        $fr = [
            'n' => $ressourcesStables['n'] - $actifsImmobiles['n'],
            'n-1' => $ressourcesStables['n-1'] - $actifsImmobiles['n-1'],
        ];

        return [
            'Fonds de Roulement (FR)' => $this->calculateEvolutions($fr['n'], $fr['n-1']),
        ];
    }

    private function calculateLiquiditeRatios($currentYearData, $previousYearData)
    {
        $actifCirculant = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des actifs courants'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des actifs courants'),
        ];

        $passifCirculant = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des passifs courants'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des passifs courants'),
        ];

        return [
            'Liquidité Générale' => $this->calculateEvolutions(
                $passifCirculant['n'] ? $actifCirculant['n'] / $passifCirculant['n'] : 0,
                $passifCirculant['n-1'] ? $actifCirculant['n-1'] / $passifCirculant['n-1'] : 0
            ),
        ];
    }

    private function calculateEndettementRatios($currentYearData, $previousYearData)
    {
        $chargesFinancieres = [
            'n' => $this->getValueByLabel($currentYearData, 'Charges financières nettes'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Charges financières nettes'),
        ];

        return [
            'Charges Financières / Revenus' => $this->calculateEvolutions(
                $chargesFinancieres['n'],
                $chargesFinancieres['n-1']
            ),
        ];
    }

    private function calculateSolvabiliteRatios($currentYearData, $previousYearData)
    {
        $capitauxPropres = [
            'n' => $this->getValueByLabel($currentYearData, 'Total des capitaux propres après résultat de l\'exercice'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Total des capitaux propres après résultat de l\'exercice'),
        ];

        return [
            'Capitaux Propres / Ressources Stables' => $this->calculateEvolutions(
                $capitauxPropres['n'],
                $capitauxPropres['n-1']
            ),
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
}
