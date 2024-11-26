<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FsEntryPoint;
use App\Models\Company;
use App\Models\FinancialStatementFile;
use App\Models\FinancialStatement;
use App\Services\FinancialStatementService;

class FinancialStatementController extends Controller
{

    protected $financialStatementService;

    public function __construct(FinancialStatementService $financialStatementService)
    {
        $this->financialStatementService = $financialStatementService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actifs = FsEntryPoint::where('category', 'like', 'Actifs%')->orderBy('id', 'asc')->get();
        $capitaux = FsEntryPoint::where('category', 'like', 'Capitaux%')->orderBy('id', 'asc')->get();
        $passifs = FsEntryPoint::where('category', 'like', '%Passifs%')->orderBy('id', 'asc')->get();
        $resultats = FsEntryPoint::where('category', 'like', 'Résultat de%')->orderBy('id', 'asc')->get();

        return view('financial-statement.create', compact('actifs', 'capitaux', 'passifs', 'resultats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function cleanNumericFields(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->cleanNumericFields($value);
            } elseif (is_string($value)) {
                if (preg_match('/^\d[\d\s\.]+$/', $value)) {
                    $data[$key] = preg_replace('/\s+/', '', $value);
                }
            }
        }
        return $data;
    }

    private function saveFile($filePath, $company, $date)
    {
        FinancialStatementFile::create([
            'company_id' => $company->id,
            'file_path' => $filePath,
            'currency' => 'TND',
            'date' => $date
        ]);
    }

    private function saveData($validatedDynamic, $company, $date)
    {
        $currentDate = \Carbon\Carbon::parse($date);

        foreach (['actifs', 'capitaux', 'passifs', 'resultats'] as $category) {
            if (isset($validatedDynamic[$category])) {
                foreach ($validatedDynamic[$category] as $id => $values) {
                    foreach ($values as $year => $value) {
                        $date = null;
                        if ($year === 'n') {
                            $date = $currentDate;
                        } elseif ($year === 'n-1') {
                            $date = $currentDate->copy()->subYear();
                        }

                        FinancialStatement::create([
                            'entry_point_id' => $id,
                            'date' => $date ? $date->format('Y-m-d') : null,
                            'value' => $value !== null ? (float)$value : 0.0,
                            'company_id' => $company->id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cleanedRequestData = $this->cleanNumericFields($request->all());
        $request->merge($cleanedRequestData);

        $validatedStatic = $request->validate([
            'company_name' => 'required|string|max:255',
            'current_year' => 'required|date',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $dynamicRules = [];
        foreach (['actifs', 'capitaux', 'passifs', 'resultats'] as $category) {
            if ($request->has($category)) {
                foreach ($request->$category as $id => $values) {
                    foreach ($values as $year => $value) {
                        $fieldKey = "{$category}.{$id}.{$year}";
                        $dynamicRules[$fieldKey] = 'nullable|numeric';
                    }
                }
            }
        }

        $validatedDynamic = $request->validate($dynamicRules);
        // TODO : check file upload
        $filePath = $request->file('file')->store('uploads', 'public');

        $company = Company::firstOrCreate(['name' => $validatedStatic['company_name']]);

        $this->saveFile($filePath, $company, $validatedStatic['current_year']);

        $this->saveData($validatedDynamic, $company, $validatedStatic['current_year']);

        return response()->json(['message' => 'Data saved successfully!']);
    }

    /**
     * Display All resources
     */
    public function fetchAll(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $financialStatements = \App\Models\FinancialStatementFile::with('company')
            ->when($search, function ($query, $search) {
                $query->whereHas('company', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->when($startDate, function ($query, $startDate) {
                $query->where('date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->where('date', '<=', $endDate);
            })
            ->paginate(10);

        if ($request->ajax()) {
            return response()->view('financial-statement.fetch-all.partials.table', compact('financialStatements'));
        }

        return view('financial-statement.fetch-all.fetch_all', compact('financialStatements', 'search', 'startDate', 'endDate'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data = $this->financialStatementService->getFinancialStatementDetails($id);

        return view('financial-statement.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
