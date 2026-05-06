<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBankModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'question_bank';
    protected $primaryKey = 'id';

    protected $fillable = [
        'education_type_id',
        'class_group_exam_id',
        'board_agency_state_id',
        'subject',
        'subject_part',
        'subject_lesson_chapter',
        'question_type',
        'mcq_answer',
        'mcq_options',
        'question',
        'solution',
        'explanation',
        'ans_1',
        'ans_2',
        'ans_3',
        'ans_4',
        'ans_5',
        'alloted_for_check_id',
        'creator_id',
        'status',
        'checked_by_id',
        'checker_comments',
    ];
    public function educationType()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }
    public function classGroup()
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'class_group_exam_id');
    }
    public function boardAgency()
    {
        return $this->hasOne(BoardAgencyStateModel::class, 'id', 'board_agency_state_id');
    }
    public function inSubject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject');
    }
    public function inSubjectPart()
    {
        return $this->hasOne(SubjectPart::class, 'id', 'subject_part');
    }
    public function inSubjectLesson()
    {
        return $this->hasOne(SubjectPartLesson::class, 'id', 'subject_lesson_chapter');
    }
    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }
    public function allotedTo()
    {
        return $this->hasOne(User::class, 'id', 'alloted_for_check_id');
    }
    public function checkedBy()
    {
        return $this->hasOne(User::class, 'id', 'checked_by_id');
    }

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
}
