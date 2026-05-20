<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleRetest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kit_id',
        'original_inv_code',
        'retest_date',
        'retest_time',
        'status',
        'admin_notes',
        'user_notes'
    ];

    protected $casts = [
        'retest_date' => 'date',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }
    
    public function originalReports()
    {
        return $this->hasMany(BiomarkerReport::class, 'inv_code', 'original_inv_code');
    }
}
