@extends('administrator.layouts.master')
@section('title')
Student 
@endsection
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
</style>
<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$country = "";
$state = '';
$city = '';

if ($student->state_id) {
   $state = DB::table('states')->where('id', $student->state_id)->first()?->name;
}


?>


<!-- InstanceBeginEditable name="Content Area" -->
<div class="container-fluid pagecontentbody">
   <div class="tab-content">
      <div class="pagebody p-4">
         <div class="row">
            <div class="col-md-9">

            </div>
            <div class="col-md-3">
               <a class="btn btn-info float-end Exam" href="{{route('admin.home')}}" style="width: 6rem;
    height: 2.65rem;">
                  <svg viewBox="100 0 1024 1024" fill="#ffffff" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" style="width: 2rem; height: 2rem;">
                     <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                     <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                     <g id="SVGRepo_iconCarrier">
                        <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path>
                     </g>
                  </svg>
                  back
               </a>
            </div>

         </div>

            <div class="row justify-content-center">
               <div class="col-md-10">
                  <div id="prodiv">
                     <div id="content" style="position:relative;">
                        <table class="bsvtbl" border="0" cellspacing="0" cellpadding="8" width="100%" style="border-collapse:collapse;">
                           <thead>
                              <tr>
                                 <th colspan="4">
                                    <div style="padding: 0 15px 10px; margin-bottom: 10px; border-bottom: 2px solid #000; position: relative;">
                                       <img src="{{asset('assets/images/logo.png')}}" style="width: 60px; height: auto; position: absolute; top: 0px; left: 20px;" />
                                       <h1 style="text-align: center; font-size: 20pt; margin: 0px 0px 0px 0px; padding: 0px 0 0; color: #383838; font-weight: bold;">
                                          Career without Barrier
                                       </h1>
                                       <h2 style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#383838; font-weight: bold;">
                                          Registration Form {{date('Y')}}
                                       </h2>
                                    </div>
                                 </th>
                              </tr>
                              <tr class="dn">
                                 <th style="font-size: 10pt; text-align:left">Please tick the checkbox &nbsp;&nbsp; &check;</th>
                                 <th colspan="2" style="text-align: right; font-size: 10pt;"><strong>Printed On : </strong> {{date('d/m/Y h:ia')}}</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>
                                    <b>Student Name</b>
                                 </td>
                                 <td>
                                    <input type="hidden" name="name_checked" value="0"> <input title="Please Tick" type="checkbox" name="name_checked" class="form-check-input" id="name_checked" value="1" {{ $student->name_checked ? 'checked' : '' }} required>
                                    &nbsp;&nbsp; {{$student->name}}
                                 </td>
                                 <td rowspan="5" colspan="2" align="center">
                                    <div class="text-center" style="display: inline-grid;">
                                       <img src="{{url('upload/student/')}}/{{$student->photograph}}" class="img-fluid" style="width: 160px;border: 1px double #dee2e6;padding: 4px;height: 150px;">
                                       <img src="{{url('upload/student/')}}/{{$student->signature}}" class="img-fluid" style="width: 160px;border: 1px double #dee2e6;padding: 4px;height: 60px;">
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Name of Father</b>
                                 </td>
                                 <td>
                                    <input type="hidden" name="father_name_checked" value="0"> <input title="Please Tick" type="checkbox" name="father_name_checked" class="form-check-input" id="father_name_checked" value="1" {{ $student->father_name_checked ? 'checked' : '' }} required>
                                    &nbsp;&nbsp; {{$student->father_name}}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Date of Birth</b>
                                 </td>
                                 <td>
                                    <input type="hidden" name="dob_checked" value="0"> <input title="Please Tick" type="checkbox" name="dob_checked" class="form-check-input" id="dob_checked" value="1" {{ $student->dob_checked ? 'checked' : '' }} required>
                                    &nbsp;&nbsp; {{Carbon::parse($student->dob)->format('d/m/Y')}} ({{str_replace('ago','',Carbon::parse($student->dob)->diffForHumans())}})
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Mobile Number</b>
                                 </td>
                                 <td>
                                    <input type="hidden" name="mobile_checked" value="0"> <input title="Please Tick" type="checkbox" name="mobile_checked" class="form-check-input" id="mobile_checked" value="1" {{ $student->mobile_checked ? 'checked' : '' }} required>
                                    &nbsp;&nbsp; {{$student->mobile}}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Email ID</b>
                                 </td>
                                 <td>
                                    <input type="hidden" name="email_checked" value="0"> <input title="Please Tick" type="checkbox" name="email_checked" class="form-check-input" id="email_checked" value="1" {{ $student->email_checked ? 'checked' : '' }} required>
                                    &nbsp;&nbsp; {{$student->email}}
                                 </td>

                              </tr>

                              <tr>
                                 <td>
                                    <b>Address</b>
                                 </td>
                                 <td>
                                    {{$student->address}}
                                 </td>
                                 <td>
                                    <b>State</b>
                                 </td>
                                 <td>
                                    {{$state}}
                                 </td>

                              </tr>
                              <tr>
                                 <td>
                                    <b>City</b>
                                 </td>
                                 <td>
                                    {{$student->district?->DistrictName}}
                                 </td>
                                 <td>
                                    <b>Pincode</b>
                                 </td>
                                 <td>
                                    {{$student->pincode}}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Qualification</b>
                                 </td>
                                 <td>
                                    {{ $student->qualification ?? 'N/A' }}
                                 </td>
                                 <td>
                                    <b>Scholarship Category</b>
                                 </td>
                                 <td>
                                    {{ $student->scholarship_category ?? 'N/A' }}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Scholarship Opted For</b>
                                 </td>
                                 <td>
                                    {{ $student->scholarship_opted_for ?? 'N/A' }}
                                 </td>

                                 <td>
                                    <b>Choice of Test Centre (A)</b>
                                 </td>
                                 <td>
                                    {{ $student->choiceCenterA?->DistrictName ?? 'N/A' }}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Choice of Test Centre (B)</b>
                                 </td>
                                 <td>
                                    {{ $student->choiceCenterB?->DistrictName ?? 'N/A' }}
                                 </td>
                                 <td>
                                    <b>Choice of Test Centre (C)</b>
                                 </td>
                                 <td>
                                    {{ $student->test_center_c ?? 'N/A' }}
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
                                    @if($student->is_gov_exam_participated == 'yes')
                                    @if($student->govt_exams_1) <b>Exam 1</b><br>
                                    @endif
                                    @if($student->govt_exams_2)<br> <b>Exam 2</b><br>
                                    @endif
                                    @endif
                                 </td>
                                 <td>
                                    @if($student->is_gov_exam_participated == 'yes')
                                    {{$student->govt_exams_1}}
                                    @if($student->exam_one_year)
                                    , Roll No: {{$student->exam_one_year}}
                                    @endif
                                    @if($student->exam_one_result)
                                    <br> Result: {{$student->exam_one_result}} %
                                    @endif

                                    @if($student->govt_exams_2)
                                    <br>
                                    {{$student->govt_exams_2}}
                                    @if($student->exam_two_year)
                                    , Roll No: {{$student->exam_two_year}}
                                    @endif
                                    @if($student->exam_two_result)
                                    <br> Result: {{$student->exam_two_result}} %
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
                                    @if($student->is_apply_career_without_barrier =='yes' && $student->year)
                                    <b>Year: </b> &nbsp; {{$student->year}}
                                    @endif
                                 </td>
                                 <td>
                                    @if($student->is_apply_career_without_barrier =='yes' && $student->roll_no)
                                    <b>Roll No: </b> &nbsp; {{$student->roll_no}}
                                    @endif
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>Occupation</b> &nbsp;&nbsp;&nbsp;

                                 </td>
                                 <td>Father: &nbsp;&nbsp;{{$student->father_occupation}} <br>
                                    Mother: &nbsp;&nbsp;{{$student->mother_occupation}}
                                 </td>
                                 <td>
                                    <b>Family Income</b>&nbsp;
                                 </td>
                                 <td>
                                    {{familyIncome($student->family_income)}}
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <b>I agree for Career without Barrier’s Terms & Conditions</b>
                                 </td>
                                 <td>
                                    @if($student->terms_conditions) &check; @else No @endif
                                 </td>
                                 <td>
                                    <b>Registration Date</b>
                                 </td>
                                 <td>
                                    {{\Carbon\Carbon::parse($student->created_at)->format('d/m/Y')}}
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
               @if($student->is_final_submitted)
               <div class="col-md-2 d-grid">
                  <button type="submit" class="btn btn-payment">Proceed To Pay</button>
               </div>
               <div align="center" class="col-md-2 d-grid">
                  <button type="button" class="btn btn-md btn-info" data-print="modal" onclick="PrintDoc()"> Print </button>
               </div>
               @else
               <div class="col-md-2 d-grid">
                  <button type="submit" class="btn btn-payment">Final Submit</button>
               </div>
               @endif
            </div>

      </div>
   </div>
</div>
<!-- InstanceEndEditable -->
<script>
   function PrintDoc() {
      var toPrint = document.getElementById('prodiv');

      var popupWin = window.open('', '_blank', 'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

      popupWin.document.open();

      popupWin.document.write('<html><title>Summer Registration</title><head><style>body{font-family:Arial} .noprint{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;} .photo img {width: 115px;}</style></head><body onload="window.print()">')

      popupWin.document.write(toPrint.innerHTML);

      popupWin.document.close();
   }
</script>
@endsection