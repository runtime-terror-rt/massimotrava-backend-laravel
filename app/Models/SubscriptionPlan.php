<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'plan_type',
        'user_id',
        'billing_cycle',
        'price',
        'duration',
        'member_limit',
        'features',
        'status',
        'projection_limit'
    ];

    protected $casts = [
        'features' => 'array',   
        'status'   => 'boolean',
        'price'    => 'decimal:2',
    ];

    // Plan creator (admin / owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
