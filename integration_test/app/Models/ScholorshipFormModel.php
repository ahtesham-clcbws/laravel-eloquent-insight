<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholorshipFormModel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function examSubject()
    {
        return $this->hasOne(ExamSubject::class, 'scholarship_exam_id', 'id');
    }

    public function examSubjects()
    {
        return $this->hasMany(ExamSubject::class, 'scholorship_exam_id', 'participate_exam');
    }

    protected $fillable = [
        'name',
        'fathersname',
        'dob',
        'gender',
        'image',
        'sign',
        'disability',
        'address',
        'city',
        'qualification',
        'participate_exam',
        'center1',
        'center2',
        'center3',
        'exam1',
        'exam2',
        'exam3',
        'year',
        'roll_no',
        'family_income',
        'occupation',
    ];
    protected $table='scholorship_form_models';


}
