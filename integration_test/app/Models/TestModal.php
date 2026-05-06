<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'test';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'title',
        'test_type',
        'test_fee',
        'test_attempts',
        'test_schedule_time_start',
        'test_schedule_time_stop',
        'show_result',
        'show_rank',
        'show_answer',
        'show_solution',
        'marks',
        'negative_marks',
        'time_to_complete',
        'extra_requirements',
        'education_type_id',
        'education_type_child_id',
        'board_state_agency',
        'other_category_class_id',
        'question_type',
        'question_mcq_options_default',
        'total_questions',
        'sections',
        'reviewer_id',
        'manager_id',
        'publisher_id',
        'questions_submitted',
        'questions_approved',
        'reviewed',
        'reviewed_status',
        'reviewed_message',
        'published',
        'published_status',
        'published_message',
    ];
    
    public function creater()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    // public function institute()
    // {
    //     return $this->hasMany(FranchiseDetails::class, 'user_id', 'id');
    // }

    public function Manager()
    {
        return $this->belongsTo(User::class, 'id', 'manager_id');
    }
    
    public function Reviewer()
    {
        return $this->belongsTo(User::class, 'id', 'reviewer_id');
    }
    
    public function Publisher()
    {
        return $this->belongsTo(User::class, 'id', 'publisher_id');
    }
    
    public function EducationType()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }
    
    public function EducationClass()
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'education_type_child_id');
    }
    
    public function EducationBoard()
    {
        return $this->hasOne(BoardAgencyStateModel::class, 'id', 'board_state_agency');
    }
    
    public function OtherCategoryClass()
    {
        return $this->hasOne(OtherCategoryClass::class, 'id', 'other_category_class_id');
    }

    public function gn_OtherCategoryClass()
    {
        return $this->hasOne(Gn_OtherExamClassDetailModel::class, 'id', 'other_category_class_id');
    }

    public function getQuestions()
    {
        return $this->belongsToMany(QuestionBankModel::class, 'test_questions','test_id','question_id')->withPivot('section_id', 'creator_id');
    }

    public function getSection()
    {
        return $this->hasMany(TestSections::class,'test_id');
    }

    public function institude()
    {
        return $this->belongsTo(FranchiseDetails::class, 'user_id', 'user_id');
    }

    public function getTestCat(){
        return $this->hasOne(TestCat::class, 'id', 'test_cat');
    }
    // public function Reviewer()
    // {
    //     return $this->hasOne(User::class);
    // }
    // public function Manager()
    // {
    //     return $this->hasOne(User::class);
    // }
    // public function Creator()
    // {
    //     return $this->hasOne(User::class);
    // }
    // public function Publisher()
    // {
    //     return $this->hasOne(User::class);
    // }

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public static function sectionsCount(){
        // $allTests = TestModal::get();
        // foreach ($allTests as $key => $test) {
        //     $totalSections = TestSections::select(['number_of_questions'])->where('test_id', $test['id'])->get();
        //     $totalQuestions = 0;
        //     foreach ($totalSections as $key => $section) {
        //         $totalQuestions += intval($section['number_of_questions']);
        //     }
        //     $questionSubmitted = TestQuestions::where('test_id', $test['id'])->count();
        //     $questionApproved = TestQuestions::where('test_id', $test['id'])->where('approved', 1)->count();
        //     $test->sections = count($totalSections);
        //     $test->total_questions = $totalQuestions;
        //     $test->questions_submitted = $questionSubmitted;
        //     $test->questions_approved = $questionApproved;
        //     $test->save();
        // }
    }
}
