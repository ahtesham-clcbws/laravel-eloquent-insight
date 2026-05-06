<?php

use App\Mail\ApplicationFormSubmittedSuccessfully;
use App\Mail\ContactEmailReply;
use App\Mail\InstituteCollaborationApproved;
use App\Mail\InstituteDiscountVoucherRequestCompleted;
use App\Mail\InstituteRequestForCollaboration;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\ReplyMail;
use App\Models\Student;

// student pay now complete
function applicationFormSubmittedSuccessfully(Student $student)
{
    if ($student->latestStudentCode === null) {
        return null;
    }
    $data = [
        'name' => $student->name,
        'city' => $student->district?->name ? $student->district->name : null,
        'application_no' => $student->latestStudentCode->application_code,
        'mobile' => $student->mobile,
        'email' => $student->email,
    ];
    return new ApplicationFormSubmittedSuccessfully($data);
}

// institute enquiry
function instituteRequestForCollaboration(Corporate $institute)
{
    $data = [
        'name' => $institute->name,
        'institute_name' => $institute->institute_name,
        'email' => $institute->email,
        'city' => $institute->district?->name ? $institute->district->name : null,
    ];
    return new InstituteRequestForCollaboration($data);
}

// after institute approval
function instituteCollaborationApproved(Corporate $institute)
{
    $data = [
        'name' => $institute->name,
        'institute_name' => $institute->institute_name,
        'email' => $institute->email,
        'city' => $institute->district?->name ? $institute->district->name : null,
        'mobile' => $institute->phone,
        'code' => $institute->institude_code,
    ];
    return new InstituteCollaborationApproved($data);
}

// after alloting institute a voucher
function instituteDiscountVoucherRequestCompleted(Corporate $institute)
{
    $data = [
        'name' => $institute->name,
        'institute_name' => $institute->institute_name,
        'email' => $institute->email,
        'city' => $institute->district?->name ? $institute->district->name : null
    ];
    return new InstituteDiscountVoucherRequestCompleted($data);
}

// when replying an email address
function contactEmailReply(ContactInfo $contactInfo)
{
    $data = [
        'name' => $contactInfo->fullname,
        'email' => $contactInfo->email,
        'city' => $contactInfo->city
    ];
    return new ContactEmailReply($data);
}
