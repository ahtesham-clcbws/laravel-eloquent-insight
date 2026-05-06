<!-- Sidebar -->
<?php

use App\Events\paymentDoneEvent;
use App\Models\Student;
use App\Models\StudentPaperExported;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

$showAdmitCard = null;

$student = Student::find(Auth::guard('student')->id());
$student->student_paper_details;
$isResultAvailable = false;
$studCode = $student->latestStudentCode;

// $studentPaperDetails = StudentPaperExported::with('subjectPaperDetail')->where('app_code', $appCode?->application_code)->where('student_id', $student->id)->get();

$query = Student::query()
    ->select('students.*', 's.percentage')
    ->where('students.is_final_submitted', 1)
    ->where('students.id', Auth::guard('student')->id())
    ->leftJoin('student_codes as s', 'students.id', '=', 's.stud_id')
    ->leftJoin('student_paper_exporteds as sp', 'students.id', '=', 'sp.student_id')
    ->whereNotNull('s.percentage')
    ->first();

if ($studCode && $query) {
    $isResultAvailable = true;
}

if ($studCode) {
    $examAt = Carbon::parse($studCode->exam_at)->startOfDay(); // Parse exam date and set time to start of day

    $examAtDate = $examAt;
    $daysBeforeExam = $examAtDate->subDays($studCode->admitcard_before)->startOfDay(); // Calculate the admit card availability date
    $showAdmitCard = Carbon::now()->startOfDay()->gte($daysBeforeExam) ? true : false; // Compare current date (start of day) with admit card availability date

    if ($studCode->is_paid || $studCode->used_coupon) {
        $updatedAt = Carbon::parse($studCode->updated_at);
        $isRecentlyUpdated = $updatedAt->greaterThanOrEqualTo(now()->subMinutes(2));

        if ($isRecentlyUpdated && $studCode->payment_done_notification_sent == 0) {
            event(new paymentDoneEvent($studCode));

            $studCode->payment_done_notification_sent = 1;
            $studCode->save();
        }
    }
}

// return print_r([
//     // 'examAt' => $examAt,
//     // 'admitcard_before' => $studCode->admitcard_before,
//     // 'examAtDate' => $examAtDate,
//     // 'daysBeforeExam' => $daysBeforeExam,
//     'showAdmitCard' => json_encode($showAdmitCard),
//     // 'is_paid' => $studCode->is_paid,
//     // 'used_coupon' => $studCode->used_coupon,
//     // 'payment_done_notification_sent' => $studCode->payment_done_notification_sent,
//     // 'updatedAt' => $updatedAt,
//     // 'isRecentlyUpdated' => $isRecentlyUpdated,
//     'query' => $query,
//     'isResultAvailable' => json_encode($isResultAvailable)
// ]);
// return print_r($student->toArray());

?>

<nav class="sidebar navbar-inverse fixed-top elevation-4" id="sidebar-wrapper" role="navigation"
    style="overflow-y: hidden;  font-style: italic !important;">
    <div class="sidebar-header">
        <div class="sidebar-brand" style="height: auto !important;">
            <div>
                <a href="{{ 'studentDashboard' }}">
                    <img src="/website/assets/images/brand/logo.png" style="width: 100%;" />
                </a>
            </div>
        </div>
        <div class="logo_area mb-2">
            <a class="brand-link studentImage" href="{{ 'studentDashboard' }}">
                @if ($student->photograph)
                    <img class="brand-image img-circle elevation-3"
                        src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/' . $student->photograph : '/upload/student/' . $student->photograph }}"
                        alt="Prifle Dp" style="opacity: .8">
                @else
                    <img class="brand-image img-circle elevation-3" src="{{ asset('student/images/th_5.png') }}"
                        alt="" style="opacity: .8">
                @endif
            </a>
            <div class="brand_link_name mb-2">
                <a class="brand-text font-weight-light director_name"
                    href="{{ 'studentDashboard' }}">{{ $student->name }}</a>
                <br>
                <?php
                $appCode = $student->latestStudentCode;
                
                $studentPaperDetails = StudentPaperExported::where('app_code', $appCode?->application_code)->where('student_id', $student->id)->first();
                
                ?>
                @if ($appCode)
                    <p style="color:#18c968;font-size: 15px;">Application No: {{ $appCode->application_code }}</p>
                @endif
            </div>
        </div>
    </div>
    <ul class="nav sidebar-nav">
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary text-dark bg-white" href="/">
                    <span class="nav_icon" style="color: #18c968;">
                        <i class="fa fa-home" style="font-size: 18px;"></i></span>
                    <p style="color:#18c968">Homepage</p>
                </a>
            </div>
            <div class="dropdown show">
                <a class="btn btn-secondary text-dark bg-white" href="{{ route('studentDashboard') }}">
                    <img class="nav_icon" src="{{ asset('student/images/watch.png') }}" alt="">
                    <p>Dashboard</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
            @if (!$student->is_final_submitted)
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('studentform') }}">
                        <img class="nav_icon" src="{{ asset('student/images/8.png') }}" alt="">
                        <p style="color:#18c968">Apply Now</p>
                    </a>
                </div>
            @endif
            @if ($student->is_final_submitted)
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('students.formReview') }}">
                        <img class="nav_icon" src="{{ asset('student/images/8.png') }}" alt="">
                        <p style="color:#18c968">Application Form</p>
                    </a>
                </div>
            @endif
            @if (
                ($studCode?->is_paid || $studCode?->used_coupon) &&
                    $studCode?->issued_admitcard &&
                    ($isResultAvailable || $showAdmitCard))
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('students.admitCardSearch') }}">
                        <img class="nav_icon" src="{{ asset('student/images/12.png') }}" alt="">
                        <p style="color:#18c968">Admit Card</p>
                    </a>
                </div>
            @endif
            @if (($studCode?->is_paid || $studCode?->used_coupon) && $studCode?->issued_admitcard && $isResultAvailable)
                @if ($student->studentPaperDetails?->isNotEmpty())
                    <div class="dropdown show">
                        <a class="btn btn-secondary" href="{{ route('students.resultdownload') }}">
                            <img class="nav_icon" src="{{ asset('student/images/12.png') }}" alt="">
                            <p style="color:#18c968">Result Download</p>
                        </a>
                    </div>
                @endif
            @endif
            @if ($student->scholarship_claim_generation_id)
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('students.claimScholarshipForm') }}">
                        <img class="nav_icon" src="{{ asset('student/images/12.png') }}" alt="">
                        <p style="color:#18c968">Student Claim Form</p>
                    </a>
                </div>
            @endif
            @if (
                $student->name_checked &&
                    $student->father_name_checked &&
                    $student->dob_checked &&
                    $student->mobile_checked &&
                    $student->email_checked &&
                    $student->qualification_checked &&
                    $student->scholarship_category_checked &&
                    $student->scholarship_opted_for_checked &&
                    $student->choiceCenterA_checked &&
                    $student->choiceCenterB_checked &&
                    ($studCode?->is_paid || $studCode?->used_coupon))
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('student.payment') }}">
                        <img class="nav_icon" src="{{ asset('student/images/12.png') }}" alt="">
                        <p style="color:#18c968">Payment Details</p>
                    </a>
                </div>
            @endif
            @if ($studCode?->is_paid || $studCode?->used_coupon)
                <div class="dropdown show">
                    <a class="btn btn-secondary" href="{{ route('student.sayAboutUs') }}">
                        <img class="nav_icon" src="{{ asset('student/images/12.png') }}" alt="">
                        <p style="color:#18c968">Say About Us</p>
                    </a>
            @endif
        </li>

    </ul>
</nav>
<!-- /#sidebar-wrapper -->
