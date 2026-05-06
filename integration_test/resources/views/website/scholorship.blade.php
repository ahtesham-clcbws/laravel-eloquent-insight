<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
<div class="perpration-page-banner common-banner" style="margin-top:72px;">
    <div class="container text-center">
        <h2 style="font-size:32px">Scholarship Form</h2>
    </div>
</div>

<style>
    .formPadding {
        margin-top: 100px
    }
</style>

<div style="margin-top: 350px">
    <div class="container">
        <form action="{{ route('home.scholorship_insert') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="formPadding">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div>
                    <div class="contact__msg">Thank you.</div>

                    <div class="form-group">
                        <input type="text" name="name" placeholder="Applicant’s Name" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="fathers_name" placeholder="Father’s/ Mother’s Name"
                            class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <input type="date" name="dob" id="dob" placeholder="DOB" class="form-control"
                                required>
                        </div>

                        <div class="col-lg-6">
                            <input type="radio" name="gender" id="gender" value="male" required
                                style="width: 15px;height:15px">
                            Male &nbsp;
                            <input type="radio" name="gender" id="gender" value="female" required
                                style="width: 15px;height:15px">
                            FeMale
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Image</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label for="">Sign</label>
                            <input type="file" name="sign" id="sign" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Person with Disability</label>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="radio" name="disability" id="disability" value="yes" required
                                    style="width: 15px;height:15px"> Yes &nbsp;
                            </div>

                            <div class="col-lg-6">
                                <input type="radio" name="disability" id="disability" value="no" required
                                    style="width: 15px;height:15px"> No
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <input type="address" name="address" id="address" placeholder="Address" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="city">
                            <option value="">Select City</option>
                            <option value="Patna">Patna</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <Select class="form-control" name="qualification">
                            <option value="">Qualifications</option>
                            <option value="Intermediate">Intermediate</option>
                        </Select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">You want to Participate in Exams</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <select class="form-control" name="participate_exam">
                                    <option value="">Select Exam</option>
                                    @foreach ($scholarshipExams as $scholarshipExam)
                                    <option value="{{ $scholarshipExam->id }}">{{ $scholarshipExam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Choice of test centre</label>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <select class="form-control" name="center1">
                                    <option value="">Centre</option>
                                    @foreach ($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->center_name }}
                                        -{{ $center->add_district }} {{ $center->add_state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <select class="form-control" name="center2">
                                    <option value="">Centre</option>
                                    @foreach ($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->center_name }}
                                        -{{ $center->add_district }} {{ $center->add_state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <select class="form-control" name="center3">
                                    <option value="">Centre</option>
                                    @foreach ($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->center_name }}
                                        -{{ $center->add_district }} {{ $center->add_state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Did you participate in any Govt/ Competitive Exam(s)</label>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <select class="form-control" name="exam1">
                                    <option value="Exam 1">Exam 1</option>
                                    @foreach($courseList as $courseLists)
                                    <option value="{{ $courseLists->id }}">{{ $courseLists->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <select class="form-control" name="exam2">
                                    <option value="Exam 1">Exam 1</option>
                                    @foreach($courseList as $courseLists)
                                    <option value="{{ $courseLists->id }}">{{ $courseLists->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <select class="form-control" name="exam3">
                                    <option value="exam 1">Exam 1</option>
                                    @foreach($courseList as $courseLists)
                                    <option value="{{ $courseLists->id }}">{{ $courseLists->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="" style="font-weight: bold">If Previously Apply For This
                            Scholarship</label>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select id="" class="form-control" name="year">
                                        <option value="">Year</option>
                                        <option value="2019">2019</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="roll_no" class="form-control" placeholder="Roll No">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-control" name="family_income">
                                        <option value="">Family Income</option>
                                        <option value="1500000">15000000</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-control" name="occupation">
                                        <option value="">Guardian Occupation</option>
                                        <option value="Govt">Govt</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" style="width: 20px;height:20px" name="privacy_policy" required> &nbsp;
                        I agree for career without barrier’s Terms & Conditions
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-dark" name="submit" value="Submit">
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
</div>
@endsection