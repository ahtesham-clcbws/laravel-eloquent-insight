<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipHome extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function educationType(){
        return $this->belongsTo(EducationType::class,'education_type_id','id');
    }

    public function overview(){
        return $this->hasOne(ScholarshipHomeTwo::class,'scholarship_course_id','id')->orderBy('created_at','asc');
    }
}
