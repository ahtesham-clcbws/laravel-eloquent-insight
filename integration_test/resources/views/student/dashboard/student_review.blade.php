@extends('student.layouts.master')
@section('content')
    <style>
        .inside-table>thead>tr>th {
            border-color: #ccc;
            background-color: #fff;
            color: black
        }

        .inside-table thead {
            background-color: #fff !important;
            border-bottom: 1px solid #ccc
        }

        .inside-table> :not(:last-child)> :last-child>* {
            border-bottom-color: #ccc
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            input[type="checkbox"] {
                display: none !important;
            }
        }
    </style>
    <?php
    
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;
    
    $country = '';
    $state = '';
    $city = '';
    
    if ($student->state_id) {
        $state = DB::table('states')->where('id', $student->state_id)->first()?->name;
    }
    $studentCode = $student->studentCode()->first();
    
    ?>

    @if ($student->is_final_submitted)
        <style>
            input,
            select {
                pointer-events: none;
            }
        </style>
    @endif

    <!-- InstanceBeginEditable name="Content Area" -->
    <div class="pagecontentbody container">
        <div class="tab-content">
            <div class="pagebody p-4">
                <div class="text-end">
                    <button data-print="modal" type="button" style="border: 1px solid #17a2b8;background: #17a2b8;color:#fff"
                        onclick="PrintDoc()"> <i class="fa fa-print"></i> </button>
                </div>
                <form action="{{ $student->is_final_submitted ? '#' : route('students.finalSubmit') }}" method="post">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div id="prodiv">
                                <div id="content" style="position:relative;">
                                    <table class="bsvtbl" style="border-collapse:collapse;" border="0" cellspacing="0"
                                        cellpadding="8" width="100%">
                                        <thead>
                                            <tr>
                                                <th colspan="4">
                                                    <div
                                                        style="padding: 0 15px 10px; margin-bottom: 10px; border-bottom: 2px solid #000; position: relative;">
                                                        <img src="{{ asset('website/assets/images/fav-icon.png') }}"
                                                            style="width: 50px; height: auto; position: absolute; top: 0px; left: 20px;" />
                                                        <h1
                                                            style="text-align: center; font-size: 20pt; margin: 0px 0px 0px 0px; padding: 0px 0 0; color: #383838; font-weight: bold;">
                                                            Career without Barrier
                                                        </h1>
                                                        <h2
                                                            style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#383838; font-weight: bold;">
                                                            Registration Form {{ date('Y') }}
                                                        </h2>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr class="dn">
                                                <th style="font-size: 12pt; text-align:left;font-weight:700;font-size:large;color:red"
                                                    colspan="2">
                                                    <span class="no-print">Please tick the checkbox &nbsp;&nbsp;
                                                        &check;</span>
                                                </th>

                                                <th style="text-align: right; font-size: 10pt;" colspan="2">
                                                    <strong>Registration Date : </strong>
                                                    {{ $student->registration_date ? date_format($student->registration_date, 'd-m-Y') : date_format($student->created_at, 'd-m-Y') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <b>Student Name</b>
                                                </td>

                                                <td colspan="2">
                                                    <input name="name_checked" type="hidden" value="0"> <input
                                                        class="form-check-input" id="name_checked" name="name_checked"
                                                        type="checkbox" value="1" title="Please Tick"
                                                        {{ $student->name_checked ? 'checked' : '' }} required>
                                                    &nbsp;&nbsp; {{ $student->name }}
                                                </td>
                                                <td rowspan="5" colspan="2" align="center">
                                                    <div class="text-center" style="display: inline-grid; studentImage">
                                                        <img class="img-fluid"
                                                            src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/' . $student->photograph : '/upload/student/' . $student->photograph }}"
                                                            style="width: 160px;border: 1px double #dee2e6;padding: 4px;height: 160px;">
                                                        <img class="img-fluid"
                                                            src="{{ explode('/', $student->signature)[0] == 'student' ? '/storage/' . $student->signature : '/upload/student/' . $student->signature }}"
                                                            style="width: 160px;border: 1px double #dee2e6;padding: 4px;height: 160px;">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Name of Father</b>
                                                </td>
                                                <td colspan="2">
                                                    <input name="father_name_checked" type="hidden" value="0"> <input
                                                        class="form-check-input" id="father_name_checked"
                                                        name="father_name_checked" type="checkbox" value="1"
                                                        title="Please Tick"
                                                        {{ $student->father_name_checked ? 'checked' : '' }} required>
                                                    &nbsp;&nbsp; {{ $student->father_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Date of Birth</b>
                                                </td>
                                                <td colspan="2">
                                                    <input name="dob_checked" type="hidden" value="0"> <input
                                                        class="form-check-input" id="dob_checked" name="dob_checked"
                                                        type="checkbox" value="1" title="Please Tick"
                                                        {{ $student->dob_checked ? 'checked' : '' }} required> &nbsp;&nbsp;
                                                    {{ Carbon::parse($student->dob)->format('d/m/Y') }}
                                                    <small>({{ str_replace('ago', '', Carbon::parse($student->dob)->diffForHumans()) }})</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Mobile Number</b>
                                                </td>
                                                <td colspan="2">
                                                    <input name="mobile_checked" type="hidden" value="0"> <input
                                                        class="form-check-input" id="mobile_checked" name="mobile_checked"
                                                        type="checkbox" value="1" title="Please Tick"
                                                        {{ $student->mobile_checked ? 'checked' : '' }} required>
                                                    &nbsp;&nbsp; {{ $student->mobile }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Email ID</b>
                                                </td>
                                                <td colspan="2">
                                                    <input name="email_checked" type="hidden" value="0"> <input
                                                        class="form-check-input" id="email_checked" name="email_checked"
                                                        type="checkbox" value="1" title="Please Tick"
                                                        {{ $student->email_checked ? 'checked' : '' }} required>
                                                    &nbsp;&nbsp;
                                                    {{ $student->email }}

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <b>Address</b>
                                                </td>
                                                <td>
                                                    {{ $student->address }}
                                                </td>
                                                <td>
                                                    <b>State</b>
                                                </td>
                                                <td colspan="2">
                                                    {{ $state }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>City</b>
                                                </td>
                                                <td>
                                                    {{ $student->district?->name }}
                                                </td>
                                                <td>
                                                    <b>Pincode</b>
                                                </td>
                                                <td colspan="2">
                                                    {{ $student->pincode }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Qualification</b>
                                                </td>
                                                <td colspan="3">
                                                    <input name="qualification_checked" type="hidden" value="0">
                                                    <input class="form-check-input" id="qualification_checked"
                                                        name="qualification_checked" type="checkbox" value="1"
                                                        title="Please Tick"
                                                        {{ $student->qualification_checked ? 'checked' : '' }} required>
                                                    {{ $student->qualifications?->name ?? 'N/A' }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Scholarship Category</b>
                                                </td>
                                                <td>
                                                    <div style="display: flex;">
                                                        <div>

                                                            <input name="scholarship_category_checked" type="hidden"
                                                                value="0"> <input class="form-check-input"
                                                                id="scholarship_category_checked"
                                                                name="scholarship_category_checked" type="checkbox"
                                                                value="1" title="Please Tick"
                                                                {{ $student->scholarship_category_checked ? 'checked' : '' }}
                                                                required>
                                                        </div>
                                                        &nbsp; <div>
                                                            {{ $student->scholarShipCategory?->name ?? 'N/A' }}
                                                        </div>
                                                    </div>

                                                </td>
                                                <td>
                                                    <b>Scholarship Opted For</b>
                                                </td>
                                                <td>
                                                    <div style="display: flex;margin-left: 13px;">
                                                        <input name="scholarship_opted_for_checked" type="hidden"
                                                            value="0"> <input class="form-check-input"
                                                            id="scholarship_opted_for_checked"
                                                            name="scholarship_opted_for_checked" type="checkbox"
                                                            value="1" title="Please Tick"
                                                            {{ $student->scholarship_opted_for_checked ? 'checked' : '' }}
                                                            required>
                                                        <div>
                                                            {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </td>
                                            <tr>
                                                <td>
                                                    <b>Choice of Test Centre (A)</b>
                                                </td>
                                                <td>
                                                    <input name="choiceCenterA_checked" type="hidden" value="0">
                                                    <input class="form-check-input" id="choiceCenterA_checked"
                                                        name="choiceCenterA_checked" type="checkbox" value="1"
                                                        title="Please Tick"
                                                        {{ $student->choiceCenterA_checked ? 'checked' : '' }} required>
                                                    &nbsp;&nbsp; {{ $student->choiceCenterA?->name ?? 'N/A' }}
                                                </td>

                                                <td>
                                                    <b>Choice of Test Centre (B)</b>
                                                </td>
                                                <td style="padding-left:20px;">
                                                    <input name="choiceCenterB_checked" type="hidden" value="0">
                                                    <input class="form-check-input" id="choiceCenterB_checked"
                                                        name="choiceCenterB_checked" type="checkbox" value="1"
                                                        title="Please Tick"
                                                        {{ $student->choiceCenterB_checked ? 'checked' : '' }} required>
                                                    {{ $student->choiceCenterB?->name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Participate in any Govt/ Competitive Exam(s)?</b>
                                                </td>
                                                <td>
                                                    {{ ucfirst($student->is_gov_exam_participated) ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($student->is_gov_exam_participated == 'yes')
                                                        @if ($student->govt_exams_1)
                                                            <b>Exam 1</b><br>
                                                        @endif
                                                        @if ($student->govt_exams_2)
                                                            <br> <b>Exam 2</b><br>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td colspan="2">
                                                    @if ($student->is_gov_exam_participated == 'yes')
                                                        {{ $student->govt_exams_1 }}
                                                        @if ($student->exam_one_year)
                                                            , Roll No: {{ $studentCode?->roll_no }}
                                                        @endif
                                                        @if ($student->exam_one_result)
                                                            <br> Result: {{ $student->exam_one_result }} %
                                                        @endif

                                                        @if ($student->govt_exams_2)
                                                            <br>
                                                            {{ $student->govt_exams_2 }}
                                                            @if ($student->exam_two_year)
                                                                , Roll No: {{ $studentCode?->roll_no }}
                                                            @endif
                                                            @if ($student->exam_two_result)
                                                                <br> Result: {{ $student->exam_two_result }} %
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Apply for the Career Without Barrier Scholarship Program</b>
                                                </td>
                                                <td>
                                                    {{ ucfirst($student->is_apply_career_without_barrier) ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($student->is_apply_career_without_barrier == 'yes' && $student->year)
                                                        <b>Year: </b> &nbsp; {{ $student->year }}
                                                    @endif
                                                </td>
                                                <td colspan="2">
                                                    @if ($student->is_apply_career_without_barrier == 'yes' && $studentCode?->roll_no)
                                                        <b>Roll No: </b> &nbsp; {{ $studentCode?->roll_no }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Father Occupation </b> &nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td>{{ $student->father_occupation }} </td>
                                                <td><b>Mother Occupation </b></td>
                                                <td>{{ $student->mother_occupation }}</td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Family Income</b>&nbsp;
                                                </td>
                                                <td>
                                                    {{ familyIncome($student->family_income) }}
                                                </td>
                                                <td><b>Person with Disability</b></td>
                                                <td>{{ $student->disability }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <b>I agree for Career without Barrier’s <a href="/p/student-terms-and-conditions"
                                                                style="text-decoration: underline;" target="_blank"> Terms
                                                                & Conditions </a></b> &nbsp;&nbsp; &check;

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <hr />
                        </div>
                        @if ($student->is_final_submitted)
                            @if (!$studentCode?->is_paid && $studentCode?->used_coupon == 0)
                                <div class="col-md-2 d-grid">
                                    <a class="btn btn-payment" type="submit"
                                        href="{{ route('student.payment') }}">Proceed To Pay</a>
                                </div>
                            @endif
                        @else
                            <div class="col-md-2 d-grid">
                                <button class="btn btn-payment" type="submit">Final Submit</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- InstanceEndEditable -->
    <script>
        function PrintDoc() {
            var toPrint = document.getElementById('prodiv');

            var popupWin = window.open('', '_blank',
                'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

            popupWin.document.open();

            popupWin.document.write(
                '<html><title>Summer Registration</title><head><style>body{font-family:Arial} input[type="checkbox"]{display: none !important;} .noprint{display: none;} .no-print{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;} .photo img {width: 115px;}</style></head><body onload="window.print()">'
            )

            popupWin.document.write(toPrint.innerHTML);

            popupWin.document.close();
        }
    </script>
@endsection
