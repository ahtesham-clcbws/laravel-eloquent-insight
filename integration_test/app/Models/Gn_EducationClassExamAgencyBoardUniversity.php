<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_EducationClassExamAgencyBoardUniversity extends Model
{
    use HasFactory;

    public function education()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function classesGroupExam()
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'classes_group_exams_id');
    }

    public function agencyBoardUniversity()
    {
        return $this->hasOne(BoardAgencyStateModel::class, 'id', 'board_agency_exam_id');
    }

}
