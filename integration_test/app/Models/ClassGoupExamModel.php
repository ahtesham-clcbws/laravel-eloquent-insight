<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassGoupExamModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'classes_groups_exams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'education_type_id',
        'boards',
        'subjects'
    ];

    public function education()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function class_boards()
    {
        return $this->belongsToMany(BoardAgencyStateModel::class, 'assign_classes_boards', 'class_id', 'board_id');
    }
    
    public function class_subjects()
    {
        return $this->belongsToMany(Subject::class, 'assign_classes_subjects', 'class_id', 'subject_id');
    }

    public function class_boards1()
    {
        return $this->belongsToMany(BoardAgencyStateModel::class, 'assign_classes_boards', 'class_id', 'board_id');
    }
}
