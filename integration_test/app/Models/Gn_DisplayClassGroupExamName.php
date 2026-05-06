<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_DisplayClassGroupExamName extends Model
{
    use HasFactory;

    public function education()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function class_exam()
    {
        return $this->belongsToMany(ClassGoupExamModel::class, 'gn__assign_class_group_exam_names', 'education_type_id', 'classes_group_exams_id','education_type_id');
    }

}
