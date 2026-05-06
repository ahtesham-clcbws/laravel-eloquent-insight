<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_DisplayOtherExamClassDetail extends Model
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

    public function boardAgencyState()
    {
        return $this->hasOne(BoardAgencyStateModel::class, 'id', 'agency_board_university_id');
    }
}
