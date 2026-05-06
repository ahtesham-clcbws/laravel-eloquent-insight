<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestQuestions extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'test_questions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'test_id',
        'section_id',
        'creator_id',
        'question_id',
        'allotter_id',
    ];
    public function fromTest()
    {
        return $this->belongsTo(TestModal::class, 'id', 'test_id');
    }

    public function forSection()
    {
        return $this->belongsTo(TestSections::class, 'id', 'section_id');
    }
    public function question()
    {
        return $this->hasOne(QuestionBankModel::class, 'id', 'question_id');
    }
    public function alloted_by()
    {
        return $this->belongsTo(User::class, 'id', 'allotter_id');
    }
    public function question_creator()
    {
        return $this->belongsTo(User::class, 'id', 'creator_id');
    }

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
}
