<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_DisplayClassSubject extends Model
{
    use HasFactory;

    public function class()
    {
        return $this->belongsTo(ClassGoupExamModel::class, 'classes_group_exams_id');
    }
}
