<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Testimonials')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Testimonials</a></li>

    </ol>
@endsection

@section('content')
    <div class="row">
        <form action="{{ route('home.testimonialSubmit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="panel-body">
                        <div class="form-group">
                            <p>Image</p>
                            <input type="file" class="form-control input-focus" name="profile_image">
                        </div>

                        <div class="form-group">
                            <p>Name</p>
                            <input type="text" class="form-control input-focus" name="testimonials_name">
                        </div>

                        <div class="form-group">
                            <p>Message</p>
                            <textarea id="editor" name="testimonials_msg"></textarea>
                        </div>
                        <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h4>Testimonials List</h4>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($testimonials as $testimonialss)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td> <img src="{{ asset('storage/' . $testimonialss->image) }}" style="width: 100px;border-radius:10px"></td>
                                    <td> {{$testimonialss->name}}</td>
                                    <td>
                                        <span class="badge badge-{{ $testimonialss->status ? 'primary' : 'dark' }}">
                                            {{ $testimonialss->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td style="text-align: center">
                                        <a href="{{ route('home.deleteTestimonials', ['id' => $testimonialss->id]) }}">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    </form>
    </div>


@endsection
