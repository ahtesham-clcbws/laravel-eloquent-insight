<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardAgencyStateModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'board_agency_exam';
    // protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    public function education(): HasOne
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function classesGroupExam(): HasOne
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'classes_group_exams_id');
    }
}
