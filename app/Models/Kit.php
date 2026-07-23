<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Kit extends Model
{
    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'activation_code',
        'inv_code',
        'status',
        'added_by_admin_id',
        'courier_name',
        'tracking_number',
        'admin_notes',
        'shipped_at',
        'delivered_at',
        'activated_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    /**
     * Valid status transitions map
     */
    protected static $validStatusTransitions = [
        'requested'          => ['processing', 'cancelled'],
        'processing'         => ['shipped', 'cancelled'],
        'shipped'            => ['delivered', 'cancelled'],
        'delivered'          => ['activated'],
        'activated'          => ['pickup_scheduled'],
        'pickup_scheduled'   => ['pickup_assigned', 'cancelled'],
        'pickup_assigned'    => ['sample_collected', 'failed'],
        'sample_collected'   => ['received_at_lab'],
        'received_at_lab'    => ['processing_at_lab'],
        'processing_at_lab'  => ['results_ready'],
        'results_ready'      => ['completed'],
    ];

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $currentStatus = $this->status;
        
        // Same status - allow (idempotent)
        if ($currentStatus === $newStatus) {
            return true;
        }

        $allowed = self::$validStatusTransitions[$currentStatus] ?? [];
        
        return in_array($newStatus, $allowed);
    }

    /**
     * Update status with validation
     */
    public function updateStatus(string $newStatus, array $extraData = []): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            Log::warning("[KIT STATUS] Invalid transition: {$this->status} → {$newStatus} (Kit ID: {$this->id})");
            throw new \InvalidArgumentException(
                "Cannot transition kit status from '{$this->status}' to '{$newStatus}'"
            );
        }

        $data = array_merge(['status' => $newStatus], $extraData);
        
        // Auto-set timestamps based on status
        match ($newStatus) {
            'shipped' => $data['shipped_at'] = now(),
            'delivered' => $data['delivered_at'] = now(),
            'activated' => $data['activated_at'] = now(),
            default => null,
        };

        return $this->update($data);
    }

    // Relationships...
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }

    public function pickup()
    {
        return $this->hasOne(KitPickup::class);
    }

    public function biomarkerReports()
    {
        return $this->hasMany(BiomarkerReport::class);
    }

    public function pickupRequest()
    {
        return $this->hasOne(PickupRequest::class, 'kit_id');
    }
}