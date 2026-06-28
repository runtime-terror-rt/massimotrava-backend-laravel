<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'billing_cycle',
        'price',
        'duration',
        'features',
        'status',
        'stripe_product_id',
        'stripe_price_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'features' => 'array',   
        'status'   => 'boolean',
        'price'    => 'decimal:2',
    ];

    /**
     * Get the user (admin/owner) who created the subscription plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the users currently subscribed to this plan.
     */
    public function subscribers()
    {
        return $this->hasMany(User::class, 'subscription_plan_id');
    }
}