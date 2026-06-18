<?php

use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home');
    
});
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'userHome'])->name('dashboard.index');
    Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
    
    Route::get('/reports/{id}', [BiomarkerReportController::class, 'showUserReport'])->name('reports.show');
    
});

require __DIR__.'/admin.php';