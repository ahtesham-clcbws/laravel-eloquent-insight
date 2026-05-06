@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Course List')

@section('breadcrumb')
<ol class="breadcrumb text-right">
    <li><a href="{{ route('course.category') }}">Classes</a></li>
    <li><a href="{{ route('course.subcategory') }}"> Courses</a></li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card alert">
            <div class="card-header">
                <h4>Course List </h4>
                <div class="card-header-right-icon">
                    {{-- <ul>
                        <li class="card-close" data-dismiss="alert"><i class="ti-close"></i></li>
                        <li class="card-option drop-menu"><i class="ti-settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="link"></i>
                            <ul class="card-option-dropdown dropdown-menu">
                                <li><a href="#"><i class="ti-loop"></i> Update data</a></li>
                                <li><a href="#"><i class="ti-menu-alt"></i> Detail log</a></li>
                                <li><a href="#"><i class="ti-pulse"></i> Statistics</a></li>
                                <li><a href="#"><i class="ti-power-off"></i> Clear ist</a></li>
                            </ul>
                        </li>
                        <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                    </ul> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Exam Date</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($course as $courses)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{!! $courses->otherdetails !!}</td>
                                    <td>{{ $courses->exam_Date}}</td>
                                    <td>{{ $courses->exam_Date}}</td>
                                    <td class="color-primary" style="text-align: center"><span class="fa fa-edit"></span> | <span class="fa fa-trash"></span></td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
