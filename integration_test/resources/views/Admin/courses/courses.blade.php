<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Courses')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Courses</a></li>

    </ol>
@endsection

@section('content')
    <div class="row">
        <form action="{{ route('course.coursesubmit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-3">
                <div class="form-group">
                    <p>Notification File</p>
                    <input type="file" class="form-control input-focus" name="notification_file">
                </div>
            </div>



            <div class="col-lg-3">
                <div class="form-group">
                    <p>Notification</p>
                    <input type="text" name="notification" class="form-control input-focus"
                        placeholder="24 Sep 2021 - 26 Oct 2021">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Registration</p>
                    <input type="text" name="registration" class="form-control input-focus"
                        placeholder="24 Sep 2021 - 26 Oct 2021">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Exam Date</p>
                    <input type="text" name="exam_Date" class="form-control input-focus"
                        placeholder="28 Dec 2021 - 06 Jan 2022">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Exam Mode</p>
                    <input type="text" name="exam_mode" class="form-control input-focus"
                        placeholder="Written & Computer Based">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Exam Pattern</p>
                    <input type="text" name="exam_pattern" class="form-control input-focus"
                        placeholder="Pre Mains Interview">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Vacancies</p>
                    <input type="text" name="vacancies" class="form-control input-focus" placeholder="5500">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Pay Scale</p>
                    <input type="text" name="pay_scale" class="form-control input-focus" placeholder="I21,700 - I69,100">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Age Crieteria</p>
                    <input type="text" name="age_criteria" class="form-control input-focus"
                        placeholder="35 Years For GEN/ EWS 40 Years for SC/ ST 36 Years For OBC">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <p>Eligibility</p>
                    <input type="text" name="eligibility" class="form-control input-focus"
                        placeholder="Graduate / Graduation Final Year Graduation Appearing">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <p>Official Site</p>
                    <input type="text" name="official_site" class="form-control input-focus"
                        placeholder="https://sssc.nic.in/">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <p>Exam Details</p>
                    <input type="file" class="form-control input-focus" name="exam_details_file">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <p>Course Overview</p>
                    <textarea id="editor" name="overview"></textarea>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <p>Other Details</p>
                    <textarea id="editor1" name="otherdetails"></textarea>
                </div>
            </div>
    </div> <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
    </div>
    </div>
    </form>
    ''

    <!-- Initialize CKEditor -->
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#editor1'))
            .then(editor1 => {
                console.log(editor1);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
