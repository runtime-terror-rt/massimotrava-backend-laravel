<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiomarkerReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function biomarkerSubcategory()
    {
        return $this->belongsTo(BiomarkerSubcategory::class, 'biomarker_subcategory_id');
    }

    public function biomarkerCategory()
    {
        return $this->belongsTo(BiomarkerCategory::class, 'biomarker_category_id');
    }

    public function kit()
    {
        return $this->belongsTo(Kit::class, 'kit_id');
    }
}