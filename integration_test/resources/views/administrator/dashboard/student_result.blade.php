@extends('administrator.layouts.master')
@section('title')
Student Result
@endsection
@section('content')

<style>
    .select2-selection__choice {
        color: black !important;
    }

    .selectAllCl .dt-column-order {
        display: none;

    }
</style>
<?php

use App\Models\StudentPaperExported;
?>
<div class="row px-3">

    <div class="col-lg-12">
        <div class="row justify-content-space-between py-3">
            <div class="col-md-6 col">
                <h2>Student Result and Claim Form</h2>
            </div>
            <div class="col-md-6 col text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import Result
                </button>
                <a href="{{ route('admin.refreshStudentRank') }}" class="btn btn-warning">
                    Recalculate Rank
                </a>
                @if(auth()->user()->roles == 'admin')
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#claimLoginForm">
                    Generate Scholarship Claims
                </button>
                @endif
            </div>
        </div>
        <div class="panel panel-default m-t-15">


            <div class="panel-body">
                <div class="card alert">
                    <!-- <div class="card-header"> -->
                    <!-- <form action="#" method="post">
              @csrf
              <div class="row" style="margin-bottom: 15px">
                <input type="hidden" value="filterform" name="filterform">

                <div class="col-md-3 col mb-3">
                  <label for="class">Scholarship Types<span class="text-danger">*</span></label>
                  <select class="form-control" id="education_name" name="education_name" required>
                    <option value="">--Select Scholarship--</option>
                    @foreach($scholarshipTypes as $scholarship)
                    <option value="{{ $scholarship->id }}" {{ request()->education_name== $scholarship->id ? 'selected' : '' }}>
                      {{ $scholarship->name }}
                    </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-2 col mb-3">
                  <label class="form-label">Gender</label>
                  <select name="gender" class="form-control" id="genders-filters">
                    <option value="">--Select Gender--</option>
                    <option value="Male" {{ request()->gender == 'Male'  ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request()->gender == 'Female'  ? 'selected' : '' }}>Female</option>
                    <option value="Transgender" {{ request()->gender == 'Transgender'  ? 'selected' : '' }}>Transgender</option>
                  </select>
                  @error('gender')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-2 col mb-3">
                  <label class="form-label">Number of Student<span class="text-danger">*</span></label>
                  <input class="form-control" placeholder="Enter Number Of Student" type="text" pattern="[0-9]+" name="limit" value="{{request()->limit}}" required>
                </div>

                <div class="col-md-2 col mb-3">
                  <label class="form-label">Percentage<span class="text-danger">*</span></label>
                  <input class="form-control" placeholder="Enter Percentage" type="text" pattern="[0-9]+" name="percentage" value="{{request()->percentage}}" required>
                </div>
                <div class="col-md-2 col mb-3 ml-auto">
                  <label class="form-label">&nbsp;</label> <br>
                  <button type="submit" class="btn btn-primary ">Filter</button> &nbsp;&nbsp;
                  <a href="/administrator/student_result" class="btn btn-danger " style="text-decoration: none;">Reset</a>
                </div>
              </div>
            </form> -->
                    <!-- </div> -->
                    <form action="{{route('admin.student.result.allow_claim')}}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered datatablecll">
                                    <thead>
                                        <tr>
                                            <th class="selectAllCl text-center" style="padding-right: 10px;"><input type="checkbox" id="selectAll"> </th>
                                            <th>Student Name/ Gender</th>
                                            <th>Email/ Mobile/ App_Code</th>

                                            <th>Scholarship Category</th>
                                            <th>Scholarship Opted For</th>
                                            <th>City</th>
                                            <th>All India Rank</th>
                                            <th>Percentage</th>
                                            <th>Page</th>
                                            <th>Claim form</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($students as $student)
                                        <?php $studCode = $student->latestStudentCode; ?>
                                        <tr>

                                            <td>
                                                <input type="hidden" name="student_id[]" value="{{$student->id}}">
                                                <input title="Please Tick" type="checkbox" data-row-id="{{$student->id}}" name="allow_to_claim_scholarship[]" class="rowCheckbox" id="allow_to_claim_scholarship" value="1" <?= $studCode?->allow_to_claim_scholarship ? 'checked' : '' ?> required>
                                            </td>
                                            <td>{{ $student->name }}<br>
                                                ({{ genderShort($student->gender) }})
                                            </td>
                                            <td>{{ $student->email }} <br>
                                                {{ $student->mobile }} <br>
                                                {{$studCode?->application_code ? $studCode?->application_code : 'NA'}}
                                            </td>
                                            <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }} <br> {{ $student->qualifications?->name }} </td>
                                            <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                            <td>{{ $student->district?->name }}</td>
                                            <td style="text-align:center">{{$studCode?->rank ?? 'N/A'}}</td>
                                            <td style="text-align:center">{{$studCode?->percentage}} %
                                                {{-- {{ json_encode($studCode) }} --}}
                                            </td>
                                            <td>

                                                <a href="{{ route('admin.student.result.detail', $student->id) }}" class="btn btn-primary" style="text-decoration: none; text-align:center"> Result</a>
                                                <br>
                                                <a href="{{ route('admin.student.adminCard', $student->id) }}" class="btn btn-primary" style="text-decoration: none; width: 104px; margin-top: 10px;"> Admit Card</a>
                                            </td>
                                            <td style="text-align:center">
                                                <!-- @if($student->studentPaperDetails?->isNotEmpty()) -->

                                                <!-- @endif -->
                                                @if($student->scholarship_claim_generation_id && $student->studentClaimForm)
                                                <a href="{{ route('admin.student.claim_form', $student->id) }}" class="btn btn-warning" style="text-decoration: none; width: 94px; margin-bottom: 10px;"> Claim Form</a>
                                                @else
                                                @if($student->scholarship_claim_generation_id)
                                                <span class="badge badge-info">Pending</span>
                                                @else
                                                <span class="badge badge-danger">Not Eligible</span>                                                
                                                @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $('#selectAll').click(function() {
        $('.rowCheckbox').each(function() {
            $(this).prop('checked', $('#selectAll').prop('checked'));

            var rowId = $(this).data('row-id');
            if ($(this).is(':checked')) {
                $('.allow_claim_btn' + rowId).show();
            } else {
                $('.allow_claim_btn' + rowId).hide();
            }
        });
    });
</script>

@if(auth()->user()->roles == 'admin')
<div class="modal fade" id="claimLoginForm" tabindex="-1" aria-labelledby="claimLoginFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="post" action="<?= route('admin.generateScholarshipEligibleStudents') ?>">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="claimLoginFormLabel">Generate Scholarship Claims</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex bd-highlight">

                    <div class="p-2 flex-grow-1">
                        <div class="input-group">
                            <input type="number" name="min_eligibility_percentage" required min="1" class="form-control" placeholder="Min Eligibility Percent" aria-label="Min Eligibility Percent" aria-describedby="min_eligibility_percentage">
                            <div class="input-group-append">
                                <span class="input-group-text" id="min_eligibility_percentage">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-2 flex-grow-1">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="max_students">Top</span>
                            </div>
                            <input type="number" name="max_students" required min="1" class="form-control" placeholder="Maximum students" aria-label="Maximum students" aria-describedby="max_students">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Generate</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Student paper import start modal -->

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('student_papers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col">
                            <div class="form-group">
                                <input required type="file" id="fileInputSection2" class="form-control input-focus" onchange="validateExcel(this)" name="importFile">
                                @error('picture')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                                <img class="imagePreviewSection2" src="#" alt="Image Preview" style="display: none;width:100px">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload File</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Student paper import end modal -->

@endsection('content')

<script>
    // when click on calculate
    // check is results are uploaded
</script>