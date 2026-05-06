@extends('administrator.layouts.master')
@section('content')


<div class="row justify-content-center mt-5">
    <div class="col-lg-6">
        <div class="card-header">
            <h2 class="book-tit">Forget Password</h2>
        </div>
        <div class="panel panel-default m-t-15">
            <div class="panel-body">
                <div class="card alert">
                    <div class="card-body">
                        <form action="{{ route('forget.password') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Email ID</label>
                                <div class="input-group">
                                    <input type="text" name="email" placeholder="email Number" id="admin_email_forget" title="Please enter valid mobile" class="form-control" required="">
                                    <button class="btn bg-dark text-white append " onclick="sendOtp('forgetPassword','otp_send')" type="button" style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;">
                                        Get Otp
                                    </button>
                                </div>
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