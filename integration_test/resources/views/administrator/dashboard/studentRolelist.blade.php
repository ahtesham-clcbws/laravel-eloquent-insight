@extends('administrator.layouts.master')

@section('title')
Students Roll number generation
@endsection
@section('content')
<style>
    .select2-selection__choice {
        color: black !important;
    }
</style>
<script type="text/javascript" src="{{ asset('js/admineducationtypes.js') }}"></script>
<div class="row px-3">
    <div class="col-12 m-t-15">
        <h2 class="py-2">Students Roll number generation</h2>

        <div class="card rounded">
            <div class="card-header">
                <form action="" method="get" id="generateRollNumberForm">
                    @csrf
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-md-3">
                            <select name="district_id[]" multiple class="form-control form-select" id="district-ids" required>
                                <option value="">--Select City--</option>
                                @foreach($cities as $city)
                                <option value="{{$city->id}}" {{ (is_array(request()->district_id) && in_array($city->id, request()->district_id)) ? 'selected' : '' }}>
                                    {{$city->name}}
                                </option>
                                @endforeach
                            </select>
                            @error('district_id')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <!-- <label for="class">Scholarship Types</label> -->
                            <select class="form-control form-select" multiple id="education_name" onchange="scholarshipTypesChange(this.value)" name="scholarship_type[]">
                                @foreach($scholarshipTypes as $scholarship)
                                <option value="{{ $scholarship->id }}" {{ is_array(request()->scholarship_type) && in_array($scholarship->id, request()->scholarship_type) ? 'selected' : '' }}>
                                    {{ $scholarship->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <!-- <label for="class">Class</label> -->
                            <select class="form-control form-select" multiple id="board_name_id" name="class[]">
                                <option value=""> --Select Class-- </option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ is_array(request()->class) && in_array($class->id, array_map('intval', request()->class)) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <!-- <label class="form-label">Gender</label> -->
                            <select name="gender[]" class="form-control form-select" id="genders-filters" multiple>
                                <option value="">--Select Gender--</option>
                                <option value="Male" {{ (is_array(request()->gender) && in_array('Male', request()->gender)) ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ (is_array(request()->gender) && in_array('Female', request()->gender)) ? 'selected' : '' }}>Female</option>
                                <option value="Transgender" {{ (is_array(request()->gender) && in_array('Transgender', request()->gender)) ? 'selected' : '' }}>Tg</option>
                            </select>
                            @error('gender')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 ml-auto">
                            <!-- <label class="form-label">&nbsp;</label> <br> -->
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" onclick="window.location.href = '/administrator/studentRollList'" class="btn btn-danger ">Reset</button>
                                <button type="button" id="generateRollNumber" class="btn btn-warning">Gen. Roll No</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                                <td>{{$student->district?->name}}</td>
                                <td>{{ $student->latestStudentCode?->application_code ? $student->latestStudentCode?->application_code : 'N/A'}} </td>
                                <td>{{ !empty($student->latestStudentCode?->roll_no) ? $student->latestStudentCode?->roll_no :'N/A' }}</td>
                                <td>
                                    ₹&nbsp;{{ $student->studentPayment && count($student->studentPayment) && !empty($student->studentPayment[0]) && $student->studentPayment[0]->payment_amount ? $student->studentPayment[0]->payment_amount : '0'}}
                                    <br />
                                    {{ $student->latestStudentCode?->coupan_code ? $student->latestStudentCode?->coupan_code : '' }}
                                    {!! $student->latestStudentCode?->coupan_code ? '<br />'.($student->latestStudentCode?->corporate_name ? $student->latestStudentCode?->corporate_name : 'SQS Foundation, Kanpur') : '' !!}
                                </td>
                                <td>{{ $student->qualifications?->name }}</td>
                                <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                <td>{{ date('d-M-Y', strtotime($student->created_at)) }}</td>
                                <td style="text-align:center">
                                    <a href="{{ route('admin.student', $student->id) }}" class="btn btn-primary" style="text-decoration: none;"></i> View</a>
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
<!-- /#page-content-wrapper -->

<script>
    $(document).ready(function() {
        $('#generateRollNumber').on('click', function() {
            console.log('clicked generateRollNumber');
            var formData = new FormData($('#generateRollNumberForm')[0]);
            // console.log(Array.from(formData));

            if (!formData.get('district_id[]')) {
                return error('Please select city/district first')
            }

            $.ajax({
                url: "<?= route('admin.studentGenerateRollNo') ?>",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log('generateRollNumber response: ', response)
                    if (response.success) {
                        success(response.message);
                        location.reload();
                    } else {
                        error(response.message)
                    }
                },
                error: function(xhr) {
                    error(xhr.responseText)
                }
            });
        })
        $("#board_name_id").parent().find('textarea').on('click', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    });

    function scholarshipTypesChange(value) {
        var scholarshipCategory = $("#education_name").val();
        console.log("Value : ", scholarshipCategory);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post('/administrator/get_scholarship_category', {
            'ids': scholarshipCategory
        }, function(response) {
            console.log("Response:", response);
            if (response.status) {
                var data = response.data;
                if (data != null) {
                    $('#board_name_id').empty().append('<option value="">--Select Option--</option>');
                    $.each(data, function(index, st) {
                        var selected = (<?= $request->name ?? 'null' ?> == st.id) ? 'selected' : '';
                        $('#board_name_id').append('<option value="' + st.id + '" ' + selected + '>' + st.name + '</option>');
                    });
                }
            } else {
                error(response.message); // Ensure error function is defined
            }
        });
    }
</script>
@endsection('content')