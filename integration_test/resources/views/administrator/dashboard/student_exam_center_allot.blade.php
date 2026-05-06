@extends('administrator.layouts.master')
@section('title')
Student Exam Center Allotment
@endsection
@section('content')

<style>
    #district-ids,
    #education_name,
    #board_name_id,
    #genders-filters,
    #exam_center,
    /* #student_number, */
    #admitcard_before,
    #exam_mins {
        display: none;
    }
</style>
<style>
    .select2-selection__choice {
        color: black !important;
    }

    .selectAllCl .dt-column-order {
        display: none;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default m-t-15">
            <div class="panel-heading m-2 ">
                <h5>Student Exam Center Allotment</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="m-t-15">
            <div class="p-2">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="alotmentForm">
                            <form class="row" method="get" style="margin-bottom: 15px" id="filterForm">

                                <div class="col-md-3 col mb-3">
                                    <select name="district_id[]" multiple class="customSelect" id="district-ids">
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

                                <div class="col-md-3 col mb-3">
                                    <select class="customSelect" multiple id="education_name" name="scholarship[]">
                                        @foreach($scholarshipTypes as $scholarship)
                                        <option value="{{ $scholarship->id }}" {{ is_array(request()->scholarship) && in_array($scholarship->id, request()->scholarship) ? 'selected' : '' }}>
                                            {{ $scholarship->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 col mb-3">
                                    <select class="customSelect" multiple id="board_name_id" name="class[]">
                                        @foreach($preloadedClasses as $class)
                                        <option value="{{ $class->id }}" {{ is_array(request()->class) && in_array($class->id, request()->class) ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                        @endforeach
                                        @if(!empty(request()->class))
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2 col mb-3">
                                    <select name="gender[]" class="customSelect" id="genders-filters" multiple>
                                        <option value="Male" {{ (is_array(request()->gender) && in_array('Male', request()->gender)) ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ (is_array(request()->gender) && in_array('Female', request()->gender)) ? 'selected' : '' }}>Female</option>
                                        <option value="Transgender" {{ (is_array(request()->gender) && in_array('Transgender', request()->gender)) ? 'selected' : '' }}>Transgender</option>
                                    </select>
                                    @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 col mb-3">
                                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                                        <button type="submit" class="btn btn-primary "><i class="bi bi-funnel-fill"></i> Filter</button>
                                        <a href="/administrator/exam_center_allotment" class="btn btn-danger " type="button" style="text-decoration: none;"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form class="row" id="examCenterAllotmentForm" method="post">
                                @csrf
                                <div class="col-12 col-lg-6">
                                    <div class="row">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="form-label">Exam Center</label>
                                            <div>
                                                <select class="customSelect w-100" id="exam_center" name="exam_center">
                                                    <!-- <option value="">--Select Center--</option> -->
                                                    <option data-display="Select" value="">Select center</option>

                                                    @foreach($examCenters as $examCenter)
                                                    <option value="{{ $examCenter->id }}" {{ $examCenter->id == request()->exam_center ? 'selected' : '' }}>
                                                        {{ $examCenter->city?->name }} : {{ $examCenter->center_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label class="form-label" for="exam_date_time">Date/Time</label>
                                            <input class="form-control" id="flatpickrInstance" onchange="validateDateTime()" type="datetime-local" name="exam_date_time">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-2 mb-2">
                                            <label for="form-label">Duration</label>
                                            <div>
                                                <select class="customSelect w-100" id="exam_mins" name="exam_mins">
                                                    <option value=""></option>
                                                    @for ($i = 30; $i <= 240; $i +=15) <option value="{{ $i }}">
                                                        {{ $i }} mins
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="form-label">Admit Card</label>
                                            <div>
                                                <select class="customSelect w-100" id="admitcard_before" name="admitcard_before">
                                                    <option value=""></option>
                                                    @for ($i = 0; $i <= 7; $i++)
                                                        <option value="{{ $i }}">
                                                        {{ $i }} days
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="form-label">Max students</label>
                                            <div>
                                                <input class="w-100 form-control" type="number" id="student_number" name="student_number" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="form-label">&nbsp;</label>
                                            <button id="examCenterAllotments" type="submit" class="btn btn-primary btn-block">Allot center</button>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="form-label">&nbsp;</label><br />
                                            <div class="btn-group w-100" role="group" aria-label="Basic example">
                                                <button class="btn btn-success" type="button" id="updateAdmitCardStatusAll">Issue A cards</button>
                                                <button class="btn btn-danger" type="button" id="StopAdmitCardStatusAll">Stop A cards</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2 d-none">
                                    <label for="form-label">&nbsp;</label>
                                    <button id="allotCenterToAll" type="button" class="btn btn-danger btn-block">Allot Center to All</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card rounded">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($filters && count($filters)>0)
                            <div class="card-header d-flex gap-5 justify-content-between w-100 px-0">
                                <div class="d-flex flex-wrap gap-2 flex-grow-1">
                                    @foreach($filters as $key => $filter)
                                    @if(count($filter)>0)
                                    @foreach($filter as $filteredValue)
                                    <span class="badge text-bg-<?= $key == 'district' ? 'primary' : ($key == 'scholarship' ? 'secondary' : ($key == 'class' ? 'success' : 'danger')) ?> fs-6">
                                        {{ $filteredValue }}
                                    </span>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </div>
                                <div class="d-inline">
                                    <span class="badge text-bg-warning fs-6">Total Students:- {{ count($students) }}</span>
                                </div>
                            </div>
                            @endif
                            <table class="table display table-bordered datatablecll">
                                <thead>
                                    <tr>
                                        <th class="selectAllCl text-center">Issued AdmitCard <br> <input type="checkbox" id="selectAll"> </th>
                                        <th class="text-center">Sr. No</th>
                                        <th>Name(Gender) <br> DOB</th>
                                        <th>Email/Mobile</th>
                                        <th>Application Code</th>
                                        <th>Roll No</th>
                                        <th>Scholarship Category</th>
                                        <th>Scholarship Opted For</th>
                                        <th>City</th>
                                        <th>Exam Center</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <?php
                                        $studCode = $student->studentCode->sortBy('created_at')->last();
                                        ?>
                                        <td scope="row" class="text-center">
                                            @if($studCode?->examCenter)
                                            <input type="checkbox" class="rowCheckbox" data-studcode_id="{{$studCode->id}}" value="" {{$studCode?->issued_admitcard ? 'checked' : ''}}>
                                            @endif
                                        </td>
                                        <td scope="row">{{$loop->index + 1}}</td>
                                        <td>{{ $student->name }} ({{ substr($student->gender,0,1) }}) <br>{{ $student->dob ?Carbon\Carbon::parse($student->dob)->format('d-m-Y') : ''  }}</td>
                                        <td>{{ $student->email }} <br>
                                            {{ $student->mobile }}
                                        </td>
                                        <td>{{$studCode?->application_code ? $studCode?->application_code : 'NA'}}</td>
                                        <td>{{ $studCode?->roll_no }}</td>
                                        <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                        <td>
                                            @if(!empty($student->qualifications) && $student->qualifications?->name)
                                            <span style="color:red;">{{ $student->qualifications?->name }} </span>
                                            <br>
                                            @endif
                                            {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $student->district?->name }}</td>
                                        <td>{{$studCode?->examCenter?->center_name}}</td>

                                        <td>
                                            @if($studCode?->examCenter && $studCode?->issued_admitcard)
                                            <a href="#" class="btn btn-danger changeStatus" data-studcode_id="{{$studCode->id}}" data-status="0">Stop AdmitCard</a>
                                            @elseIf($studCode?->examCenter)
                                            <a href="#" class="btn btn-success changeStatus" data-studcode_id="{{$studCode->id}}" data-status="1">Issue AdmitCard</a>
                                            @endif
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

</div>

@endsection('content')

@push('custom-scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        let tomSelectCommonOptions = {
            closeAfterSelect: true,
            hidePlaceholder: true,
            maxOptions: null
        }
        let tomSelectPlugins = {
            remove_button: {
                title: 'Remove this item',
            }
        }
        let tomSelectOptions = {
            plugins: tomSelectPlugins,
            persist: false,
            ...tomSelectCommonOptions
        };

        let district_ids = new TomSelect('#district-ids', tomSelectOptions);
        district_ids.settings.placeholder = "Select city";
        district_ids.inputState();
        let citySelect = district_ids;

        let education_name = new TomSelect('#education_name', tomSelectOptions);
        education_name.settings.placeholder = "Select scholarship type";
        education_name.inputState();
        let scholarshipSelect = education_name;

        let board_name_id = new TomSelect('#board_name_id', tomSelectOptions);
        board_name_id.settings.placeholder = "Select class";
        board_name_id.inputState();
        let classSelect = board_name_id;

        let gender_filters = new TomSelect('#genders-filters', tomSelectOptions);
        gender_filters.settings.placeholder = "Select Gender";
        gender_filters.inputState();
        let genderSelect = gender_filters;

        let exam_center = new TomSelect('#exam_center', tomSelectCommonOptions);
        exam_center.settings.placeholder = "Select exam center";
        exam_center.inputState();
        let examCenterSelect = exam_center;

        // let student_number = new TomSelect('#student_number', {
        //     ...tomSelectCommonOptions,
        //     create: true
        // });
        // student_number.settings.placeholder = "Select students";
        // student_number.inputState();
        // let studentNumberSelect = student_number;

        let exam_mins = new TomSelect('#exam_mins', tomSelectCommonOptions);
        // exam_mins.settings.placeholder = "Select duration";
        // exam_mins.inputState();
        let examMinutesSelect = exam_mins;

        let admitcard_before = new TomSelect('#admitcard_before', tomSelectCommonOptions);
        // admitcard_before.settings.placeholder = "Select admin card option";
        // admitcard_before.inputState();
        let adminCardSelect = admitcard_before;

        $("#flatpickrInstance").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:s",
            altInput: true,
            altFormat: "G:i K - l - j M, Y",
            minDate: "today"
        });


        $(document).on('change', '#education_name', function() {
            let scholarshipCategory = $(this).val();
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
                        classSelect.clear();
                        classSelect.clearOptions();
                        $.each(data, function(index, st) {
                            console.log('scholarship options: ', st)
                            classSelect.addOption({
                                value: st.id,
                                text: st.name
                            }, user_created = false)
                        });
                        classSelect.refreshItems();
                    }
                } else {
                    error(response.message); // Ensure error function is defined
                }
            });
        })

        $('#selectAll').click(function() {
            $('.rowCheckbox').prop('checked', this.checked);
        });

        $('.rowCheckbox').change(function() {
            if ($('.rowCheckbox:checked').length == $('.rowCheckbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            updateAdmitCardStatus([$(this).data('studcode_id')], $(this).is(':checked') ? 1 : 0);
        });

        $('.changeStatus').click(function(e) {
            e.preventDefault();
            var studcodeId = $(this).data('studcode_id');
            var status = $(this).data('status');
            updateAdmitCardStatus([studcodeId], status);
        });

        $('#updateAdmitCardStatusAll').click(function(e) {
            e.preventDefault();
            if (confirm('Are you sure to Issue admit cards to the below students list of selected students?')) {
                var studcodeIds = [];
                $('.rowCheckbox:checked').each(function() {
                    studcodeIds.push($(this).data('studcode_id'));
                });
                if (studcodeIds.length === 0) {
                    error('Please select at least one student.');
                    return;
                }
                updateAdmitCardStatus(studcodeIds, 1);
            }
        });

        $('#StopAdmitCardStatusAll').click(function(e) {
            e.preventDefault();
            if (confirm('Are you sure to Stop admit cards to the below students list of un-selected students?')) {
                var studcodeIds = [];
                $('.rowCheckbox:checked').each(function() {
                    studcodeIds.push($(this).data('studcode_id'));
                });
                if (studcodeIds.length === 0) {
                    error('Please select at least one student.');
                    return;
                }
                updateAdmitCardStatus(studcodeIds, 0);
            }
        });

        function updateAdmitCardStatus(studcodeIds, status) {
            $.ajax({
                url: '{{ route("update.admitcard.status") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    studcode_ids: studcodeIds,
                    status: status
                },
                success: function(response) {
                    if (response.status) {
                        success(response.message);
                        location.reload();
                    }
                }
            });
        }


        $(document).on('submit', '#filterForm', function() {
            // var formData = new FormData(this);
            // console.log('formData: ', Array.from(formData));

            var isValid = true;

            // alert('click alotmentForm submit')
            var isValid = true;
            var errorMessage = "";
            console.log('$(#district-ids).val()', $('#district-ids').val())

            if ($('#district-ids').val().length == 0 || $('#district-ids').val() == '') {
                isValid = false;
                errorMessage += "Please select city.\n";
            }

            // If form is not valid, prevent submission
            if (!isValid) {
                alert(errorMessage);
                event.preventDefault();
                return false;
            }
        })
        $(document).on('submit', '#examCenterAllotmentForm', function() {
            event.preventDefault();
            var isValid = true;
            var errorMessage = [];

            if ($('#district-ids').val().length == 0 || $('#district-ids').val() == '') {
                isValid = false;
                errorMessage.push("Please select city.");
            }

            if (!$('#exam_center').val() || $('#exam_center').val() === "") {
                isValid = false;
                errorMessage.push("Please select an Exam Center.");
            }

            if (!$('#flatpickrInstance').val() || $('#flatpickrInstance').val() === "") {
                isValid = false;
                errorMessage.push("Please select the Date and Time.");
            }

            if (!$('#exam_mins').val() || $('#exam_mins').val() === "") {
                isValid = false;
                errorMessage.push("Please select teh exam duration.");
            }

            if (!$('#admitcard_before').val() || $('#admitcard_before').val() === "") {
                isValid = false;
                errorMessage.push("Please select When to show the admin card to students.");
            }

            // If form is not valid, prevent submission
            if (!isValid) {
                alert(errorMessage.join("\n"));
                return false;
            }

            var formData = new FormData(this);
            let filterFormData = new FormData($("#filterForm")[0]);
            for (var pair of filterFormData.entries()) {
                formData.append(pair[0], pair[1]);
            }
            console.log('formData: ', Array.from(formData));

            $.ajax({
                url: "<?= route('admin.studentExamCenterAllotment') ?>",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('studentExamCenterAllotment: ', response)
                    // return;
                    if (response.status) {
                        success(response.message);
                        location.reload();
                    } else {
                        alert(response.message)
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseText)
                }
            });


        })
    });
</script>

@endpush