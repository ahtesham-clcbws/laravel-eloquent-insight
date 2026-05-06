<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictScholarshipLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'education_type_id',
        'max_registration_limit',
        'start_from'
    ];

    public function students()
    {
        return $this
            ->hasMany(Student::class, 'district_id', 'district_id')
            ->whereHas('studentCode', function ($query) {
                $query->where('is_paid', 1);
            });
    }
    public function roll_numbers()
    {
        return $this
            ->hasMany(Student::class, 'district_id', 'district_id')
            ->whereHas('studentCode', function ($query) {
                $query->where('is_paid', 1)->whereNotNull('roll_no');
            });
    }

    public function District()
    {
        return $this->belongsTo(District::class)->withoutGlobalScope('active');
    }

    public function EducationType()
    {
        return $this->belongsTo(EducationType::class, 'education_type_id');
    }

    public function scopeForEducationType($query, $educationTypeId)
    {
        return $query->where('education_type_id', $educationTypeId);
    }

    /**
     * Get the registration limit for a given district and education type.
     * Returns 0 if no limit is set.
     */
    public static function getLimit($districtId, $educationTypeId)
    {
        $limit = self::where('district_id', $districtId)
            ->where('education_type_id', $educationTypeId)
            ->first();

        return $limit ? $limit->max_registration_limit : 0;
    }
}
