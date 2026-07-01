<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['rating', 'text', 'author_name', 'author_image', 'is_verified', 'status'];
}
