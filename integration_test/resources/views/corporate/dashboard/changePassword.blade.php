@extends('corporate.layouts.master')
@section('content')

    <div class="row justify-content-center mt-5">
        <div class="col-lg-6">
            <div class="panel panel-default m-t-15">
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('corporate.changePassword') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Previous Password</label>
                                    <input name="old_password" type="password" required class="form-control" placeholder="Please enter old password...">
                                    <i toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input name="new_password" type="password" required class="form-control" placeholder="Please enter new password to create...">
                                    <i toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input name="confirm_password" type="password" required class="form-control" placeholder="Confirm New Password...">
                                    <i toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                </div>
                                <input type="submit" class="btn btn-primary btn-flat m-b-15" value="Change Password">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

 
@endsection
