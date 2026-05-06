@extends('administrator.layouts.master')
@section('title')
Preparation Course List
@endsection

@section('content')
<div class="row py-5 pl-3 pr-3">
    <div class="container card p-0">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 text-start">
                    <h4>Course List </h4>
                </div>
                <div class="col-md-6 text-end"> <a href="{{route('admin.home.course')}}" class="btn btn-primary">Add Course </a></div>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Scholarship Category</th>
                                    <th>Name</th>
                                    <th>Exam Date</th>
                                    <th>Date</th>
                                    <th>Is Featured</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $key => $course)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $course->scholarshipCategory?->name }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->exam_Date}}</td>
                                    <td>{{ $course->exam_Date}}</td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="featured-{{ $course->id }}">
                                                <input type="checkbox" id="featured-{{ $course->id }}" data-id="{{ $course->id }}" class="toggle-featured" {{ $course->is_featured ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Deactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="form-control2">
                                            <label class="switch" for="status-{{ $course->id }}">
                                                <input type="checkbox" id="status-{{ $course->id }}" data-id="{{ $course->id }}" class="toggle-status" {{ $course->status ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Deactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    
                                 </td>
                                    <td class="color-primary" style="text-align: center">
                                        <a href="{{route('admin.home.course',[$course->id])}}"><span class="fa fa-edit"></span> </a> | <a href="{{route('admin.home.courseDelete',[$course->id])}}"><span class="fa fa-trash"></span></a>
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
</div>
<script>
    $(document).ready(function() {
        $('.toggle-featured').on('change', function() {
            var courseId = $(this).data('id');
            var isFeatured = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('toggle.featured') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: courseId,
                    is_featured: isFeatured
                },
                success: function(response) {
                    if(response.status){
                        success(response.message);
                    }else{
                        error(response.message);
                    }
                },
                error: function(xhr) {
                    error(xhr.responseText);
                }
            });
        });

        $('.toggle-status').on('change', function() {
            var courseId = $(this).data('id');
            var isStatus = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('toggle.status') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: courseId,
                    status: isStatus
                },
                success: function(response) {
                    if(response.status){
                        success(response.message);
                    }else{
                        error(response.message);
                    }
                },
                error: function(xhr) {
                    error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection