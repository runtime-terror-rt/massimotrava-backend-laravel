<?php

use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('user.home');
    
// });


Route::get('/', [UserController::class, 'userHome'])->name('home.index');

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard.index');
    Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
    
    Route::get('/reports/{id}', [BiomarkerReportController::class, 'showUserReport'])->name('reports.show');
    
});

require __DIR__.'/admin.php';