<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Benefit')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Home</a></li>
        <li><a href="{{ route('course.category') }}">Benefit</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Add Benefit List</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('home.savebenefits') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <p>Benefit</p>
                                    <input type="text" name="benefit" id="" required class="form-control input-focus">
                                </div>
                                

                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <h4>Govt Website List</h4>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($benefit as $benefits)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>
                                    <p>{{ $benefits->benefits}}</p>
                                </td>

                                <td style="text-align: center">
                                    <a href="{{ route('home.deletebenefits', ['id' => $benefits->id]) }}">
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

@endsection
