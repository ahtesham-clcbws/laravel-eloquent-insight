<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_ClassSubject extends Model
{
    use HasFactory;

    public function class()
    {
        return $this->belongsTo(ClassGoupExamModel::class, 'classes_group_exams_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
