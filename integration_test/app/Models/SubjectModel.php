<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table='subject';

    public function subcategories()
    {
        return $this->belongsToMany(SubCategoryModel::class);
    }
}
