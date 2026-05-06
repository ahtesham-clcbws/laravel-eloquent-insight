<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_DisplayExamAgencyBoardUniversity extends Model
{
    use HasFactory;

    public function education()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function educations()
    {
        return $this->hasMany(EducationType::class, 'id', 'education_type_id');
    }


    public function classesGroupExam()
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'classes_group_exams_id');
    }

    // public function class_board()
    // {
    //     return $this->belongsToMany(BoardAgencyStateModel::class, 'gn__education_class_exam_agency_board_universities', 'education_type_id','classes_group_exams_id','education_type_id')->withPivot([
    //         'created_at',
    //         'updated_at',
    //     ]);
    // } 
}
