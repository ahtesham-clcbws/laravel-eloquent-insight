@extends('administrator.layouts.master')

@section('title')
Registered Student
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
                    <h2>Registered Students</h2>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatablecl">
                            <thead>
                                <tr>
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Email/Mobile</th>
                                    <th scope="col">City & center</th>
                                    <th scope="col">Application Code</th>
                                    <th scope="col">Payment & Voucher</th>
                                    <th scope="col">Scholarship Category</th>
                                    <th scope="col">Scholarship Opted</th>
                                    <th scope="col">Step</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <th scope="row">{{$loop->index + 1}}</th>
                                    <td>
                                        {{ $student->name }}<br>
                                        <span>{{$student->gender}}</span><br>
                                        <span>{{ $student->dob }}</span><br>
                                    </td>
                                    <td>
                                        {{ $student->email }} <br>
                                        {{ $student->mobile }}<br>
                                        {{ $student->login_password }}
                                    </td>
                                    <td>
                                        {{$student->district?->name}}
                                        @php($center =
                                        DB::table('districts')->where('id',$student->test_center_a)->first())
                                        <br />
                                        {{ $center?->name }}
                                    </td>
                                    <td>{{$student->latestStudentCode?->application_code ? $student->latestStudentCode?->application_code : 'NA'}}<br>
                                        @if(!empty($student->latestStudentCode?->roll_no))
                                            <span style="color:red;">R.No:{{ $student->latestStudentCode?->roll_no }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>

                                        @php($studentPayment = $student->studentPayment->last())
                                        @if(!empty($studentPayment))

                                        {{$studentPayment->payment_amount}}

                                        <bt />
                                        {{$student->latestStudentCode?->coupan_code ? 'Coupon:- ' . $student->latestStudentCode?->coupan_code : ''}}
                                        @endif

                                    </td>
                                    <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                    <td>
                                        {!! $student->qualifications?->name ? $student->qualifications?->name . '<br/>' : '' !!}
                                        {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}
                                    </td>
                                    <td>Step: {{ $student->form_step }}</td>
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