<?php

use App\Http\Controllers\MacroAnalysisController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MacroAnalysisController::class, 'index'])->name('dashboard');
Route::post('/analyze', [MacroAnalysisController::class, 'analyze'])->name('analyze');
Route::post('/sectors/sync', [MacroAnalysisController::class, 'syncSectors'])->name('sectors.sync');
