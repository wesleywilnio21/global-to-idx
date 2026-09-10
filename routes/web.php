<?php

use App\Http\Controllers\HeadToHeadDuelController;
use App\Http\Controllers\MacroAnalysisController;
use App\Http\Controllers\MacroReportController;
use App\Http\Controllers\PortfolioStressTestController;
use App\Http\Controllers\VulnerabilityScannerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [MacroAnalysisController::class, 'index'])->name('dashboard');
Route::post('/analyze', [MacroAnalysisController::class, 'analyze'])->name('analyze');
Route::post('/sectors/sync', [MacroAnalysisController::class, 'syncSectors'])->name('sectors.sync');
Route::match(['get', 'post'], '/portfolio', [PortfolioStressTestController::class, 'index'])->name('portfolio.index');
Route::match(['get', 'post'], '/duel', [HeadToHeadDuelController::class, 'index'])->name('duel.index');
Route::get('/report', [MacroReportController::class, 'tearSheet'])->name('report.tear-sheet');
Route::match(['get', 'post'], '/scanner', [VulnerabilityScannerController::class, 'index'])->name('scanner.index');

Route::get('/upload-preview', function () {
    return view('upload-preview');
})->name('upload.preview');

Route::post('/upload-preview', function (Request $request) {
    $request->validate([
        'screenshot' => ['required', 'image', 'max:15360'],
    ]);

    $file = $request->file('screenshot');
    $file->move(public_path(), 'screenshot.png');

    return response()->json([
        'success' => true,
        'path' => '/screenshot.png',
    ]);
});
