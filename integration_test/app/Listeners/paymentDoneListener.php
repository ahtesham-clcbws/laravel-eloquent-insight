<?php

namespace App\Listeners;

use App\Mail\ApplicationFormSubmittedSuccessfully;
use App\Models\User;
use App\Notifications\Admin\StudentPaymentAdminMail;
use App\Notifications\Student\StudentPaymentMail;
use Illuminate\Support\Facades\Mail;

class paymentDoneListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $studentCode = $event->studCode;

        $student = $studentCode->student;

        $admins = User::where('roles', 'admin')->whereNotNull('email')->get();

        // $student->notify(new StudentPaymentMail($studentCode));

        $data = [
            'name' => $student->name,
            'city' => $student->district?->name ? $student->district->name : null,
            'application_no' => $student->latestStudentCode->application_code,
            'mobile' => $student->mobile,
            'email' => $student->email,
        ];
        Mail::to($student)->send(new ApplicationFormSubmittedSuccessfully($data));

        foreach ($admins as $admin) {
            $admin->notify(new StudentPaymentAdminMail($studentCode));
        }
    }
}
