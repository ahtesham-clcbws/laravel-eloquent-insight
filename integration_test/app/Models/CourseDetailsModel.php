<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetailsModel extends Model
{
    use HasFactory;

    protected $table='courses_list';

    public function scholarshipCategory(){
        return $this->belongsTo(EducationType::class,'scholarship_category');
    }
}
