<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Result')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Home</a></li>
        <li><a href="{{ route('course.category') }}">Result</a></li>
    </ol>
@endsection

@section('content')
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="category">Select Class</label>
                <select class="form-control" name="category" required>
                    <option value="">Select Class/Category</option>
                    @foreach ($category as $categories)
                        <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="table table-responsive">
                <thead>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Roll No.</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($student as $students)
                        <tr>
                            <td>{{ loop->iteration }}</td>
                            <td>{{ $students->name }}</td>
                            <td>{{ $students->name }}</td>
                            <td>{{ $students->name }}</td>
                            <td>1</td>
                            <td>Action</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection
