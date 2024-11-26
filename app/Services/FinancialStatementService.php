<?php

namespace App\Services;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementFile;
use Carbon\Carbon;

class FinancialStatementService
{
    public function getFinancialStatementDetails($id)
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

        $financialStatements[$dateCurrentYear] = $financialStatements[$dateCurrentYear] ?? collect([]);
        $financialStatements[$datePreviousYear] = $financialStatements[$datePreviousYear] ?? collect([]);

        $categories = $financialStatements
            ->flatMap(function ($statements) {
                return $statements->pluck('entryPoint');
            })
            ->unique('id')
            ->groupBy('category');
        
        return [
            'file' => $file,
            'financialStatements' => $financialStatements,
            'categories' => $categories,
            'dateCurrentYear' => $dateCurrentYear,
            'datePreviousYear' => $datePreviousYear,
        ];
    }

    
}