<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;
    protected $table='examsubjects';
    protected $guarded = [];

    // public function scholarshipForm()
    // {
    //     return $this->belongsTo(ScholorshipFormModel::class, 'scholarship_exam_id', 'participate_exam');
    // }

    protected $fillable = ['name', 'total_ques', 'marks', 'scholorship_exam_id'];

    public function scholarshipExam()
    {
        return $this->belongsTo(ScholorshipExamModel::class, 'scholorship_exam_id');
    }
}
