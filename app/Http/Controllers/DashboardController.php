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
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalFinancialStatements = FinancialStatementFile::count();

        $minYear = FinancialStatementFile::min('date') ? Carbon::parse(FinancialStatementFile::min('date'))->year : Carbon::now()->year;
        $maxYear = FinancialStatementFile::max('date') ? Carbon::parse(FinancialStatementFile::max('date'))->year : Carbon::now()->year;

        $viewType = $request->input('viewType', 'year');
        $selectedYear = $request->input('selectedYear', $maxYear);

        if ($viewType === 'year') {
            $financialStatements = FinancialStatementFile::selectRaw('YEAR(date) as period, COUNT(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->keyBy('period');

            $labels = range($minYear, $maxYear);
            $chartData = [];
            foreach ($labels as $year) {
                $chartData[] = $financialStatements->get($year)->count ?? 0;
            }
        } else {
            $financialStatements = FinancialStatementFile::selectRaw('MONTH(date) as period, COUNT(*) as count')
                ->whereYear('date', $selectedYear)
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->keyBy('period');

            $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            $chartData = [];
            for ($i = 1; $i <= 12; $i++) {
                $chartData[] = $financialStatements->get($i)->count ?? 0;
            }
        }

        return view('dashboard', compact(
            'totalCompanies', 
            'totalUsers', 
            'totalFinancialStatements', 
            'minYear', 
            'maxYear', 
            'viewType', 
            'selectedYear', 
            'labels', 
            'chartData'
        ));
    }
}
