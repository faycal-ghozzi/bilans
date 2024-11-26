<?php

namespace App\Http\Controllers;

use App\Services\FinancialRatioService;
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
        $ratios = $this->financialRatioService->calculateRatios($id);

        return view('financial-analysis.results', [
            'ratios' => $ratios
        ]);
    }
}
