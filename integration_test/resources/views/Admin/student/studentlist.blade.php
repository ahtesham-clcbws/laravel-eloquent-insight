<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', '')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Student List</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Student List</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-header">
                          <div class="row" style="margin-bottom: 15px">
                            <div class="col-lg-9"></div>
                            <div class="col-lg-2"><div class="badge badge-primary">Download as excel</div></div>
                            <div class="col-lg-1"><div class="badge badge-primary">Print</div></div>
                          </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Roll No.</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <td><span class="badge badge-primary">Sale</span></td> --}}
                                       @foreach($student as $students)
                                       <tr>
                                        <th scope="row">1</th>
                                        <td>{{ $students->name }}</td>
                                        <td>{{ $students->email }}</td>
                                        <td>{{ $students->mobile }}</td>
                                        <td>{{ $students->gender }}</td>
                                        <td>{{ $students->dob }}</td>
                                        <td class="color-primary">{{ $students->id }}</td>
                                        <td style="text-align:center">
                                            <ul>
                                                <li class="card-option drop-menu"><i class="ti-settings"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="link"></i>
                                                    <ul class="card-option-dropdown dropdown-menu">
                                                        <li><a href="{{ $students->id }}"><i class="ti-loop"></i> Edit</a></li>
                                                        <li><a href="#"><i class="ti-menu-alt"></i>Restrict</a></li>
                                                        <li><a href="#"><i class="ti-menu-alt"></i>Delete</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
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

@endsection
