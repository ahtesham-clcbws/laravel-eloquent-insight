<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_DisplaySubjectPart extends Model
{
    use HasFactory;

    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }

    public function subject_part_lessons()
    {
        return $this->hasMany(SubjectPartLesson::class, 'subject_part_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(ClassGoupExamModel::class, 'classes_group_exams_id');
    }
}
