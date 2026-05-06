<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GnResultSubjectMapping extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function education()
    {
        return $this->hasOne(EducationType::class, 'id', 'education_type_id');
    }

    public function classesGroupExam()
    {
        return $this->hasOne(ClassGoupExamModel::class, 'id', 'classes_group_exams_id');
    }

    public function boardAgencyState()
    {
        return $this->hasOne(BoardAgencyStateModel::class, 'id', 'agency_board_university_id');
    }

    public function subjects($name = null)
    {
        $subjectIds = json_decode($this->subject_id, true);
        $subject = collect();

        if (is_array($subjectIds)) {
            $subject = Subject::whereIn('id', $subjectIds)->get();
        }

        if ($name) {
            return implode(',', $subject->pluck('name', 'max_marks')->all());
        }
        return   $subject;
    }

    public function scholarshipOptedFor($name = null)
    {
        $nameId = json_decode($this->name, true);
        $scholar = collect();

        if (is_array($nameId))
            $scholar = Gn_OtherExamClassDetailModel::whereIn('id', $nameId)->get();

        if ($name) {
            return implode(',', $scholar->pluck('name')->all());
        }
        return   $name;
    }

    public function subjectPaperDetails()
    {
        return $this->hasMany(SubjectPaperDetail::class, 'subject_mapping_id', 'id')->orderBy('created_at', 'asc');
    }
}
