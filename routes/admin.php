<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BiomarkerCategoryController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\BiomarkerSubcategoryController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [DashboardController::class, 'login'])->name('login');
Route::get('/register',          [DashboardController::class, 'register'])->name('register');
Route::get('/forgot-password', [DashboardController::class, 'forgotPassword'])->name('password.request');
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
            Route::post('/reports/send-email', [BiomarkerReportController::class, 'sendEmail'])->name('reports.send.email');

            // ✅ PDF DOWNLOAD ROUTE (ADD THIS)
            Route::get('/reports/download-pdf', [BiomarkerReportController::class, 'downloadPdf'])->name('reports.download.pdf');

            //Kits 
            Route::get('/kits', [KitController::class, 'index'])->name('kits.index');
            Route::get('/get-user-kits', [KitController::class, 'getUserKits'])->name('get-user-kits');
            Route::post('/kits/activate', [KitController::class, 'activateKit'])->name('kits.activate');
            Route::delete('/kits/{id}', [KitController::class, 'destroy'])->name('kits.destroy');
            Route::get('/get-subcategories', [KitController::class, 'getSubcategories'])->name('get-subcategories');

            // Category 
            Route::get('/category', [BiomarkerCategoryController::class, 'index'])->name('category.index');
            Route::post('/category', [BiomarkerCategoryController::class, 'storeCategory'])->name('categories.store');
            Route::delete('/biomarker-category/{id}', [BiomarkerCategoryController::class, 'destroy'])->name('categories.delete');

            // Sub Category
            Route::get('/biomarker-subcategories', [BiomarkerSubcategoryController::class, 'index'])->name('biomarker-subcategories.index');
            Route::get('/biomarker-subcategories/{categoryId}', [BiomarkerSubcategoryController::class, 'getSubcategories'])->name('biomarker-subcategories.index');
            Route::post('/biomarker-subcategory/store', [BiomarkerSubcategoryController::class, 'storeSubcategory'])->name('biomarker-subcategory.store');
            Route::delete('/biomarker-subcategory/{id}', [BiomarkerSubcategoryController::class, 'destroy'])->name('biomarker-subcategory.delete');

            Route::resource('labs', LabController::class)->only(['index', 'store', 'destroy']);

            Route::post('/admin/lab-users/store', [AdminController::class, 'storeLabUser'])->name('lab-users.store');

            Route::post('/admin/update-password', [LoginController::class, 'updatePassword'])->name('update.password');

            Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
            Route::post('/privacy-policy/save', [PrivacyPolicyController::class, 'save'])->name('privacy-policy.save');
            Route::delete('/privacy-policy/{id}', [PrivacyPolicyController::class, 'destroy'])->name('privacy-policy.destroy');
    });
