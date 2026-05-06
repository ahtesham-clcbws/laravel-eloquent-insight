<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Course List')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.subcategory') }}"> Class List</a></li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>Class List </h4>
                    <div class="card-header-right-icon">

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class Name</th>
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoriesWithSubcategories as $category)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if ($category->subcategories->isEmpty())
                                                <a href="{{ route('courses.subcategorybyid', ['id' => $category->id]) }}">
                                                    <span class="btn btn-xs btn-danger">Add Subcategory</span></a>
                                            @else
                                                @foreach ($category->subcategories as $subcategory)
                                                    {{ $subcategory->subcategory_name }} &nbsp;
                                                    {{-- <span class="btn btn-xs btn-info" data-toggle="modal" data-target="#exampleModal">Assign Subject</span> --}}

                                                    <span class="btn btn-xs btn-info assign-subject" data-toggle="modal"
                                                        data-target="#{{ $subcategory->id }}">Assign Subject</span>
                                                    @if (!$loop->last)
                                                        <br> <!-- Add a line break if it's not the last subcategory -->
                                                    @endif

                                                    <div class="modal fade" id="{{ $subcategory->id }}" tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Assign Subject
                                                                        ({{ $subcategory->subcategory_name }})</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('course.savesubjects') }}" method="POST">
                                                                        @csrf <!-- Add CSRF token for Laravel -->
                                                                        <input type="hidden" name="subcategoryId" value="{{ $subcategory->id }}">
                                                                        <input type="hidden" name="categoryId" value="{{ $subcategory->category_id }}">
                                                                        @foreach ($subjectList as $subject)
                                                                            <p>
                                                                                <input type="checkbox" name="subject_ids[]"
                                                                                    value="{{ $subject->id }}" {{ $subcategory->subjects->contains('id', $subject->id) ? 'checked' : '' }}>
                                                                                {{ $subject->subjectName }}
                                                                            </p>
                                                                        @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        id="saveChangesBtn">Save changes</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="color-primary" style="text-align: center">
                                            @foreach ($subcategory->subjects as $subject)
                                            <p>
                                                {{ $subject->subjectName }}

                                            </p>
                                        @endforeach
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





@endsection
