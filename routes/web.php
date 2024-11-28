<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialAnalysisController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\FinancialStatementFileController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/financial-statement/download/{id}', [FinancialStatementFileController::class, 'download'])->middleware(['auth', 'verified'])->name('financial-statement.download');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function(){
    Route::get('/fs/add', [FinancialStatementController::class, 'index'])->name('financial-statement.create');
    Route::post('/fs/store', [FinancialStatementController::class, 'store'])->name('financial-statement.store');
    Route::get('/fs/all', [FinancialStatementController::class, 'fetchAll'])->name('financial-statement.fetch_all');
    Route::get('/fs/{id}', [FinancialStatementController::class, 'show'])->name('financial-statement.show');

    Route::get('/fs/{id}/analysis', [FinancialAnalysisController::class, 'index'])->name('financial.analysis');
});



require __DIR__.'/auth.php';
