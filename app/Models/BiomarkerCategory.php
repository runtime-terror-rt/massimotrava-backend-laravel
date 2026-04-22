<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiomarkerCategory extends Model
{
    protected $fillable = ['title','description', 'status'];

    public function subcategories()
    {
        return $this->hasMany(BiomarkerSubcategory::class, 'biomarker_category_id');
    }
}
