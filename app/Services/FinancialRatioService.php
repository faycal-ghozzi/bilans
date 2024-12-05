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
            'rentabilité' => $this->calculateRentabiliteRatios($currentYearData, $previousYearData),
            'structure_financière' => $this->calculateStructureFinanciereRatios($currentYearData, $previousYearData),
            'liquidité' => $this->calculateLiquiditeRatios($currentYearData, $previousYearData),
            'endettement' => $this->calculateEndettementRatios($currentYearData, $previousYearData),
            'solvabilité' => $this->calculateSolvabiliteRatios($currentYearData, $previousYearData),
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

        $ca = [
            'n' => $this->getValueByLabel($currentYearData, 'Revenus'),
            'n-1' => $this->getValueByLabel($previousYearData, 'Revenus'),
        ];

        $ebe = [
            'n' => $this->getValueByLabel($currentYearData, 'EBE'),
            'n-1' => $this->getValueByLabel($previousYearData, 'EBE'),
        ];

        return [
            'retour_sur_capitaux_propres_exploitation' => $this->calculateEvolutions(
                $capitauxPropres['n'] ? $resultatExploitation['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $resultatExploitation['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            'retour_sur_capitaux_propres_net' => $this->calculateEvolutions(
                $capitauxPropres['n'] ? $ca['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $ca['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            'ebe_ca' => $this->calculateEvolutions(
                $ca['n'] ? $ebe['n'] / $ca['n'] : 0,
                $ca['n-1'] ? $ebe['n-1'] / $ca['n-1'] : 0
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
            'fr' => $this->calculateEvolutions($fr['n'], $fr['n-1']),
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
            'liquidite_generale' => $this->calculateEvolutions(
                $passifCirculant['n'] ? $actifCirculant['n'] / $passifCirculant['n'] : 0,
                $passifCirculant['n-1'] ? $actifCirculant['n-1'] / $passifCirculant['n-1'] : 0
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
