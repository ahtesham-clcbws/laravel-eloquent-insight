@extends('administrator.layouts.master')

@section('title')
Student List
@endsection
@section('content')

<style>
    .select2-selection__choice {
        color: black !important;
    }
</style>
<div class="row px-3">
    <div class="col-lg-12">
        <div class="m-2 m-t-15">
            <div class="row justify-content-space-between py-2">
                <div class="col-md-6 col">
                    <h2>Student List</h2>
                </div>
                <div class="col-md-6 col text-end">
                    <a href="{{route('admin.print.studentList')}}" target="blank"
                        class="btn btn-primary btn-small">Print PDF</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatablecl">
                            <thead>
                                <tr>
                                    <th scope="col">##</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Email/Mobile</th>
                                    <th scope="col">District<br />Centre</th>
                                    <th scope="col">Appl No</th>
                                    <th scope="col">Roll No</th>
                                    <th scope="col">Payment & Voucher</th>
                                    <th scope="col">Qualification</th>
                                    <th scope="col">Scholarship Category</th>
                                    <th scope="col">Scholarship Opted For</th>
                                    <th scope="col">Dated</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <th scope="text-left">{{$loop->index+1}}</th>
                                    <td class="text-nowrap">
                                        {{ $student->name }}<br />
                                        <span>{{$student->gender}}</span><br />
                                        <span>{{ $student->dob }}</span>
                                    </td>
                                    <td>{{ $student->email }}<br />
                                        {{ $student->mobile }}<br />
                                        {{ $student->login_password }}
                                    </td>
                                    <td>{{ $student->district?->name }}</td>
                                    <td>{{ $student->latestStudentCode?->application_code ? $student->latestStudentCode?->application_code : 'N/A'}} </td>
                                    <td>{{ !empty($student->latestStudentCode?->roll_no) ? $student->latestStudentCode?->roll_no :'N/A' }}</td>
                                    <td>
                                        ₹ {{ $student->studentPayment && count($student->studentPayment) && !empty($student->studentPayment[0]) && $student->studentPayment[0]->payment_amount ? $student->studentPayment[0]->payment_amount : 0}}
                                        <br />
                                        {{ $student->latestStudentCode?->coupan_code ? $student->latestStudentCode?->coupan_code : '' }}
                                        {!! $student->latestStudentCode?->coupan_code ? '<br />'.($student->latestStudentCode?->corporate?->institute_name ? $student->latestStudentCode->corporate->institute_name : ($student->latestStudentCode?->corporate_name ? $student->latestStudentCode?->corporate_name : 'SQS Foundation, Kanpur')) : '' !!}
                                    </td>
                                    <td>{{ $student->qualifications?->name }}</td>
                                    <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                    <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                    <td>{{ date('d-M-Y', strtotime($student->created_at)) }}</td>

                                    <td style="text-align:center">
                                        <a href="{{ route('admin.student', $student->id) }}" class="btn btn-primary"
                                            style="text-decoration: none;"></i> View</a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection('content')