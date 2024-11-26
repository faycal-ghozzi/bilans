<?php

namespace App\Http\Controllers;

use App\Services\FinancialStatementService;
use Illuminate\Http\Request;

class FinancialAnalysisController extends Controller
{

    protected $financialStatementService;

    public function __construct(FinancialStatementService $financialStatementService)
    {
        $this->financialStatementService = $financialStatementService;
    }

    public function index($id)
    {
        $data = $this->financialStatementService->getFinancialStatementDetails($id);

        var_dump($data['financialStatements']);
    }
}
