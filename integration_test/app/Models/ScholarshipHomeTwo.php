<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipHomeTwo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scholarshipType(){
        return $this->belongsTo(ScholarshipHome::class,'scholarship_course_id','id');
    }
}
