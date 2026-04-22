<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    protected $fillable = [
        'user_id',
        'activation_code',
        'inv_code',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
