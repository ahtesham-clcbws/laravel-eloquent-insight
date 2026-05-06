<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Notification')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Notification</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Add Notification</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('news.notificationSave') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <p class="text-muted f-s-12">Title</p>
                                    <textarea id="editor" name="title"></textarea>
                                </div>

                                <div class="form-group">
                                    <p class="text-muted f-s-12">Details</p>
                                    <textarea id="editor1" name="details"></textarea>
                                </div>
                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <h4>Notification List</h4>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notification as $notifications)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{!! $notifications->title !!}</td>
                                {{-- <td>
                                    <span class="badge badge-{{ $category->status ? 'primary' : 'dark' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td> --}}

                                <td style="text-align: center">
                                    <a href="{{ route('news.notificationDelete', ['id' => $notifications->id]) }}">
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
