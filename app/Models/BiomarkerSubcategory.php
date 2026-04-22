<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiomarkerSubcategory extends Model
{
    protected $fillable = [
        'biomarker_category_id', 'title', 'description', 'status', 'max_range', 'min_range', 'unit'
    ];

    public function category()
    {
        return $this->belongsTo(BiomarkerCategory::class, 'biomarker_category_id');
    }

    public function reports()
    {
        return $this->hasMany(BiomarkerReport::class);
    }   
}
