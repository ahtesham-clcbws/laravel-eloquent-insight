@extends('student.layouts.master')
@section('content')
    <?php
    
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    
    $totalMax = 0;
    $totalObtained = 0;
    $totalQuestionsCount = 0;
    $attempted_questions = 0;
    $right_answers = 0;
    $wrong_answers = 0;
    $totalPenalty = 0;
    $studentPaperDetail = null;
    $notAttemptedQuestions = 0;
    ?>
    <div class="pagecontentbody container pb-3 pt-5">
        <div class="main" id="prodiv">
            <link href="{{ asset('student/admin_card.css') }}" rel="stylesheet">
            <center>

                <table style="border-collapse:collapse;margin-left:6.3494pt" cellspacing="0">
                    <tr>
                        <td style="text-align: center;" colspan="5">
                            <div class="logo"> <a href="#"><img
                                        src="{{ asset('website/assets/images/brand/logo.png') }}" alt="logo"
                                        width="180"></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <div class="logo" style="margin-bottom: 40px;"> <a href="#"><img
                                        src="{{ asset('website/assets/images/bottom-menu/sqs-logo.png') }}" alt="img"
                                        width="120">
                                </a>
                            </div>
                        </td>
                        <td colspan="3">
                            <div style="margin-top: 15px;margin-bottom: 15px;text-align:center">
                                <h3 style="color:#1015cd;font-weight: 900;font-size: large;">CAREER PREP SCHOLARSHIP EXAM
                                </h3>
                                <h5 style="color:#00a64b">Scholarship Test Result - {{ date('Y') }} (Phase-I)</h5>
                            </div>
                        </td>
                        <td>
                            <div class="logo" style="margin-bottom: 40px;"> <a href="#"><img
                                        src="{{ asset('student/test_notes_logo.png') }}" alt="logo" width="120"></a>
                            </div>
                        </td>
                    </tr>
                    <tr style="height:17pt">
                        <td style="width:530pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-left-style:solid;border-left-width:1pt;border-left-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#231F20"
                            colspan="4" bgcolor="#FFF200">
                            <p class="s9"
                                style="padding-top: 3pt;padding-left: 181pt;text-indent: 0pt;text-align: left;">
                                Candidate &amp; Exam Detail</p>
                        </td>
                        <td class="tb1" style="width:105pt" bgcolor="#FFF200">
                            <p class="s9"
                                style="padding-top: 3pt;padding-left: 17pt;text-indent: 0pt;text-align: left;">
                                Candidate Photo</p>
                        </td>
                    </tr>
                    <tr style="height:27pt">
                        <td class="tb1" style="width:93pt;">
                            <p class="s10" style="padding: 12pt;text-indent: 0pt;text-align: left;">Candidate’s Name</p>
                        </td>
                        <td class="tb1" style="width:129pt">
                            <p class="s10" style="padding: 10pt;text-indent: 0pt;text-align: left;">
                                {{ ucfirst($student->name) }}</p>
                        </td>
                        <td class="tb1" style="width:75pt;">
                            <p class="s10" style="padding: 12pt;text-indent: 0pt;text-align: left;">Roll No</p>
                        </td>
                        <td class="tb1" style="width:133pt;">
                            <p class="s11" style="padding: 11pt;text-indent: 0pt;text-align: left;">
                                {{ $appCode->roll_no }}</p>
                        </td>
                        <td class="tb1" style="width:105pt;" rowspan="5">
                            <p style="text-indent: 0pt;">
                                <span>
                                    <center>
                                        @if($student->photograph)
                                        <img width="125" height="139" src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/'.$student->photograph : '/upload/student/'.$student->photograph  }}" alt="Prifle Dp" class="brand-image img-circle elevation-3" style="opacity: .8">
                                        @else
                                        <img src="{{asset('student/images/th_5.png')}}" width="125" height="139" />
                                        @endif
                                    </center>
                                </span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height:25pt">
                        <td class="tb1" style="width:93pt;">
                            <p class="s10" style="padding: 10pt;text-indent: 0pt;text-align: left;">
                                Father’s Name</p>
                        </td>
                        <td class="tb1" style="width:129pt;">
                            <p class="s10" style="padding: 10pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                {{ ucfirst($student->father_name) }}</p>
                        </td>
                        <td class="tb1" style="width:75pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                Application No</p>
                        </td>
                        <td class="tb1" style="width:133pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                {{ $appCode?->application_code ?? 'NA' }}
                            </p>
                        </td>
                    </tr>
                    <tr style="height:24pt">
                        <td class="tb1" style="width:93pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                                Date of
                                Birth</p>
                        </td>
                        <td class="tb1" style="width:129pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">
                                {{ Carbon::parse($student->dob)->format('d/m/Y') }} </p>
                        </td>
                        <td class="tb1" style="width:75pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                Exam Category</p>
                        </td>
                        <td class="tb1" style="width:133pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">
                                {{ $student->scholarShipCategory?->name ?? 'N/A' }}
                            </p>
                        </td>
                    </tr>
                    <tr style="height:25pt">
                        <td class="tb1" style="width:93pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                                Gender</p>
                        </td>
                        <td class="tb1" style="width:129pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                {{ $student->gender }}
                            </p>
                        </td>
                        <td class="tb1" style="width:75pt;">
                            <p class="s10" style="padding: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                                Preparation For</p>
                        </td>
                        <td class="tb1" style="width:133pt">
                            <p class="s10" style="padding: 9pt;padding-left: 13pt;text-indent: 0pt;text-align: left;">
                                {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}
                            </p>
                        </td>
                    </tr>
                    <tr style="height:25pt">
                        <td class="tb1" style="width:93pt;">
                            <p class="s10" style="padding: 8pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                                Person with Disability</p>
                        </td>
                        <td class="tb1" style="width:129pt;" colspan="3">
                            <p class="s10" style="padding: 8pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                                {{ $student->disability ?? 'NA' }}
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="padding-top: 3pt;text-indent: 0pt;text-align: left;"><br /></p>
                <h2 style="padding-bottom: 2pt;padding-left: 35pt;text-indent: 0pt;text-align: center;">Scholarship Test
                    Result
                </h2>
                <table style="border-collapse:collapse;margin-left:6.3494pt" cellspacing="0">
                    <tr style="height:26pt">
                        <td class="tb1" style="width:188pt;" colspan="2" bgcolor="#FFF200">
                            <p class="s12"
                                style="padding-top: 6pt;padding-left: 20pt;text-indent: 0pt;text-align: left;">#
                                Subject</p>
                        </td>
                        <td class="tb1" style="width:184pt;" bgcolor="#FFF200">
                            <p class="s12"
                                style="padding-top: 6pt;padding-left: 36pt;text-indent: 0pt;text-align: left;">
                                Marks
                                ( Maximum )</p>
                        </td>
                        <td class="tb1" style="width:163pt;" bgcolor="#FFF200">
                            <p class="s12"
                                style="padding-top: 6pt;padding-left: 27pt;text-indent: 0pt;text-align: left;">
                                Marks
                                (Obtained)</p>
                        </td>
                    </tr>

                    @foreach ($studentPaperDetails as $key => $studentPaperDetail)
                        <tr style="height:26pt">
                            <td class="tb1" style="width:40pt;">
                                <p class="s13"
                                    style="padding-top: 7pt;padding-left: 20pt;text-indent: 0pt;text-align: left;">
                                    {{ $key + 1 }}
                                </p>
                            </td>
                            <td class="tb1" style="width:148pt;">
                                <p class="s13"
                                    style="padding-top: 8pt;padding-left: 21pt;text-indent: 0pt;text-align: left;">
                                    {{ $studentPaperDetail?->subject_name }}
                                </p>
                            </td>
                            <td class="tb1" style="width:184pt;">
                                <p class="s13"
                                    style="padding-top: 7pt;padding-left: 39pt;text-indent: 0pt;text-align: left;">
                                    <?php
                                    $subjectPaper = DB::table('subject_paper_details')->where('subject_mapping_id', $studentPaperDetail?->subject_mapping_id)->where('subject_id', $studentPaperDetail?->subject_id)->first();
                                    
                                    $totalObtained += $studentPaperDetail?->obtained_marks; // This is now gross marks per subject
                                    $totalMax += $subjectPaper->max_marks;
                                    $totalQuestionsCount += $subjectPaper->total_questions;
                                    
                                    // Paper-wide totals
                                    $right_answers = $studentPaperDetail->right_answers;
                                    $wrong_answers = $studentPaperDetail->wrong_answers;
                                    $attempted_questions = $studentPaperDetail->attempted_questions;
                                    
                                    echo $subjectPaper->max_marks;
                                    ?>

                                </p>
                            </td>
                            <td class="tb1" style="width:163pt;">
                                <p class="s13"
                                    style="padding-top: 7pt;padding-left: 32pt;text-indent: 0pt;text-align: left;">
                                    {{ number_format($studentPaperDetail?->obtained_marks, 2) }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                    <?php
                        $notAttemptedQuestions = $totalQuestionsCount - $attempted_questions;
                        
                        // Calculate penalties paper-wide
                        $firstDetail = $studentPaperDetails->first();
                        $rules = DB::table('subject_paper_details')
                                ->where('subject_mapping_id', $firstDetail?->subject_mapping_id)
                                ->first();
                        
                        $wrongPenalty = $rules->negative_marks_wrong ?? 0;
                        $skippedPenalty = $rules->negative_marks_skipped ?? 0;
                        $penaltyAmount = ($wrong_answers * $wrongPenalty) + ($notAttemptedQuestions * $skippedPenalty);
                        $netTotal = $totalObtained - $penaltyAmount;
                    ?>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:188pt;" colspan="2">
                            <p class="s14"
                                style="padding-top: 5pt;padding-left: 22pt;text-indent: 0pt;text-align: left;">
                                Wrong
                                Answer-{{ $wrong_answers }}</p>
                        </td>
                        <td class="tb1" style="width:184pt">
                            <p class="s14"
                                style="padding-top: 4pt;padding-left: 36pt;text-indent: 0pt;text-align: left;">
                                Right
                                Answer-{{ $right_answers }}</p>
                        </td>
                        <td class="tb1" style="width:163pt">
                            <p class="s14"
                                style="padding-top: 4pt;padding-left: 14pt;text-indent: 0pt;text-align: left;">
                                Obtained Marks-{{ number_format($netTotal, 2) }}</p>
                        </td>
                    </tr>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:188pt" colspan="2">
                            <p class="s14"
                                style="padding-top: 3pt;padding-left: 22pt;text-indent: 0pt;text-align: left;">
                                Total
                                Questions-{{ $totalQuestionsCount }}</p>
                        </td>
                        <td class="tb1" style="width:184pt">
                            <p class="s14"
                                style="padding-top: 3pt;padding-left: 21pt;text-indent: 0pt;text-align: left;">
                                Not
                                Attempted Qs -{{ $notAttemptedQuestions }}</p>
                        </td>
                        <td class="tb1" style="width:163pt;">
                            <p class="s14"
                                style="padding-top: 3pt;padding-left: 14pt;text-indent: 0pt;text-align: left;">
                                Attempted Qs -{{ $attempted_questions }}</p>
                        </td>
                    </tr>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:535pt" colspan="4">
                            <p class="s15"
                                style="padding-top: 6pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                                Total Marks - @if ($totalMax > 0)
                                    {{ number_format($netTotal, 2) }}/{{ $totalMax }}
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:535pt" colspan="4">
                            <p class="s15"
                                style="padding-top: 5pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                                Marks in Percentage- @if ($totalMax > 0)
                                    {{ round(($netTotal / $totalMax) * 100, 2) }}%
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:50%" colspan="3">
                            <p class="s15"
                                style="padding-top: 7pt;padding-left: 88pt;text-indent: 0pt;text-align: left;">
                                All
                                India Rank - {{ $appCode->rank }}</p>
                        </td>
                        <td class="tb1" style="width:50%">
                            <p class="s15"
                                style="padding-top: 7pt;padding-left: 83pt;text-indent: 0pt;text-align: left;">
                                State Rank - {{ $appCode->state_rank }}</p>
                        </td>
                    </tr>
                    <tr style="height:26pt">
                        <td class="tb1" style="width:50%;" colspan="3">
                            <p class="s15"
                                style="padding-top: 4pt;padding-left: 90pt;text-indent: 0pt;text-align: left;">
                                Gender Rank - {{ $appCode->gender_rank }}</p>
                        </td>
                        <td class="tb1" style="width:50%;">
                            <p class="s15"
                                style="padding-top: 5pt;padding-left: 71pt;text-indent: 0pt;text-align: left;">
                                District - {{ $appCode->district_rank }}</p>
                        </td>
                    </tr>
                </table>

                @if ($student->scholarship_claim_generation_id)
                    <p class="s16" style="padding-top: 5pt;padding-left: 35pt;text-indent: 0pt;text-align: center;">
                        Congratulations!</p>
                    <div class="">
                        <p class="s17"
                            style="padding-top: 4pt;text-indent: 0pt;text-align: center;background-color: #a5afb7;padding: 6pt;margin-top: 7pt;">
                            You are Eligible for Scholarship
                        </p>
                    </div>
                @endif

            </center>
        </div>

        @if ($student->scholarship_claim_generation_id)
            <div class="col-md-12 mt-2" align="center">
                <button class="btn btn-md btn-success" data-toggle="modal" data-target="#myModal" type="button">
                    Claim Your Scholarship
                </button>
            </div>
        @endif

        <div class="col-md-12 mt-2" align="center">
            <button class="btn btn-sm btn-info" data-print="modal" type="button"
                onclick="PrintDoc()">Print <i class="fa fa-print" style="margin-left: 7px;"></i></button>
        </div>

    </div>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <form class="form" method="POST" action="{{ route('students.claimScholarshipForm') }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Terms and Conditions</h4>
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                    </div>
                    <form class="form" method="POST" action="{{ route('students.claimScholarshipForm') }}">
                        @csrf
                        <!-- Modal Body -->
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12 col">
                                    <div class="form-check mb-3 mt-2">
                                        <input name="terms_conditions_scholarship" type="hidden" value="0">
                                        <input class="form-check-input" id="terms_conditions_scholarship"
                                            name="terms_conditions_scholarship" type="checkbox" value="1"
                                            {{ $student->terms_conditions_scholarship ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms_conditions_scholarship"><span
                                                class="text-danger">*</span> I agree
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Submit and Proceed</button>
                            <button class="btn btn-danger" data-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>

    <script>
        function PrintDoc() {
            var toPrint = document.getElementById('prodiv');

            var popupWin = window.open('', '_blank',
                'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

            popupWin.document.open();

            popupWin.document.write(
                '<html><title >Result</title><head><style>body{font-family:Arial;} .noprint{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;} .photo img {width: 115px;}</style></head><body onload="window.print()">'
            )

            popupWin.document.write(toPrint.innerHTML);

            popupWin.document.close();
        }
    </script>
@endsection('content')
