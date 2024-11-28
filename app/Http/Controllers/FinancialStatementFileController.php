<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FinancialStatementFile;

class FinancialStatementFileController extends Controller
{
    public function download($id)
    {
        $statement = FinancialStatementFile::findOrFail($id);
        $filePath = $statement->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($filePath, basename($filePath));
    }
}