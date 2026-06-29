<?php

use App\Http\Controllers\ActionItem\ActionItemController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ScheduleRetestController;
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

Route::get('/register',        [SignUpController::class, 'showRegisterForm'])->name('register');
Route::post('/register',       [SignUpController::class, 'register']);
Route::get('/otp/verify',      [SignUpController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('/otp/verify',     [SignUpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend',     [SignUpController::class, 'resendOtp'])->name('otp.resend');

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    
    Route::post('/subscribe/checkout/{planId}', [SubscriptionController::class, 'createCheckoutSession'])->name('subscribe.checkout');
    
    Route::get('/subscription/success', function () { return view('user.subscription.success'); })->name('subscription.success');
    Route::get('/subscription/cancel', function () { return view('subscription.cancel'); })->name('subscription.cancel');
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard.index');
    Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
    Route::get('/health-insights', [UserController::class, 'helthInsight'])->name('health.insights');
    Route::get('/reports/{id}', [BiomarkerReportController::class, 'showUserReport'])->name('reports.show');
    Route::get('/kits', [KitController::class, 'index'])->name('kits.index');
    Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup.index');
    Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup.store');
    Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup.show');
    Route::patch('/pickup-requests/{id}/reschedule', [PickupRequestController::class, 'reschedule'])->name('pickup.reschedule');
    Route::patch('/pickup-requests/{id}/cancel', [PickupRequestController::class, 'cancel'])->name('pickup.cancel');

    Route::get('retests', [ScheduleRetestController::class, 'index'])->name('retests.index');
    Route::post('retests', [ScheduleRetestController::class, 'storeWeb'])->name('retests.store');
    Route::put('retests/{id}', [ScheduleRetestController::class, 'updateWeb'])->name('retests.update');
    Route::delete('retests/{id}', [ScheduleRetestController::class, 'destroyWeb'])->name('retests.destroy');

    Route::get('/action-item', [ActionItemController::class, 'index'])->name('actionitem.index');
    Route::get('/action-item/helth-profile', [ActionItemController::class, 'helthProfile'])->name('actionitem.helthprofile');
    Route::post('/health-profile', [ActionItemController::class, 'storeHelthProfile'])->name('health-profile.store');
    Route::get('/action-item/instruction', [ActionItemController::class, 'instruction'])->name('actionitem.instruction');
    Route::post('/action-items/mark-viewed', [ActionItemController::class, 'markViewed'])->name('action-items.mark-viewed');
    Route::get('/action-item/questionnaire', [ActionItemController::class, 'questionnaire'])->name('actionitem.questionnaire');
    Route::post('/kit-questionnaire', [ActionItemController::class, 'storeKitQuestionnaire'])->name('kit-questionnaire.store');
});

require __DIR__.'/admin.php';