<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Career Page')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .faq-rhs p {
            display: block !important;
        }

        .mainSectionCar {
            margin-top: 72px;
            background: #253a53;
            color: #fff;
        }

        .mainSectionCar a {
            color: #fff;
        }

        .gvtJobs {
            height: 27px;
        }

        /* responsive  */
        @media screen and (max-width: 600px) {
            .width {
                max-width: 100%;
                padding-bottom: 10px;
            }

            .row {
                display: block !important;
                text-align: center;
            }

            .centerContent {
                text-align: center;
            }

            .wave {
                max-width: 600px;
            }

            .exam-details {
                margin-top: 10px;
            }
        }
    </style>
    <div style="background:#e6f6fd">

        <div class="perpration-career-banner mainSectionCar">
            <div class="container text-center">
                <div class="row" style="padding-top:85px;padding-bottom:85px">
                    <div class="col-2 width">
                        <img class="img-thumbnail" src="{{ asset('home/course/' . $course?->course_logo) }}"
                            alt="Responsive image" width="115px" height="100px">
                    </div>
                    <div class="col-10 width centerContent" style="text-align: justify;">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div class="width gvtJobs">
                                <p style="font-size: small;max-width:100%">Union Govt Job</p>
                            </div>
                            <div class="width">
                                <h4 class="mb-0">{{ $course?->course_full_name }} ({{ $course?->title }})</h4>
                            </div>
                            <div class="width d-flex" style="font-size: small;max-width:100%">
                                <div class="div width">
                                    <i class="bi bi-megaphone"></i>
                                    <a class="" href="{{ asset('home/course/' . $course->notification_file_path) }}"
                                        target="_blank">Gazette Notification
                                        <i class="bi bi-download text-success"></i>
                                    </a>
                                </div>
                                <div class="div width ml-4">
                                    <i class="bi bi-book"></i>
                                    <a class="" href="{{ asset('home/course/' . $course->exam_details_file_path) }}"
                                        target="_blank">Course/Exam Details
                                        <i class="bi bi-download text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-3 width">
                        <span>Get 100% Scholarship For Preparation </span>
                        <div class="">
                            <a href="#">
                                <button class="btn btn-success btn-apply-now" type="button">Apply
                                    Now</button>
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="container py-5">
            <div class="mb-5">
                <div class="row">
                    <!-- FAQ RIGHT SIDE CONTENT-->
                    <div class="col-lg-7 col-md-12">
                        <h4>Course Overview</h4>
                        <div class="faq-rhs career-overview-content" style="border-radius: 0;">
                            {!! $course?->overview !!} {!! $course?->otherdetails !!}
                        </div>
                    </div>

                    <!-- FAQ LEFT SIDE IMG-->
                    <div class="col-lg-5 col-md-12">
                        <div class="exam-details">
                            <h4>Exam Details</h4>
                            <div class="career-overview-content list-group-horizontal pt-3">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="border-top:0 !important;color: black;font-weight: bold;">
                                                Notification:
                                            </td>

                                            <td style=" border-top:0 !important;color: black;font-weight: bold;">
                                                {{ $course?->notification }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Registration:
                                            </td>

                                            <td>
                                                {{ $course?->registration }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Exam Date:
                                            </td>

                                            <td>
                                                {{ $course?->exam_Date }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Exam Mode:
                                            </td>

                                            <td>
                                                {{ $course?->exam_mode }}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Exam Pattern:
                                            </td>

                                            <td>
                                                {{ $course?->exam_pattern }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Vacancy:
                                            </td>

                                            <td>
                                                {{ $course?->vacancies }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Pay Scale:
                                            </td>

                                            <td>
                                                {{ $course?->pay_scale }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Age Criteria:
                                            </td>

                                            <td>
                                                {{ $course?->age_criteria }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight: bold;">
                                                Eligibility:
                                            </td>

                                            <td>
                                                {{ $course?->eligibility }}
                                            </td>
                                        </tr>
                                        <tr>
                                            @if ($course?->prospectus)
                                                <td style="color: black;font-weight: bold;">
                                                    E-Prospectus:
                                                </td>
                                                <td>
                                                    <div>
                                                        @if (in_array(pathinfo($course->prospectus, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']))
                                                            <a href="{{ asset('home/course/' . $course->prospectus) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('home/course/' . $course->prospectus) }}"
                                                                    alt="Prospectus Image"
                                                                    style="max-width: 50px; max-height: 40px;">
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('home/course/' . $course->prospectus) }}"
                                                                target="_blank">
                                                                Download &nbsp;<i class="fa fa-download ml-2"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>

                                        @if ($course && $course->official_site)
                                            <tr>
                                                <td style="color: black;font-weight: bold;">
                                                    Official site:
                                                </td>
                                                <td class="text-break">
                                                    <a href="{{ $course->official_site }}" target="_blank">
                                                        {{ $course->official_site }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!-- FAQ RIGHT SIDE CONTENT END-->
                </div>
            </div>
        </div>

    </div>
    <style>
        .exam-details>.career-overview-content * {
            font-size: 97% !important;
        }
    </style>
@endsection
