<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Contact Info')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Contact Info</a></li>
    </ol>
@endsection



@section('content')
    <style>
        .faq_w1 h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
            font-size: 13px
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Contact Info</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            {{-- <form action="{{ route('home.contactSave') }}" method="POST"> --}}
                            @csrf

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Mobile</p>
                                    <input type="text" name="mobile" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Mobile 2</p>
                                    <input type="text" name="mobile2" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Email</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Facebook</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Twitter</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Instagram</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">WhatsApp1</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">WhatsApp2</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Twitter</p>
                                    <input type="email" name="email" class="form-control" id="">
                                </div>
                            </div>
                        </div>

                        </div>

                            <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>


                </div>
            </div>

        </div>

    </div>




@endsection
