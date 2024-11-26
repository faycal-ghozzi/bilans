<?php

namespace App\Http\Controllers;

use App\Services\FinancialRatioService;
use App\Models\FinancialStatementFile;
use Illuminate\Http\Request;

class FinancialAnalysisController extends Controller
{

    protected $financialRatioService;

    public function __construct(FinancialRatioService $financialRatioService)
    {
        $this->financialRatioService = $financialRatioService;
    }

    public function index($id)
    {
        $file = FinancialStatementFile::with('company')->findOrFail($id);
        $ratios = $this->financialRatioService->calculateRatios($id);

        return view('financial-analysis.results', [
            'file' => $file,
            'ratios' => $ratios
        ]);
    }
}
