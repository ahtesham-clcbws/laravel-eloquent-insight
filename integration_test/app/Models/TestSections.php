<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSections extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'test_section';
    protected $primaryKey = 'id';

    protected $fillable = [
        'test_id',
        'section_index',
        'subject',
        'subject_part',
        'subject_part_lesson',
        'number_of_questions',
        'submitted_questions',
        'question_type',
        'mcq_options',
        'difficulty_level',
        'creator_id',
        'date_of_completion',
    ];
    public function Belongto()
    {
        return $this->belongsTo(TestModal::class, 'id', 'test_id');
    }

    public function sectionSubject() {
        return $this->hasOne(Subject::class, 'id', 'subject');
    }
    
    public function sectionSubjectPart() {
        return $this->hasOne(SubjectPart::class, 'id', 'subject_part');
    }
   
    public function sectionSubjectLesson() {
        return $this->hasOne(SubjectPartLesson::class, 'id', 'subject_part_lesson');
    }

    public function getQuestions(){
        return $this->belongsToMany(QuestionBankModel::class, 'test_questions','section_id','question_id');
    }

    public function subject() {
        return $this->hasMany(Subject::class, 'id', 'subject');
    }
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
}
