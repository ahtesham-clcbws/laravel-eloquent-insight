<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Category')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Scholorship</a></li>
    </ol>
@endsection
<style>
    .boxShadow {
        margin: 10px auto;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .table td {
        text-align: center
    }

    table thead tr th {
        text-align: center
    }
</style>
@section('content')

<form action="{{ route('students.exportCsv') }}" method="GET">
   <div class="row">
    <div class="col-lg-6">
        <select class="form-control" name="exam_id">
            <option value="">Select Exam</option>
            @foreach ($exam as $exams)
                <option value="{{ $exams->id }}">{{ $exams->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-1">
       <button type="submit" class="btn btn-xs btn-dark" style="margin:10px">Download
            Excel</button>
    </div>
   </div>
</form>


<form action="{{ route('students.uploadexcel') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
     <div class="col-lg-6">
         <input type="file" class="form-control" name="excel">
     </div>
     <div class="col-lg-1">
        <button type="submit" class="btn btn-xs btn-dark" style="margin:10px">Upload
             Excel</button>
     </div>
    </div>
 </form>



    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">List Scholorship</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <table class="table" style="font-size:12px ">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Name</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                        <!-- Add more headers as needed -->
                                    </tr>
                                </thead>
                            </table>
                            @foreach ($student as $students)
                                <table class="table" style="font-size:12px ">
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $students->name }}</td>
                                        <td>{{ $students->dob }}</td>
                                        <td>{{ $students->gender }}</td>
                                        <td>{{ $students->address }}</td>
                                        <td> <a href="#" data-toggle="modal"
                                                data-target="#myModalLogin{{ $students->id }}"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> Full Details</a>
                                        </td>
                                    </tr>
                                </table>

                                {{-- End of login --}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
