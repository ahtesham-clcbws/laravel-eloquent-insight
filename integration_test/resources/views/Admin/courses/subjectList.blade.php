<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Subject List')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Subject List</a></li>

    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Add Subject</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('course.savesubject') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Add New Subject</p>
                                    <input type="text" name="subjectName" class="form-control input-focus"
                                        placeholder="Add New Subject">
                                </div>

                                <div class="form-group">
                                    <input type="file" id="fileInput" class="form-control input-focus" name="image">
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        style="display: none;width:200px">
                                </div>
                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <h4>Subject List</h4>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subject as $subjects)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $subjects->subjectName }}</td>
                                <td>
                                    <span class="badge badge-{{ $subjects->status ? 'primary' : 'dark' }}">
                                        {{ $subjects->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    <button class="btn btn-xs btn-dark" data-toggle="modal"
                                        data-target="#{{ $subjects->id }}List"><span class="fa fa-add"></span> List</button>
                                    |
                                    <button class="btn btn-xs btn-warning" data-toggle="modal"
                                        data-target="#{{ $subjects->id }}"><span class="fa fa-add"></span> Add
                                        Subcategory</button>
                                    |
                                    <a href="{{ route('courses.deleteSubject', ['id' => $subjects->id]) }}">
                                        <span class="fa fa-trash"></span>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="{{ $subjects->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Assign Subject
                                                ({{ $subjects->subjectName }})
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('course.savesubjectsubcategory') }}" method="POST">
                                                @csrf <!-- Add CSRF token for Laravel -->
                                                <input type="hidden" name="subject_id" value="{{ $subjects->id }}">
                                                <div class="form-group">
                                                    <label>SubCategory Name</label>
                                                    <input type="text" name="subject_subcategory_name"
                                                        class="form-control" id="">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save
                                                changes</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="{{ $subjects->id }}List" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label>Subcategory Subject List
                                                ({{ $subjects->subjectName }})
                                            </label>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-responsive">
                                                <thead>
                                                    <th>S.No</th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            1.
                                                        </td>
                                                        <td>
                                                            Math
                                                        </td>
                                                        <td>
                                                            <span class="btn btn-info">Delete</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        label {
            font-weight: bold;
            color: #000;
        }

        .form-group label {
            color: #000;
            font-weight: bold
        }
    </style>
    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });
    </script>

@endsection
