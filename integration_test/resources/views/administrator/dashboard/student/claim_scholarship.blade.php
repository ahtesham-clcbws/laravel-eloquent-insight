@extends('administrator.layouts.master')

@section('title')
Student Claim Form
@endsection

@section('content')
<style>
    .dn{
        display: none;
    }
</style>
<div class="container pagecontentbody">
    <div class="tab-content">
        <div class="row pt-3">
            <div class="col-md-6">
                <h5>
                    Claim Form
                </h5>
            </div>
            <div class="col-md-6">
                @if($claimForm)
                <div class="card bg-light border-info mb-3">
                    <div class="card-body py-2">
                        <form action="{{ route('admin.student.claim_status_update', $claimForm->id) }}" method="POST" class="row align-items-center">
                            @csrf
                            <div class="col-auto">
                                <label class="mb-0"><strong>Status:</strong></label>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending-processing" {{ $claimForm->status == 'pending-processing' ? 'selected' : '' }}>Pending-Processing</option>
                                    <option value="confirmed" {{ $claimForm->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="rejected" {{ $claimForm->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-info">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="pagebody  pt-2">
            <div class=" col-md-12" style="padding: 8px 25px;" id="prodiv">
                <!-- Choice One / Two -->
                <div class="dn mt-2 " style="text-align: center;">
                    <h3 style="margin-top:2px;margin-bottom:2px">

                        Career Without Barrier

                    </h2
                    <h3 style="margin-top:2px;margin-bottom:2px">Student Name:{{$student->name}}</h3> <h4 style="margin-top:2px;margin-bottom:2px">App Code:{{$student->latestStudentCode?->application_code}}</h4>
                    <h4 style="margin-top:2px;margin-bottom:2px">City: {{$student->district?->name}}</h4
                    <h6 style="margin-top:2px;margin-bottom:2px">Rank:{{$student->latestStudentCode->rank}}</h6>
                    <h5 style="margin-top:2px;margin-bottom:2px">
                        Student Claim Form
                    </h5>
                    <hr>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <th style="background-color: #3d84ca;" colspan="4">Choice One</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Institute Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_name1}}</td>
                            <th>Institute’s Director’s Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_director1}}</td>
                        </tr>
                        <tr>
                            <th>Institute/ Contact Details</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_mobile1}}</td>
                            <th>Whatsapp Contact</th>
                            <td style="width: 210px;" >{{$claimForm?->whatsapp_no1}}</td>
                        </tr>
                        <tr>
                            <th>Institute E-mail Id</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_email1}}</td>
                            <th>State</th>
                            <td style="width: 210px;" >{{$student->state?->name}}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td style="width: 210px;" >{{$student?->district?->name}}</td>
                            <th>Institute Address</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_address1}}</td>
                        </tr>
                        <tr>
                            <th>Desired Course Detail</th>
                            <td style="width: 210px;" >{{$claimForm?->desired_course_detail1}}</td>
                            <th>Course Fee</th>
                            <td style="width: 210px;" >{{$claimForm?->course_fee1}}</td>
                        </tr>
                        <tr>
                            <th>Course Duration</th>
                            <td style="width: 210px;" >{{$claimForm?->course_duration1}}</td>
                            <th>Institute Prospectus</th>
                            <td style="width: 210px;" >
                                @if($claimForm?->institude_prospectus1)
                                    <a href="{{ asset('upload/' . $claimForm->institude_prospectus1) }}" target="_blank">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Choice Two -->

                <table class="table table-bordered">
                    <thead>
                        <th style="background-color: #33ded4" colspan="4">Choice Two</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Institute Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_name2}}</td>
                            <th>Institute’s Director’s Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_director2}}</td>
                        </tr>
                        <tr>
                            <th>Institute/ Contact Details</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_mobile2}}</td>
                            <th>Whatsapp Contact</th>
                            <td style="width: 210px;" >{{$claimForm?->whatsapp_no2}}</td>
                        </tr>
                        <tr>
                            <th>Institute E-mail Id</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_email2}}</td>
                            <th>State</th>
                            <td style="width: 210px;" >{{$student->state?->name}}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td style="width: 210px;" >{{$student?->district?->name}}</td>
                            <th>Institute Address</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_address2}}</td>
                        </tr>
                        <tr>
                            <th>Desired Course Detail</th>
                            <td style="width: 210px;" >{{$claimForm?->desired_course_detail2}}</td>
                            <th>Course Fee</th>
                            <td style="width: 210px;" >{{$claimForm?->course_fee2}}</td>
                        </tr>
                        <tr>
                            <th>Course Duration</th>
                            <td style="width: 210px;" >{{$claimForm?->course_duration2}}</td>
                            <th>Institute Prospectus</th>
                            <td style="width: 210px;" >
                                @if($claimForm?->institude_prospectus2)
                                    <a href="{{ asset('upload/' . $claimForm->institude_prospectus2) }}" target="_blank">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>


                <!-- Choce Third/ Fourth -->

                <!-- Choice Third -->

                <table class="table table-bordered">
                    <thead>
                        <th style="background-color: #28a745bd;" colspan="4">Choice Third</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Institute Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_name3}}</td>
                            <th>Institute’s Director’s Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_director3}}</td>
                        </tr>
                        <tr>
                            <th>Institute/ Contact Details</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_mobile3}}</td>
                            <th>Whatsapp Contact</th>
                            <td style="width: 210px;" >{{$claimForm?->whatsapp_no3}}</td>
                        </tr>
                        <tr>
                            <th>Institute E-mail Id</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_email3}}</td>
                            <th>State</th>
                            <td style="width: 210px;" >{{$claimForm?->state?->name}}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td style="width: 210px;" >{{$claimForm?->district?->name}}</td>
                            <th>Institute Address</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_address3}}</td>
                        </tr>
                        <tr>
                            <th>Desired Course Detail</th>
                            <td style="width: 210px;" >{{$claimForm?->desired_course_detail3}}</td>
                            <th>Course Fee</th>
                            <td style="width: 210px;" >{{$claimForm?->course_fee3}}</td>
                        </tr>
                        <tr>
                            <th>Course Duration</th>
                            <td style="width: 210px;" >{{$claimForm?->course_duration3}}</td>
                            <th>Institute Prospectus</th>
                            <td style="width: 210px;" >
                                @if($claimForm?->institude_prospectus3)
                                    <a href="{{ asset('upload/' . $claimForm->institude_prospectus3) }}" target="_blank">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Choice Two -->

                <table class="table table-bordered">
                    <thead>
                        <th style="background-color: #9fa728e6;" colspan="4">Choice Fourth</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Institute Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_name4}}</td>
                            <th>Institute’s Director’s Name</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_director4}}</td>
                        </tr>
                        <tr>
                            <th>Institute/ Contact Details</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_mobile4}}</td>
                            <th>Whatsapp Contact</th>
                            <td style="width: 210px;" >{{$claimForm?->whatsapp_no4}}</td>
                        </tr>
                        <tr>
                            <th>Institute E-mail Id</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_email4}}</td>
                            <th>State</th>
                            <td style="width: 210px;" >{{$claimForm?->state?->name}}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td style="width: 210px;" >{{$claimForm?->district?->name}}</td>
                            <th>Institute Address</th>
                            <td style="width: 210px;" >{{$claimForm?->institude_address4}}</td>
                        </tr>
                        <tr>
                            <th>Desired Course Detail</th>
                            <td style="width: 210px;" >{{$claimForm?->desired_course_detail4}}</td>
                            <th>Course Fee</th>
                            <td style="width: 210px;" >{{$claimForm?->course_fee4}}</td>
                        </tr>
                        <tr>
                            <th>Course Duration</th>
                            <td style="width: 210px;" >{{$claimForm?->course_duration4}}</td>
                            <th>Institute Prospectus</th>
                            <td style="width: 210px;" >
                                @if($claimForm?->institude_prospectus4)
                                    <a href="{{ asset('upload/' . $claimForm->institude_prospectus4) }}" target="_blank">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<hr>
<div align="center" class="col-md-12">
    <button type="button" style="width: 6rem;height: 2.65rem;" class=" mt-4 mb-4 btn btn-md btn-info" data-print="modal" onclick="PrintDoc()"> Print<i class="fa fa-print" style="margin-left: 7px;"></i> </button>
</div>

<script>
    function PrintDoc() {
        var toPrint = document.getElementById('prodiv');

        var popupWin = window.open('', '_blank', 'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

        popupWin.document.open();

        popupWin.document.write('<html><title>Admit Card</title><head><style>body{font-family:Arial}.table{margin-top:30px;min-height:200px}.noprint{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;} .photo img {width: 115px;}</style></head><body onload="window.print()">')

        popupWin.document.write(toPrint.innerHTML);

        popupWin.document.close();
    }
</script>
@endsection('content')