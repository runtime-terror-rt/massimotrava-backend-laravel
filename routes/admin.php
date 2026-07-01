<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AdminPickupRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BiomarkerCategoryController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\BiomarkerSubcategoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

// Authentication Routes
Route::get('/login', [DashboardController::class, 'login'])->name('login');
Route::get('/register', [DashboardController::class, 'register'])->name('register');
Route::get('/forgot-password', [DashboardController::class, 'forgotPassword'])->name('password.request');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'it', 'de'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Protected Admin Dashboard Domain Matrix
Route::prefix('admin')
      ->name('admin.')
      ->middleware(['auth', 'admin']) // Keeps authorization layer secure
      ->group(function () {
            
            Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
            Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard.index');
            // Profile Matrix Registry
            Route::get('/edit/profile', [UserController::class, 'editProfile'])->name('profile.edit');
            Route::post('/profile', [UserController::class, 'updateProfile'])->name('update.profile');
            Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
            Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('update.password');
            
            // User Subsystem Control Directories
            Route::get('get/users', [UserController::class, 'getUsers'])->name('get.users');
            Route::get('get/lab/users', [UserController::class, 'getLabUsers'])->name('get.lab.users');
            Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
            Route::post('/lab-users/store', [AdminController::class, 'storeLabUser'])->name('lab-users.store');
            Route::delete('/lab-users/{id}', [AdminController::class, 'labUsersDestroy'])->name('lab-users.destroy');
            
            // Central Analytics Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
            Route::get('/analytics', fn() => view('admin.analytics.index'))->name('analytics');

            // Medical/Biomarker Reports Management Pipeline
            Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/create', [BiomarkerReportController::class, 'create'])->name('reports.create');
            Route::delete('/reports/{id}', [BiomarkerReportController::class, 'destroy'])->name('reports.destroy');
            Route::post('/reports/store', [BiomarkerReportController::class, 'storeReport'])->name('reports.store');
            Route::post('/reports/send-email', [BiomarkerReportController::class, 'sendEmail'])->name('reports.send.email');
            Route::get('/reports/download-pdf', [BiomarkerReportController::class, 'downloadPdf'])->name('reports.download.pdf');
            Route::get('/reports/{id}/edit', [BiomarkerReportController::class, 'edit'])->name('reports.edit');
            Route::put('/reports/{id}', [BiomarkerReportController::class, 'update'])->name('reports.update');
            Route::get('/reports/{id}', [BiomarkerReportController::class, 'show'])->name('reports.show');

            // Kiosk & Medical Kits Dispatch Control
            Route::get('/kits', [KitController::class, 'index'])->name('kits.index');
            Route::get('/get-user-kits', [KitController::class, 'getUserKits'])->name('get-user-kits');
            Route::post('/kits/activate', [KitController::class, 'activateKit'])->name('kits.activate');
            Route::delete('/kits/{id}', [KitController::class, 'destroy'])->name('kits.destroy');
            Route::get('/get-subcategories', [KitController::class, 'getSubcategories'])->name('get-subcategories');

            // Category & Sub Category Node System 
            Route::get('/category', [BiomarkerCategoryController::class, 'index'])->name('category.index');
            Route::post('/category', [BiomarkerCategoryController::class, 'storeCategory'])->name('categories.store');
            Route::delete('/biomarker-category/{id}', [BiomarkerCategoryController::class, 'destroy'])->name('categories.delete');

            Route::get('/biomarker-subcategories', [BiomarkerSubcategoryController::class, 'index'])->name('biomarker-subcategories.index');
            Route::get('/biomarker-subcategories/{categoryId}', [BiomarkerSubcategoryController::class, 'getSubcategories'])->name('biomarker-subcategorie');
            Route::post('/biomarker-subcategory/store', [BiomarkerSubcategoryController::class, 'storeSubcategory'])->name('biomarker-subcategory.store');
            Route::delete('/biomarker-subcategory/{id}', [BiomarkerSubcategoryController::class, 'destroy'])->name('biomarker-subcategory.delete');

            // Laboratories Management Index
            Route::resource('labs', LabController::class)->only(['index', 'store', 'destroy']);

            // Legal & System Utilities Configuration Section
            Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
            Route::post('/privacy-policy/save', [PrivacyPolicyController::class, 'save'])->name('privacy-policy.save');
            Route::delete('/privacy-policy/{id}', [PrivacyPolicyController::class, 'destroy'])->name('privacy-policy.destroy');

            // Central FAQs Engine
            Route::get('faq/', [FaqController::class, 'adminIndex'])->name('faq.index');
            Route::post('faq/store', [FaqController::class, 'storeOrUpdate'])->name('faq.store');
            Route::get('faq/{id}', [FaqController::class, 'show'])->name('faq.show');
            Route::post('faq/{id}/toggle', [FaqController::class, 'toggleActive'])->name('faq.toggle');
            Route::delete('faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');

            // Core Dynamic Authorization: Spatie Role-Permission Sub-system
            Route::get('role-permission/', [RolePermissionController::class, 'index'])->name('role-permission.index');
            Route::post('role-permission/save', [RolePermissionController::class, 'storeOrUpdate'])->name('role-permission.save');
            Route::delete('role-permission/{id}/delete', [RolePermissionController::class, 'destroy'])->name('role-permission.destroy');

            Route::get('/contents/create', [ContentController::class, 'create'])->name('contents.create');
            Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
            Route::post('/contents', [ContentController::class, 'store'])->name('contents.store');
            Route::get('/contents/{id}/edit', [ContentController::class, 'edit'])->name('contents.edit');
            Route::put('/contents/{id}', [ContentController::class, 'update'])->name('contents.update');
            Route::delete('/contents/{id}', [ContentController::class, 'destroy'])->name('contents.destroy');

            Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
            Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
            Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
            Route::get('/security-audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

             
            Route::get('/payments', [SubscriptionPlanController::class, 'index'])->name('payments.index');
            Route::post('/plans', [SubscriptionPlanController::class, 'store'])->name('plans.store');
            Route::put('/plans/{id}', [SubscriptionPlanController::class, 'update'])->name('plans.update');
            Route::delete('/plans/{id}', [SubscriptionPlanController::class, 'destroy'])->name('plans.destroy');

            Route::get('/pickup-requests', [AdminPickupRequestController::class, 'index'])->name('pickup.index');
            Route::get('/pickup-requests/{id}', [AdminPickupRequestController::class, 'show'])->name('pickup.show');
            Route::patch('/pickup/{id}/schedule', [AdminPickupRequestController::class, 'schedule'])->name('pickup.schedule');
            Route::patch('/pickup/{id}/collect', [AdminPickupRequestController::class, 'collect'])->name('pickup.collect');
            Route::patch('/pickup/{id}/cancel', [AdminPickupRequestController::class, 'cancel'])->name('pickup.cancel');
            Route::resource('reviews', ReviewController::class);
            Route::post('/reviews/{review}/toggle-status', [ReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');
            Route::get('/reviews', [ReviewController::class, 'FrontIndex'])->name('review.index');
    });