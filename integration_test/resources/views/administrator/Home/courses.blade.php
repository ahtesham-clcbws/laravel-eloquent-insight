@extends('administrator.layouts.master')

@section('title')
Preparation Course Add
@endsection

@section('content')

<?php

use App\Models\EducationType;

$educationType = EducationType::where('is_featured', 1)->get();

?>

<style>

</style>
<div class="row py-5 pl-3 pr-3">
    <div class="container card p-0">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 text-end"> <a href="{{route('admin.home.courseList')}}" class="btn btn-primary">
                        << Back Course List</a>
                </div>
            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('admin.home.course') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" name="id" value="{{$course->id ?? ''}}" autocomplete="off">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Select Scholarship Category <span class="text-danger">*</span></p>
                            <select name="scholarship_category" class="form-control input-focus">
                                <option value=''>--Select Scholarship Category--</option>
                                @foreach($educationType as $edu)
                                <option value="{{$edu->id}}" @selected($edu->id == ($course->scholarship_category ?? ''))>{{$edu->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Course Short Name</p>
                            <input type="text" value="{{old('course_name', $course->title ?? '')}}" name="course_name" class="form-control input-focus" placeholder="Enter Course Name: PCS-PRE">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Course Full Name</p>
                            <input type="text" value="{{old('course_full_name', $course->course_full_name ?? '')}}" name="course_full_name" class="form-control input-focus" placeholder="Enter Course Name:Preliminary & Mains">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Course Logo</p>
                            <input type="file" class="form-control input-focus" onchange="validateImage(this)" name="course_logo">
                            @if($course->course_logo)
                                <small class="text-muted">Current: <a href="{{ asset('home/course/' . $course->course_logo) }}" target="_blank">{{ $course->course_logo }}</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p> featured image</p>
                            <input type="file" class="form-control input-focus" onchange="validateImage(this)" name="featured_image">
                            @if($course->featured_image)
                                <small class="text-muted">Current: <a href="{{ asset('home/course/' . $course->featured_image) }}" target="_blank">{{ $course->featured_image }}</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Notification</p>
                            <input type="text" value="{{old('notification', $course->notification ?? '')}}" name="notification" class="form-control input-focus" placeholder="Enter Notification: 21 may 2024 - 26 jul 2024 ">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Registration</p>
                            <input type="text" value="{{old('registration', $course->registration ?? '')}}" name="registration" class="form-control input-focus" placeholder="Registration: 21 may 2024 - 26 jul 2024">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Exam Date</p>
                            <input type="text" value="{{old('exam_Date', $course->exam_Date ?? '')}}" name="exam_Date" class="form-control input-focus" placeholder="Exam Date: 21 Aug 2024 - 26 Aug 2024">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Exam Mode</p>
                            <input type="text" value="{{old('exam_mode', $course->exam_mode ?? '')}}" name="exam_mode" class="form-control input-focus" placeholder="Written & Computer Based">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Exam Pattern</p>
                            <input type="text" value="{{old('exam_pattern', $course->exam_pattern ?? '')}}" name="exam_pattern" class="form-control input-focus" placeholder="Pre Mains Interview">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Vacancies</p>
                            <input type="text" value="{{old('vacancies', $course->vacancies ?? '')}}" name="vacancies" class="form-control input-focus" placeholder="5500">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Pay Scale</p>
                            <input type="text" value="{{old('pay_scale', $course->pay_scale ?? '')}}" name="pay_scale" class="form-control input-focus" placeholder="I21,700 - I69,100">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Age Crieteria</p>
                            <input type="text" value="{{old('age_criteria', $course->age_criteria ?? '')}}" name="age_criteria" class="form-control input-focus" placeholder="35 Years For GEN/ EWS 40 Years for SC/ ST 36 Years For OBC">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Eligibility</p>
                            <input type="text" value="{{old('eligibility', $course->eligibility ?? '')}}" name="eligibility" class="form-control input-focus" placeholder="Graduate / Graduation Final Year Graduation Appearing">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Official Site</p>
                            <input type="text" value="{{old('official_site', $course->official_site ?? '')}}" name="official_site" class="form-control input-focus" placeholder="	https://ppntestsite.com/career">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Notification File</p>
                            <input type="file" onchange="validateImage(this,'imagepdf')" class="form-control input-focus" name="notification_file">
                            @if($course->notification_file_path)
                                <small class="text-muted">Current: <a href="{{ asset('home/course/' . $course->notification_file_path) }}" target="_blank">{{ $course->notification_file_path }}</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>Exam Details</p>
                            <input type="file" onchange="validateImage(this,'imagepdf')" class="form-control input-focus" name="exam_details_file">
                            @if($course->exam_details_file_path)
                                <small class="text-muted">Current: <a href="{{ asset('home/course/' . $course->exam_details_file_path) }}" target="_blank">{{ $course->exam_details_file_path }}</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <p>E-Prospectus</p>
                            <input type="file" onchange="validateImage(this,'imagepdf')" class="form-control input-focus" name="prospectus">
                            @if($course->prospectus)
                                <small class="text-muted">Current: <a href="{{ asset('home/course/' . $course->prospectus) }}" target="_blank">{{ $course->prospectus }}</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <p>Course Overview</p>
                                <textarea class="ckeditor" id="editor" name="overview">{{old('overview', $course->overview ?? '')}}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <p>Other Details</p>
                                <textarea class="ckeditor" id="editor1" name="otherdetails">{{old('otherdetails', $course->otherdetails ?? '')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
</form>
</div>
</div>
<script>
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    });
</script>

@endsection