<?php

use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('user.home');
    
// });
Route::get('/terms-and-condition', function () {
    return view('user.terms-and-condition');
    
});

Route::get('/laboratory-services-consent', function () {
    return view('user.laboratory-services');
    
});
Route::get('/pricing', [SubscriptionPlanController::class, 'showPricingPage'])->name('pricing.page');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'frontIndex'])->name('privacy.policy');

Route::get('/', [UserController::class, 'userHome'])->name('home.index');
Route::post('/stripe/webhook', [SubscriptionController::class, 'handleWebhook'])->name('stripe.webhook');
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    
    Route::post('/subscribe/checkout/{planId}', [SubscriptionController::class, 'createCheckoutSession'])->name('subscribe.checkout');
    
    Route::get('/subscription/success', function () { return view('user.subscription.success'); })->name('subscription.success');
    Route::get('/subscription/cancel', function () { return view('subscription.cancel'); })->name('subscription.cancel');
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard.index');
    Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
    Route::get('/health-insights', [UserController::class, 'helthInsight'])->name('health.insights');
    Route::get('/reports/{id}', [BiomarkerReportController::class, 'showUserReport'])->name('reports.show');
});

require __DIR__.'/admin.php';