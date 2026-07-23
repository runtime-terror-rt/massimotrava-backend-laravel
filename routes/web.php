<?php

use App\Http\Controllers\ActionItem\ActionItemController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleRetestController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Route::get('/', function () {
//     return view('user.home');
    
// });
Route::get('/terms-and-condition', function () {
    return view('user.terms-and-condition');
    
});

Route::get('/laboratory-services-consent', function () {
    return view('user.laboratory-services');
    
});

Route::post('/newsletter/subscribe', [NewsletterSubscriberController::class, 'subscribe'])
        ->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{subscriber}', [NewsletterSubscriberController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe')
    ->middleware('signed');

Route::get('/pricing', [SubscriptionPlanController::class, 'showPricingPage'])->name('pricing.page');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'frontIndex'])->name('privacy.policy');

Route::get('/', [UserController::class, 'userHome'])->name('home.index');
Route::post('/stripe/webhook', [SubscriptionController::class, 'handleWebhook'])->name('stripe.webhook');

Route::get('/register',        [SignUpController::class, 'showRegisterForm'])->name('register');
Route::post('/register',       [SignUpController::class, 'register']);
Route::get('/otp/verify',      [SignUpController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('/otp/verify',     [SignUpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend',     [SignUpController::class, 'resendOtp'])->name('otp.resend');
Route::get('/reviews', [ReviewController::class, 'FrontIndex'])->name('review.index');
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/edit/profile', [UserController::class, 'userEditProfile'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'userUpdateProfile'])->name('update.profile');
    Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('update.password');
    Route::post('/subscribe/checkout/{planId}', [SubscriptionController::class, 'createCheckoutSession'])->name('subscribe.checkout');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/subscription/success', function () { return view('user.subscription.success'); })->name('subscription.success');
    Route::get('/subscription/cancel', function () { return view('subscription.cancel'); })->name('subscription.cancel');
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard.index');
    Route::get('/reports', [BiomarkerReportController::class, 'index'])->name('reports.index');
    Route::get('/health-insights', [UserController::class, 'helthInsight'])->name('health.insights');
    Route::get('/report/show/{inv_code}', [BiomarkerReportController::class, 'userReportShow'])->name('show.reports');
    
    Route::get('/reports/{id}', [BiomarkerReportController::class, 'showUserReport'])->name('reports.show');
    Route::get('/kits', [KitController::class, 'index'])->name('kits.index');
    Route::get('/get-user-kits', [KitController::class, 'getUserKits'])->name('get-user-kits');
    Route::post('/kit/activate', [KitController::class, 'activateKit'])->name('kits.activate');
    Route::get('/my-kits', [KitController::class, 'myKits'])->name('user.kits.index');

    // User schedules sample pickup
    Route::post('/kits/{id}/schedule-pickup', [KitController::class, 'schedulePickup'])->name('kits.schedule-pickup');
    
    Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup.index');
    Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup.store');
    Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup.show');
    Route::patch('/user/pickup/{id}/reschedule', [PickupRequestController::class, 'reschedule'])->name('pickup.reschedule');
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

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/notifications/edit', [NotificationSettingController::class, 'edit']);
    Route::post('/notifications/toggle', [NotificationSettingController::class, 'toggle']);

    Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription'])->name('subscription.my');

});

require __DIR__.'/admin.php';

Route::get('/test-sms', function () {

    $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
        ->asForm()
        ->post(
            "https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json",
            [
                'To' => '+8801631382236',
                'From' => env('TWILIO_NUMBER'),
                'Body' => 'Laravel Test SMS',
            ]
        );

    dd(
        $response->status(),
        $response->json(),
        $response->body()
    );
});