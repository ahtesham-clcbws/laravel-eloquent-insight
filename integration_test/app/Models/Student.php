<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use EloquentInsight\Traits\HasInsight;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasInsight;

    protected $guard = 'student';

    protected $fillable = [
        'serial_number',
        'name',
        'mobile',
        'new_mobile',
        'otp',
        'is_otp_verified',
        'email',
        'new_email',
        'email_code',
        'password',
        'login_password',
        'disability',
        'gender',
        'image',
        'userStatus',
        'father_name',
        'dob',
        'category_id',
        'subcategory_id',
        'subject',
        'address',
        'state_id',
        'district_id',
        'pincode',
        'qualification',
        'scholarship_category',
        'scholarship_opted_for',
        'test_center_a',
        'test_center_b',
        'form_step',
        'landmark',
        'is_gov_exam_participated',
        'is_apply_career_without_barrier',
        'govt_exams_1',
        'govt_exams_2',
        'govt_exams_3',
        'year',
        'roll_no',
        'student_roll_number',
        'family_income',
        'photograph',
        'signature',
        'terms_conditions',
        'terms_conditions_scholarship',
        'exam_one_result',
        'exam_one_year',
        'exam_two_result',
        'exam_two_year',
        'mother_occupation',
        'father_occupation',
        'career_two_result',
        'career_two_year',
        'career_exams_2',
        'career_one_result',
        'career_exams_1',
        'is_final_submitted',
        'scholarship_claim_generation_id',
        'name_checked',
        'father_name_checked',
        'dob_checked',
        'mobile_checked',
        'email_checked',
        'qualification_checked',
        'scholarship_category_checked',
        'scholarship_opted_for_checked',
        'choiceCenterA_checked',
        'choiceCenterB_checked',
        'isNew'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function studentCode()
    {
        return $this->hasMany(StudentCode::class, 'stud_id');
    }


    public function latestStudentCode()
    {
        return $this->hasOne(StudentCode::class, 'stud_id')
            ->latestOfMany('created_at');
    }

    public function getPercentageAttribute()
    {
        return $this->latestStudentCode?->percentage;
    }

    public function studentClaimForm()
    {
        return $this->hasOne(StudentClaimForm::class, 'student_id');
    }

    public function studentPayment()
    {
        return $this->hasMany(StudentPayment::class, 'student_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id')->select('id', 'name');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id')->select('id', 'state_id', 'name');
    }
    public function choiceCenterA()
    {
        return $this->belongsTo(District::class, 'test_center_a', 'id')->select('id', 'name');
    }
    public function choiceCenterB()
    {
        return $this->belongsTo(District::class, 'test_center_b', 'id')->select('id', 'name');
    }

    public function qualifications()
    {
        return $this->belongsTo(BoardAgencyStateModel::class, 'qualification', 'id');
    }

    public function scholarShipCategory()
    {
        return $this->belongsTo(EducationType::class, 'scholarship_category', 'id');
    }

    public function scholarShipOptedFor()
    {
        return $this->belongsTo(Gn_OtherExamClassDetailModel::class, 'scholarship_opted_for', 'id');
    }

    public function testimonial()
    {
        return $this->hasOne(TestimonialsModel::class, 'type_id')
            ->where('type', 'student');
    }

    public function studentPaperDetails()
    {
        return $this->hasMany(StudentPaperExported::class, 'student_id');
    }


    // ahtesham
    public function scholarship_granted()
    {
        return $this->belongsTo(ScholarshipClaimGeneration::class);
    }
    public function studentPaperExported()
    {
        return $this->hasMany(StudentPaperExported::class, 'student_id');
    }
}
