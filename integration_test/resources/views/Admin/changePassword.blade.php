<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Change Password')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Password</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Change Password</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('admin.changePassword') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Previous Password</label>
                                    <input name="old_password" type="password" required class="form-control" placeholder="Please enter old password...">
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input name="new_password" type="password" required class="form-control" placeholder="Please enter new password to create...">
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input name="confirm_password" type="password" required class="form-control" placeholder="Confirm New Password...">
                                </div>
                                <input type="submit" class="btn btn-primary btn-flat m-b-15" value="Change Password">
                            </form>
                        </div>
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
 ''

 <!-- Initialize CKEditor -->
 <script>
     ClassicEditor
         .create(document.querySelector('#editor'))
         .then(editor => {
             console.log(editor);
         })
         .catch(error => {
             console.error(error);
         });
 </script>
@endsection
