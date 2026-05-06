<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Gn_OtherExamClassDetailModel::class, 'course_id');
    }

    public function institute()
    {
        return $this->belongsTo(Corporate::class);
    }
}
