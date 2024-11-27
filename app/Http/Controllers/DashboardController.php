<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\FinancialStatementFile;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Metrics
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalFinancialStatements = FinancialStatementFile::count();

        // Date range for financial statements
        $minYear = FinancialStatementFile::min('date') ? Carbon::parse(FinancialStatementFile::min('date'))->year : Carbon::now()->year;
        $maxYear = Carbon::now()->year;

        // Year and grouping type from request
        $selectedYear = $request->input('year', $maxYear);
        $groupBy = $request->input('groupBy', 'month'); // Default: month

        // Data for graph
        $financialStatements = FinancialStatementFile::selectRaw(
            $groupBy === 'month' 
                ? 'MONTH(date) as period, COUNT(*) as count' 
                : 'YEAR(date) as period, COUNT(*) as count'
        )
        ->whereYear('date', $selectedYear)
        ->groupBy('period')
        ->orderBy('period')
        ->get()
        ->keyBy('period');

        // Prepare data for the graph
        if ($groupBy === 'month') {
            $chartData = [];
            for ($i = 1; $i <= 12; $i++) {
                $chartData[] = $financialStatements->get($i)->count ?? 0;
            }
        } else {
            $chartData = $financialStatements->pluck('count')->toArray();
        }

        return view('dashboard', compact(
            'totalCompanies', 
            'totalUsers', 
            'totalFinancialStatements', 
            'minYear', 
            'maxYear', 
            'selectedYear', 
            'chartData', 
            'groupBy'
        ));
    }
}
