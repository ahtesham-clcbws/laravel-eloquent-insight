<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    // Apply Global Scope for 'isActive'
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', 1);
        });
    }

    public function DistrictScholarshipLimits()
    {
        return $this->hasMany(DistrictScholarshipLimit::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'Id');
    }

    public function students()
    {
        return $this->hasMany(Student::class)->select('district_id', 'id', 'name');
    }

    public function getEducationRemainingForms($education_type_id = null)
    {
        $totalForms = 0;
        if ($education_type_id && count($this->DistrictScholarshipLimits) > 0) {
            $relation = DistrictScholarshipLimit::where('education_type_id', $education_type_id)->where('district_id', $this->id);
            $totalForms = $relation && $relation->max_registration_limit ? $relation->max_registration_limit : 0;
        }
        $totalForms = $this->total_forms;
        $filledForms = $this->students()->count(); // Count students who have filled the form

        return [
            'totalForms' => $totalForms,
            'remaining' => $totalForms - 0
        ];
    }
    public function getRemainingForms()
    {
        $totalForms = $this->total_forms;
        $filledForms = $this->students()->count(); // Count students who have filled the form

        return $totalForms - $filledForms; // Remaining forms
    }

    public function hasFormLimit()
    {
        return $this->DistrictScholarshipLimits;
    }

    public function getLimit($educationTypeId)
    {
        $students = 0;
        $limit = $this->districtScholarshipLimits()->forEducationType($educationTypeId)->first();

        if($limit) {
            $students = Student::where('district_id', $this->id)->where('scholarship_category', $educationTypeId)->count();
        }

        return (object)[
            'limit' => $limit ? $limit->max_registration_limit : 0,
            'remaining' => $limit ? $limit->max_registration_limit - $students : 0
        ];
    }
}
