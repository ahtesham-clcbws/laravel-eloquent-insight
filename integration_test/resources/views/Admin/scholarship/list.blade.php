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
    <a href="{{ route('home.addscholorship') }}"><button class="btn btn-xs btn-dark" style="margin:10px">Add New</button></a>
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
                                        <th>Fee</th>
                                        <th>Exam</th>
                                        <th>Admit Card</th>
                                        <th>Result</th>
                                        <th>Action</th>
                                        <!-- Add more headers as needed -->
                                    </tr>
                                </thead>
                            </table>
                            @foreach ($scholarshipExams as $exam)
                                <table class="table" style="font-size:12px ">
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $exam->name }}</td>
                                        <td>{{ $exam->price }}</td>
                                        <td>{{ $exam->additional_column1 }}</td>
                                        <td>{{ $exam->admit_Card_from }}</td>
                                        <td>{{ $exam->result_on }}</td>
                                        <td> <a href="#" data-toggle="modal"
                                                data-target="#myModalLogin{{ $exam->id }}"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> Subject
                                                List</a></td>
                                    </tr>
                                </table>
                                <div id="myModalLogin{{ $exam->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <!-- SECTION: POP UP TRAVEL BOOKING FORM AND IMG -->
                                            <div class="pop-up">
                                                <!--POP UP IMG-->
                                                <div class="pop-up2">
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                    <div class="book-now">
                                                        <table class="table" style="font-size:12px ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Total Ques.</th>
                                                                    <th>Marks</th>
                                                                    <!-- Add more headers as needed -->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($examSubjects as $subject)
                                                                    @if ($subject->scholorship_exam_id == $exam->id)
                                                                        <tr>
                                                                            <td>{{ $subject->name }}</td>
                                                                            <td>{{ $subject->total_ques }}</td>
                                                                            <td>{{ $subject->marks }}</td>

                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- SECTION: POP UP END -->
                                        </div>
                                    </div>
                                </div>
                                {{-- End of login --}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
