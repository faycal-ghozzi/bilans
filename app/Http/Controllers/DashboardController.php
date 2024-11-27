<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\FinancialStatementFile;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Metrics
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalFinancialStatements = FinancialStatementFile::count();

        // Financial Statements per Month (last year)
        $financialStatementsByMonth = FinancialStatementFile::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Data for chart (fill missing months with zero)
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $financialStatementsByMonth->get($i)->count ?? 0;
        }

        return view('dashboard', compact('totalCompanies', 'totalUsers', 'totalFinancialStatements', 'monthlyData'));
    }
}
