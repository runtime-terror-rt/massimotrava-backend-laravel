<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| সব admin route এখানে থাকবে।
| Middleware: 'auth' + 'admin' (AdminMiddleware)
|
| bootstrap/app.php বা Kernel.php-তে alias যোগ করুন:
|   'admin' => \App\Http\Middleware\AdminMiddleware::class,
|--------------------------------------------------------------------------
*/
Route::get('/login', [DashboardController::class, 'login'])->name('login');
Route::get('/register',          [DashboardController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
          Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
          Route::get('/edit/profile', [UserController::class, 'editProfile'])->name('profile.edit');
          Route::post('/profile', [UserController::class, 'updateProfile'])->name('update.profile');
          Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
          Route::get('get/users', [UserController::class, 'getUsers'])->name('get.users');
          Route::get('get/lab/users', [UserController::class, 'getLabUsers'])->name('get.lab.users');
          Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
          // Dashboard
          Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');
          Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
          
          // Analytics (future)
          Route::get('/analytics', fn() => view('admin.analytics.index'))->name('analytics');

          // Reports (future)
          Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
          Route::get('/reports/create', [BiomarkerReportController::class, 'create'])->name('reports.create');
          Route::delete('/reports/{id}', [BiomarkerReportController::class, 'destroy'])->name('reports.destroy');
          Route::post('/reports/store', [BiomarkerReportController::class, 'storeReport'])->name('reports.store');

          //Kits 
          Route::get('/kits', [KitController::class, 'index'])->name('kits.index');
          Route::get('/get-user-kits', [KitController::class, 'getUserKits'])->name('get-user-kits');
          Route::post('/kits/activate', [KitController::class, 'activateKit'])->name('kits.activate');
          Route::delete('/kits/{id}', [KitController::class, 'destroy'])->name('kits.destroy');
          Route::get('admin/get-subcategories', [KitController::class, 'getSubcategories'])->name('get-subcategories');


    });
