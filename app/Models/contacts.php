<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contacts extends Model
{
    protected $fillable = ['name', 'email', 'message', 'status'];
}
