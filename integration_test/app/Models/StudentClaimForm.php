<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClaimForm extends Model
{
    use HasFactory;

    protected $table = 'student_claim_forms';

    protected $fillable = [
        'student_id',
        'status',
        'institude_name1',
        'institude_director1',
        'institude_mobile1',
        'whatsapp_no1',
        'institude_email1',
        'state1',
        'city_id1',
        'institude_address1',
        'desired_course_detail1',
        'course_fee1',
        'course_duration1',
        'institude_prospectus1',
        'institude_name2',
        'institude_director2',
        'institude_mobile2',
        'whatsapp_no2',
        'institude_email2',
        'state2',
        'city_id2',
        'institude_address2',
        'desired_course_detail2',
        'course_fee2',
        'course_duration2',
        'institude_prospectus2',
        'institude_name3',
        'institude_director3',
        'institude_mobile3',
        'whatsapp_no3',
        'institude_email3',
        'state3',
        'city_id3',
        'institude_address3',
        'desired_course_detail3',
        'course_fee3',
        'course_duration3',
        'institude_prospectus3',
        'institude_name4',
        'institude_director4',
        'institude_mobile4',
        'whatsapp_no4',
        'institude_email4',
        'state4',
        'city_id4',
        'institude_address4',
        'desired_course_detail4',
        'course_fee4',
        'course_duration4',
        'institude_prospectus4',
    ];

    public function student(){
        return $this->belongsTo(Student::class,'student_id','id');
    }
}
