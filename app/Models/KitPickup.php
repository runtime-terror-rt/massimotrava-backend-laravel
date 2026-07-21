<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitPickup extends Model
{
    protected $fillable = [
        'kit_id',
        'preferred_date',
        'preferred_time_slot',
        'pickup_address',
        'contact_phone',
        'assigned_courier_name',
        'assigned_courier_phone',
        'assigned_by_admin_id',
        'status',
        'admin_notes',
        'failure_reason',
        'collected_at',
        'delivered_to_lab_at',
    ];

    protected $casts = [
        'preferred_date'      => 'date',
        'collected_at'        => 'datetime',
        'delivered_to_lab_at' => 'datetime',
    ];

    /**
     * Valid status transitions for pickup
     */
    protected static $validStatusTransitions = [
        'requested'        => ['assigned', 'cancelled'],
        'assigned'         => ['collected', 'failed'],
        'collected'        => ['delivered_to_lab'],
        'delivered_to_lab' => [], // terminal
        'failed'           => [], // terminal - kit goes back to activated
        'cancelled'        => [], // terminal
    ];

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(string $newStatus): bool
    {
        if ($this->status === $newStatus) {
            return true;
        }

        $allowed = self::$validStatusTransitions[$this->status] ?? [];
        return in_array($newStatus, $allowed);
    }

    /**
     * Update status with validation
     */
    public function updateStatus(string $newStatus, array $extraData = []): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition pickup status from '{$this->status}' to '{$newStatus}'"
            );
        }

        return $this->update(array_merge(['status' => $newStatus], $extraData));
    }

    // Relationships
    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }

    public function assignedByAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_by_admin_id');
    }
}