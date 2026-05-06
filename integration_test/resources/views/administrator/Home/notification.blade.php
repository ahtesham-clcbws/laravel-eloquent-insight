@extends('administrator.layouts.master')
@section('title')
News And Event
@endsection


@section('content')
<div class="row py-2 pl-3 pr-3">
    <div class="container ">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header"> <h4>Add Notification</h4> </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form action="{{ route('news.notificationSave') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Title</p>
                                        <textarea class="ckeditor" id="editor" name="title"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Details</p>
                                        <textarea class="ckeditor" id="editor1" name="details"></textarea>
                                    </div>
                                    <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header"><h4>Notification List</h4></div>
              
                <div class="card table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr.N.</th>
                                <th>Title</th>
                                <th>Status</tth>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notification as $notifications)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{!! $notifications->title !!}</td>
                                 <td>
                                 <div class="form-control2">
                                        <label class="switch" for="status-{{ $notifications->id }}">
                                            <input type="checkbox" id="status-{{ $notifications->id }}" data-id="{{ $notifications->id }}" onchange="toggleStatus(this, 'home_notification')" {{ $notifications->status ? 'checked' : '' }}>
                                            <div class="slider round">
                                                <span class="off">Inactive</span>
                                                <span class="on">Active</span>
                                            </div>
                                        </label>
                                    </div>
                                </td> 

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