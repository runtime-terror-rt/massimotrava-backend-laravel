<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Lab;
use App\Observers\AuditObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        User::observe(AuditObserver::class);
        
        if (class_exists(Lab::class)) {
            Lab::observe(AuditObserver::class);
        }
        
         \App\Models\BiomarkerReport::observe(AuditObserver::class);
         \App\Models\BiomarkerCategory::observe(AuditObserver::class);
         \App\Models\BiomarkerSubcategory::observe(AuditObserver::class);
         \App\Models\Faq::observe(AuditObserver::class);
         \App\Models\Content::observe(AuditObserver::class);
         \App\Models\Campaign::observe(AuditObserver::class);
         \App\Models\Role::observe(AuditObserver::class);
         \App\Models\Permission::observe(AuditObserver::class);
         \App\Models\ScheduleRetest::observe(AuditObserver::class);
         \App\Models\SubscriptionPlan::observe(AuditObserver::class);
         \App\Models\Subscription::observe(AuditObserver::class);
         \App\Models\contacts::observe(AuditObserver::class);
         \App\Models\PrivacyPolicy::observe(AuditObserver::class);
    }
}