<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'billing_cycle',
        'stripe_price_id',
        'stripe_product_id',
        'price',
        'duration',
        'features',
        'status',
        'kit_limit',
    ];

    protected $casts = [
        'features' => 'array',
        'price'    => 'decimal:2',
        'status'   => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscription_plan_id');
    }
}