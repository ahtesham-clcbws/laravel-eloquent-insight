@extends('student.layouts.master')

@section('content')

<!-- Form step start -->
@include('student.layouts.form_arrow_step')
<!-- Form step end -->

<!-- InstanceBeginEditable name="Content Area" -->
@if($student->is_final_submitted) <style>
    input,
    select {
        pointer-events: none;
    }
</style>@endif

<div class="container-fluid pagecontentbody mt-4">
    <div class="tab-content">
        <div class="pagebody px-4">
            <form method="post" action="{{ route(name: 'students.additionalDetailStore') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="col-md-7 col">
                        <label for="is_gov_exam_participated">Did you participate in any Govt/ Competitive Exam(s)? <span class="text-danger">*</span>: </label>
                        <input type="radio" name="is_gov_exam_participated" id="is_gov_exam_participated_yes" value="yes" required style="width: 15px; height: 15px" {{ $student->is_gov_exam_participated === 'yes' ? 'checked' : '' }}>
                        <label for="is_gov_exam_participated_yes">Yes</label>

                        <input type="radio" name="is_gov_exam_participated" id="is_gov_exam_participated_no" value="no" checked required style="width: 15px; height: 15px" {{ $student->is_gov_exam_participated === 'no' ? 'checked' : '' }}>
                        <label for="is_gov_exam_participated_no">No</label>
                        @error('is_gov_exam_participated')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="row is_gov_exam_participated_input" style="display: none;">
                        <div class="col">
                            <label for="govt_exams_1">Exam 1. <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="text" class="form-control" id="govt_exams_1" name="govt_exams_1" placeholder="Enter exam name" value="{{$student->govt_exams_1}}">
                                </div>
                                <div class="input-group-append">
                                    <select id="exam_one_year" name="exam_one_year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}" {{$student->exam_one_year == $year ? 'selected' : ''}}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" name="exam_one_result" class="form-control text_on_side_input" id="exam_one_result" placeholder="Result." value="{{$student->exam_one_result}}">
                                </div>
                            </div>
                            <i toggle="#password-field" class="fa fa-fw  field-icon text_on_input">1.</i>
                            @error('govt_exams_1')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                            @error('exam_one_year')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('exam_one_result')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-md-9 col">
                        <label for="govt_exams"> Did you apply career without barrier
                            scholarship exam? <span class="text-danger">* &nbsp;</span></label>

                        <input type="radio" name="is_apply_career_without_barrier" value="yes" required style="width: 15px;height:15px" {{ $student->is_apply_career_without_barrier === 'yes' ? 'checked' : '' }}>
                        Yes &nbsp;
                        <input type="radio" name="is_apply_career_without_barrier" value="no" checked required style="width: 15px;height:15px" {{ $student->is_apply_career_without_barrier === 'no' ? 'checked' : '' }}>
                        No
                        @error('is_apply_career_without_barrier')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="row career_without_barrier" style="display: none;">
                        <div class="col">
                            <label for="career_exams_1">Exam 1. <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <select name="career_exams_1" class="form-control text_on_side_input" id="career_exams_1" placeholder="Enter name exam.">
                                        <option value="">Select Scholarship category</option>
                                        @foreach(App\Models\EducationType::all() as $education)
                                        <option {{$student->career_exams_1 == $education->name ? 'selected' : ''}} value="{{$education->name}}">{{$education->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <select id="year" name="year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}" {{$student->year == $year ? 'selected' : ''}}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" name="career_one_result" class="form-control text_on_side_input" id="career_one_result" placeholder="Result." value="{{$student->career_one_result}}">
                                </div>
                            </div>
                            <i toggle="#password-field" class="fa fa-fw  field-icon text_on_input">1.</i>
                            @error('career_exams_1')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                            @error('year')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('career_one_result')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="career_exams_2">Exam 2. </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <select name="career_exams_2" class="form-control text_on_side_input" id="career_exams_2" placeholder="Enter name exam." value="{{$student->career_exams_2}}">
                                    <option value="">Select Scholarship category</option>
                                        @foreach(App\Models\EducationType::all() as $education)
                                        <option {{$student->career_exams_2 == $education->name ? 'selected' : ''}} value="{{$education->name}}">{{$education->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('career_exams_2')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                    <select id="test_center_a" name="career_two_year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}" {{$student->career_two_year == $year ? 'selected' : ''}}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('career_two_year')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" name="career_two_result" class="form-control text_on_side_input" id="career_two_result" placeholder="Result." value="{{$student->career_two_result}}">
                                    @error('career_two_result')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                </div>
                            </div>
                            <i toggle="#password-field" class="fa fa-fw  field-icon text_on_input">2.</i>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="family_income">Family Income:</label>
                            <br>
                            <select id="family_income" name="family_income" class="form-control form-select" value="{{$student->family_income}}">
                                <option value="">--Select Family Income--</option>
                                <option value="1" {{$student->family_income == '1' ? 'selected' : ''}}>Less than 1L</option>
                                <option value="2" {{$student->family_income == '2' ? 'selected' : ''}}>1L to 2L</option>
                                <option value="3" {{$student->family_income == '3' ? 'selected' : ''}}>2L to 3L</option>
                                <option value="4" {{$student->family_income == '4' ? 'selected' : ''}}>3L to 5L</option>
                                <option value="5" {{$student->family_income == '5' ? 'selected' : ''}}>5L and above</option>
                            </select>
                            @error('family_income')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="mother_occupation">Father/Mother’s Occupation:</label>
                            <div class="input-group">
                                <div class="input-group-append" style="width: 50%;">
                                    <input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="Father Occupation" value="{{$student->father_occupation}}">
                                </div>
                                <div class="input-group-append" style="width: 50%;">
                                    <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="Mother Occupation" value="{{$student->mother_occupation}}">
                                </div>
                            </div>
                            @error('mother_occupation')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                            @error('father_occupation')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Personal Details -->
                <!-- Educational Qualification -->
                <!-- Additional Optional Information -->

                <div class="row custom-row-patty">
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="photo">Attach Photograph (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="photograph" class="custom-file-input" value="{{$student->photograph}}" id="photo" onchange="validateImage(this,'imagepdf')" {{$student->photograph ? '' : 'required'}}>
                                    <label class="custom-file-label" for="photo">Choose file</label>
                                    @error('photograph')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                @if($student->photograph)
                                <div class="input-group-append photograph-image" style="margin-left: 10px;margin-top: -2rem;">
                                    <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('upload/student/'.$student->photograph) }}" style="background: none;border: none;">
                                        <img src="{{ asset('upload/student/'.$student->photograph) }}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10re;">
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="signature">Attach Signature (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file custom-file-signature">
                                    <input type="file" name="signature" value="{{$student->signature}}" class="custom-file-input" id="signature" onchange="validateImage(this)" {{$student->signature ? '' : 'required'}}>
                                    <label class="custom-file-label" for="signature">Choose file</label>
                                    @error('signature')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                @if($student->signature)
                                <div class="input-group-append student-signature-img-view" style="margin-left: 10px;margin-top: -2rem;">
                                    <a style="background: none;border: none;" target="_blank" class="btn btn-outline-secondary btn-success" href="{{  asset('upload/student/'.$student->signature)}}">
                                        <img src="{{ asset('upload/student/'.$student->signature) }}" alt="Signature" style="width: 7rem;height: 8rem;margin-top: -10re;">
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check mb-3 mt-2">

                            <input type="hidden" name="terms_conditions" value="0"> <!-- Hidden input for unchecked value -->
                            <input type="checkbox" name="terms_conditions" class="form-check-input" id="terms_conditions" value="1" {{ $student->terms_conditions ? 'checked' : '' }} required> <!-- Checked value -->
                            <label class="form-check-label" for="terms_conditions"><span class="text-danger">*</span> <b>I agree for Career without Barrier’s <a style="text-decoration: underline;" href="/p/student-terms-and-conditions" target="_blank"> Terms & Conditions </a></b> &nbsp;&nbsp; &check;</label>

                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">

                    <div class="col-md-3 mt-2 col d-grid">
                        <button type="submit" class="btn btn-theme submitform">Save and Review</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('input[name="is_gov_exam_participated"]').on('change', function() {
                var checkval = $(this).val();

                if (checkval == 'yes') {
                    $('.is_gov_exam_participated_input').show();
                    $('.is_gov_exam_participated_input input:first').attr('required', true);
                    $('.is_gov_exam_participated_input select:first').attr('required', true);
                    $('.is_gov_exam_participated_input input:eq(1)').attr('required', true);
                } else {
                    $('.is_gov_exam_participated_input').hide();
                    $('.is_gov_exam_participated_input input:first').attr('required', false);
                    $('.is_gov_exam_participated_input select:first').attr('required', false);
                    $('.is_gov_exam_participated_input input:eq(1)').attr('required', false);
                }
            })

            $('input[name="is_gov_exam_participated"]:checked').trigger('change');

            $('input[name="is_apply_career_without_barrier"]').on('change', function() {
                var checkval = $(this).val();
                if (checkval == 'yes') {
                    $('.career_without_barrier').show();
                    $('.career_without_barrier input:first').attr('required', false);

                } else {
                    $('.career_without_barrier').hide();
                    $('.career_without_barrier input').attr('required', false);
                }
            })
            $('input[name="is_apply_career_without_barrier"]:checked').trigger('change');

        });

        function updateImage(type) {
            var formData = new FormData();
            if (type == 'photo') {
                var files = $('#photo')[0].files;
                if (files.length > 0) {
                    formData.append('photograph', files[0]);
                }
            }
            if (type == 'signature') {
                var files = $('#signature')[0].files;
                if (files.length > 0) {
                    formData.append('signature', files[0]);
                }
            }

            $.ajax({
                url: '{{ route("upload.photo") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    if (type == 'photo') {
                        if ($('.photograph-image').length > 0) {
                            $('.photograph-image').html(`
                            <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('') }}${response.photo_path}" style="background: none;border: none;">
                                <img src="{{ asset('') }}${response.photo_path}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10px;">
                            </a>
                        `);
                        } else {
                            var imagePreviewHtml = `
                            <div class="input-group-append photograph-image" style="margin-left: 10px;margin-top: -2rem;">
                                <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('') }}${response.photo_path}" style="background: none;border: none;">
                                    <img src="{{ asset('') }}${response.photo_path}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10px;">
                                </a>
                            </div>
                        `;
                            $('.custom-file').after(imagePreviewHtml);
                        }
                    }
                    if (type == 'signature') {

                        if ($('.student-signature-img-view').length > 0) {
                            $('.student-signature-img-view').html(`
                            <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('') }}${response.photo_path}" style="background: none;border: none;">
                                <img src="{{ asset('') }}${response.photo_path}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10px;">
                            </a>
                        `);
                        } else {
                            var imagePreviewHtml = `
                            <div class="input-group-append student-signature-img-view" style="margin-left: 10px;margin-top: -2rem;">
                                <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('') }}${response.photo_path}" style="background: none;border: none;">
                                    <img src="{{ asset('') }}${response.photo_path}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10px;">
                                </a>
                            </div>
                        `;
                            $('.custom-file-signature').after(imagePreviewHtml);
                        }
                    }

                },
                error: function(xhr, status) {
                    error(xhr.responseText);
                }
            });

        }
    </script>

    @endsection('content')