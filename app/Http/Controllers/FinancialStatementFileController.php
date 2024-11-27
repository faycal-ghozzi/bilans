<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FinancialStatementFile;

class FinancialStatementFileController extends Controller
{
    public function download($id)
    {
        // Retrieve the financial statement file
        $statement = FinancialStatementFile::findOrFail($id);

        // Get the file path
        $filePath = $statement->file_path;

        // Check if the file exists on the server
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Serve the file for download
        return Storage::disk('public')->download($filePath, basename($filePath));
    }
}