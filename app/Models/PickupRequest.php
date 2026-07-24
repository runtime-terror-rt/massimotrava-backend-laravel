<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupRequest extends Model {
    protected $fillable = [
        'user_id',
        'kit_id',
        'kit_name',
        'kit_icon',
        'pickup_date',
        'time_slot',
        'address',
        'contact_phone',
        'notes',
        'admin_notes',
        'status',
        'collected_at',
        'cancelled_at',
    ];

    protected $casts = [
        'pickup_date'  => 'date',
        'collected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo { 
        return $this->belongsTo(User::class); 
    }

    public function scopePending($q) { 
        return $q->where('status','pending'); 
    }

    public function scopeScheduled($q) { 
        return $q->where('status','scheduled'); 
    }

    public function scopeCollected($q) { 
        return $q->where('status','collected'); 
    }
}