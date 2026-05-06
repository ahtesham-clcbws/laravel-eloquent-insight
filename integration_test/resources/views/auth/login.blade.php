@extends('layouts.app')

@section('content')



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <i toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3" id="otp_row" style="display: none;">
                            <label for="otp" class="col-md-4 col-form-label text-md-end">{{ __('Enter OTP') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="otp" placeholder="Enter OTP" id="admin_otp" title="Please enter valid otp" class="form-control" autocomplete="off">
                                    <button class="btn bg-dark text-white append admin_otp_sent_btn" onclick="sendOtp('admin','otp_send')" type="button" style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0; ">
                                        Get Otp
                                    </button>
                                </div>
                                @error('otp')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 d-none">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Show OTP field if there's an OTP error or if it was previously shown
        @if($errors->has('otp'))
            $('#otp_row').show();
            $('#admin_otp').attr('required', true);
        @endif

        $('#email').on('blur', function() {
            let email = $(this).val();
            if (email) {
                // We could potentially check if this email belongs to an admin here via AJAX
                // But for now, let's just show the OTP field if it's an admin path
                if (window.location.pathname.includes('/administrator')) {
                    $('#otp_row').show();
                    $('#admin_otp').attr('required', true);
                }
            }
        });
        
        // If we are on the administrator login page, show the OTP field
        if (window.location.pathname.includes('/administrator')) {
            $('#otp_row').show();
            $('#admin_otp').attr('required', true);
        }
    });
</script>
@endpush


@endsection
