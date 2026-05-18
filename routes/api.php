<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\BiomarkerCategoryController;
use App\Http\Controllers\BiomarkerReportController;
use App\Http\Controllers\BiomarkerSubcategoryController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\Courier\CourierTrackController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AppleAuthController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\UserController;


Route::prefix('v1')->group(function () {

    // ----------------------------
    // Public Routes
    // ----------------------------
    Route::post('register', [SignUpController::class, 'register']);

    Route::post('login', [LoginController::class, 'login']);
    Route::post('resend-otp', [LoginController::class, 'resendOtp']);

    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);

    //google login
    Route::post('/auth/google', [GoogleAuthController::class, 'tokenLogin']);
    //apple login
    Route::post('/auth/apple', [AppleAuthController::class, 'tokenLogin']);

    Route::get('privacy-policy', [PrivacyPolicyController::class, 'index']);
    Route::post('/contact', [ContactsController::class, 'store']);
    Route::get('/faqs', [FaqController::class, 'index']);

    Route::get('plans', [SubscriptionPlanController::class, 'index']);
    Route::get('plans/{id}', [SubscriptionPlanController::class, 'show']);

    Route::post('/pickup', [ShippingController::class, 'requestPickup']);
    Route::post('/track', [ShippingController::class, 'trackShipment']);
    Route::get('/track-shipment/{trackingNumber}', [ShippingController::class, 'trackShipment']);

    Route::get('/track-shipment', [CourierTrackController::class, 'trackShipment']);

    // ----------------------------
    // Protected Routes (Require Auth)
    // ----------------------------
    Route::middleware('auth:sanctum')->group(function () {

        //Profile
        Route::get('/profile', [UserProfileController::class, 'show']);
        Route::post('/profile', [UserProfileController::class, 'updateProfile']);

        Route::post('logout', [LoginController::class, 'logout']);

        // Privacy Policy Management
        Route::post('save-terms', [PrivacyPolicyController::class, 'save']);

        // User Management
        Route::get('/admin/users', [AdminController::class, 'getUser']);
        Route::get('/admin/users/{id}', [AdminController::class, 'getUserById']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
        Route::post('/users/toggle-active/{id}', [AdminController::class, 'toggleActiveUser']);

        // FAQ Management
        Route::get('/admin/faqs', [FaqController::class, 'adminIndex']);
        Route::post('/faqs', [FaqController::class, 'storeOrUpdate']);
        Route::delete('/faqs/{id}', [FaqController::class, 'destroy']);
        Route::post('/faqs/status/{id}', [FaqController::class, 'toggleActive']);

        // Contact Management
        Route::post('/contact', [ContactsController::class, 'store']);
        Route::get('/admin/contacts', [ContactsController::class, 'index']);

        // Kit Management
        Route::get('/kits', [KitController::class, 'index']);
        Route::post('/kit/activate', [KitController::class, 'activateKit']);
        Route::get('/my-kits', [KitController::class, 'myKits']);
        Route::delete('/kit/{id}', [KitController::class, 'destroy']);

        // Biomarker Categories
        Route::get('/biomarker-categories', [BiomarkerCategoryController::class, 'index']);
        Route::post('/biomarker-category/store', [BiomarkerCategoryController::class, 'storeCategory']);
        Route::delete('/biomarker-category/{id}', [BiomarkerCategoryController::class, 'destroy']);

        // Biomarker Subcategories
        Route::get('/biomarker-subcategories', [BiomarkerSubcategoryController::class, 'index']);
        Route::get('/biomarker-subcategories/{categoryId}', [BiomarkerSubcategoryController::class, 'getSubcategories']);
        Route::post('/biomarker-subcategory/store', [BiomarkerSubcategoryController::class, 'storeSubcategory']);
        Route::delete('/biomarker-subcategory/{id}', [BiomarkerSubcategoryController::class, 'destroy']);

        // Biomarker Report
        Route::post('/biomarker-report/store', [BiomarkerReportController::class, 'storeReport']);
        Route::get('/biomarker-reports', [BiomarkerReportController::class, 'index']);
        Route::get('/get-reports', [BiomarkerReportController::class, 'getReports']);
        Route::get('/user/reports', [BiomarkerReportController::class, 'getUserReports']);

        //Labs 
        Route::get('/labs', [LabController::class, 'index']);
        Route::post('/labs/store', [LabController::class, 'store']);

        // Subscription Plan Management
        Route::post('/plans/store-or-update', [SubscriptionPlanController::class, 'storeOrUpdatePlan']);
        Route::delete('/plans/{id}', [SubscriptionPlanController::class, 'destroy']);
        Route::post('/plans/toggle-status/{id}', [SubscriptionPlanController::class, 'toggleStatus']);

    });
});
