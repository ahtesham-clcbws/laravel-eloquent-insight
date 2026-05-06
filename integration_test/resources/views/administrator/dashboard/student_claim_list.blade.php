@extends('administrator.layouts.master')

@section('title')
Claimed Student List
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
                    <h2>Claimed Student List</h2>
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
                                    <th scope="col">Appl No</th>
                                    <th scope="col">Roll No</th>
                                    <th scope="col">Scholarship Category</th>
                                    <th scope="col">Scholarship Opted For</th>
                                    <th scope="col">Submission Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <th scope="text-left">{{$loop->index+1}}</th>
                                    <td class="text-nowrap">
                                        {{ $student->name }}<br />
                                        <span>{{$student->gender}}</span>
                                    </td>
                                    <td>{{ $student->email }}<br />
                                        {{ $student->mobile }}
                                    </td>
                                    <td>{{ $student->latestStudentCode?->application_code ? $student->latestStudentCode?->application_code : 'N/A'}} </td>
                                    <td>{{ !empty($student->latestStudentCode?->roll_no) ? $student->latestStudentCode?->roll_no :'N/A' }}</td>
                                    <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                    <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                    <td>{{ date('d-M-Y', strtotime($student->studentClaimForm->created_at)) }}</td>

                                    <td style="text-align:center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.student', $student->id) }}" class="btn btn-sm btn-primary" title="View Profile">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.student.claim_form', $student->id) }}" class="btn btn-sm btn-success" title="View Claim Form">
                                                <i class="fa fa-file-text"></i> Claim Form
                                            </a>
                                        </div>
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
