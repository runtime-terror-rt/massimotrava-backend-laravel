<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiomarkerReport extends Model
{
    protected $fillable = [
        'user_id',
        'kit_id',
        'biomarker_category_id',
        'biomarker_subcategory_id',
        'value',
        'unit',
        'inv_code',
        'status'
    ];

    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }

    public function biomarkerCategory()
    {
        return $this->belongsTo(BiomarkerCategory::class, 'biomarker_category_id');
    }

    public function biomarkerSubcategory()
    {
        return $this->belongsTo(BiomarkerSubcategory::class, 'biomarker_subcategory_id');
    }
}
