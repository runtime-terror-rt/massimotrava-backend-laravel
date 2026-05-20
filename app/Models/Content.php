<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'body',
        'featured_image',
        'video_url',
        'duration',
        'status',
        'views_count',
        'is_featured',
        'published_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePosts($query)
    {
        return $query->where('type', 'post');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }
}
