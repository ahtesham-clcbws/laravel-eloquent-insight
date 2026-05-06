<html>

    <head>

        <link href="{{ asset('css/f1font-awesome.min.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
        <link type="image/x-icon" href="{{ asset('website/assets/images/fav-icon.png') }}" rel="shortcut icon">
        <script src="{{ asset('website/assets/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('website/assets/js/custom.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Corporate Login</title>
    </head>

    <body>

        <section class="vh-100" style="background-color: #4629cc;">
            <div class="container p-4">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col col-xl-10">
                        <div class="card" style="border-radius: 1rem;">
                            <div class="row g-0">
                                <div class="col-md-6 col-lg-5 d-none d-md-block">

                                    <img class="img-fluid"
                                        src="https://www.21kschool.com/blog/wp-content/uploads/2021/01/rptgtpxd-1396254731.jpg"
                                        alt="login form" style="border-radius: 1rem 0 0 1rem;height:100%" />
                                </div>
                                <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                    <div class="card-body p-lg-5 p-4 text-black">

                                        <form id="forgetForm" method="post" action="#">
                                            @csrf
                                            <div class="d-flex align-items-center mb-3 pb-1">
                                                <span class="h1 fw-bold mb-0">
                                                    <img class="img-thumnails"
                                                        src="{{ asset('website/assets/images/fav-icon.png') }}"
                                                        alt="login form"
                                                        style="border-radius: 1rem 0 0 1rem;height:100%" />
                                                </span>
                                            </div>

                                            <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">To Reset your
                                                password. Please provide your registered mobile no</h5>


                                            <label class="form-label" for="form2Example17">Mobile No</label>
                                            <div class="input-group">
                                                <input class="form-control" id="forget_mobile" name="mobile"
                                                    type="text" title="Please enter valid mobile"
                                                    pattern="[6-9]{1}[0-9]{9}" placeholder="Mobile Number" required>
                                                <button class="btn bg-dark append forget_send_otp_btn text-white"
                                                    type="button"
                                                    style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;"
                                                    onclick="sendOtp('forgetPassword','otp_send')">
                                                    Get Otp
                                                </button>
                                            </div>

                                            <br>
                                            <label class="form-label" for="form2Example17">OTP Code</label>
                                            <div class="input-group">

                                                <input class="form-control" id="forget_otp" name="otp"
                                                    type="text" title="Please enter valid otp"
                                                    placeholder="otp Number" required>
                                                <button class="btn bg-dark append forget_verify_otp_btn text-white"
                                                    type="button"
                                                    style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;"
                                                    onclick="sendOtp('forgetPassword','otp_verify')">
                                                    Verfiy Otp
                                                </button>
                                            </div>

                                            <div class="full newpasswordn" style="display:none;">
                                                <label class="form-label" for="form2Example17">New Password</label>
                                                <div class="input-group">

                                                    <input class="form-control" id="new_password" name="new_password"
                                                        type="text" placeholder="New Password *" required>
                                                    <i class="fa fa-fw fa-eye-slash field-icon toggle-password"
                                                        style="position: relative;left: -23px;top: 7px;"
                                                        toggle="#password-field"></i>
                                                    @error('new_password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror


                                                </div>

                                                <br>

                                            </div>


                                            <div class="full newpasswordn" id="confirm_password" style="display:none;">
                                                <label class="form-label" for="form2Example17">Confirm Password</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="confirm_Password"
                                                        name="confirm_password" type="text"
                                                        placeholder="confirm_Password *" required>
                                                    <i class="fa fa-fw fa-eye-slash field-icon toggle-password"
                                                        style="position: relative;left: -23px;top: 7px;"
                                                        toggle="#password-field"></i>
                                                    @error('confirm_password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>

                                                <br>

                                            </div>



                                            <br>

                                            <div class="mb-4 pt-1">
                                                <button class="btn btn-dark btn-md btn-block" id="forgetBtn"
                                                    type="submit"
                                                    style="display:none; width: 100%;background: #1616c9;font-weight: 700;">Submit</button>
                                            </div>

                                            <a class="small text-muted" href="{{ route('corporatelogin') }}">back to
                                                login?</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {



            })
        </script>
        <script>
            function success(msg) {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",

                }
                toastr.success(msg);
            }

            function error(msg) {
                toastr.options = {
                    "closeButton": false,
                    "debug": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",

                }
                toastr.error(msg)
            }

            //       new DataTable('.datatablecl', {
            //     responsive: true
            // });
        </script>

        @stack('custom-scripts')
        <x-message />

        <script type="text/javascript">
            function success(msg) {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-center-below",
                    "preventDuplicates": false,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr.success(msg);
            }

            function errors(msg, customclass = '') {
                let positionClass = ''; // Default position class

                if (customclass !== '') {
                    positionClass = customclass; // Use custom class if provided
                } else {
                    positionClass = 'toast-top-center'; // Default position class if custom class is not provided
                }

                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": positionClass,
                    "preventDuplicates": false,
                    "showDuration": "300",
                    "hideDuration": "2000",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr.error(msg);
            }



            $(document).ready(function() {
                $('#loginBtn').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('studentlogin') }}",
                        method: 'post',
                        data: $('#loginForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                success("Login Successfully.", 'toast-login-center-below')
                                window.location.href = "{{ route('studentDashboard') }}";
                            } else {
                                errors("Invalid Login Credentials", 'toast-login-center-below')
                                // alert('Invalid Login Credentials');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('An error occurred while processing your request.');
                        }
                    });
                });

                $('#studentRegister').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('studentSignup') }}",
                        method: 'post',
                        data: $('#studentSignup').serialize(),
                        success: function(response) {
                            if (response.success) {
                                success(response.msg, 'toast-login-center-below')
                                setTimeout(() => {
                                    window.location.href =
                                        "{{ route('studentDashboard') }}";
                                }, 1000);
                            } else {
                                errors(response.msg, 'toast-login-center-below')
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = '';
                            var errorsmsg = JSON.parse(xhr.responseText).errors;
                            for (var key in errorsmsg) {
                                if (errorsmsg.hasOwnProperty(key)) {
                                    errorMessage += errorsmsg[key][0] + '<br>';
                                }
                            }
                            errors(errorMessage, 'toast-login-center-below')
                        }
                    });
                });
            });
        </script>
        <script>
            function sendOtp(userType, type) {
                var sendBtn, verifyBtn, mobileField, otp;
                var formData = new FormData();
                console.log(userType);

                if (userType === 'forgetPassword') {

                    mobileField = $('#forget_mobile');
                    sendBtn = $('.forget_send_otp_btn');
                    if (type == 'otp_verify') {
                        verifyBtn = $('.forget_verify_otp_btn');
                        otp = $('#forget_otp').val();
                        formData.append('otp', otp);
                    }
                } else {
                    errors('Invalid user type.');
                    sendBtn.prop('disabled', false);
                    verifyBtn.prop('disabled', false);
                    return;
                }

                var mobile = mobileField.val();
                if (!mobile) {
                    errors('Please input a valid mobile number.');
                    sendBtn.prop('disabled', false);
                    verifyBtn.prop('disabled', false);
                    return;
                }

                var mobileNumber = parseInt(mobile);
                if (isNaN(mobileNumber) || mobileNumber.toString().length !== 10) {
                    errors('10 digit mobile number is required.');
                    sendBtn.prop('disabled', false);
                    verifyBtn.prop('disabled', false);
                    return;
                }


                formData.append('form_name', type);
                formData.append('form_user', userType);
                formData.append('mobile', mobileNumber);

                $.ajax({
                    url: '/corporate_otp_verification',
                    data: formData,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {
                    console.log(data);
                    if (data.status == true) {
                        Swal.fire({
                            title: 'Congratulation!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Cool'
                        })
                        sendBtn.prop('disabled', false);
                        if (type == 'otp_verify') {
                            verifyBtn.text('Verified');
                        }
                        console.log(userType == 'forgetPassword' && type == 'otp_verify')
                        console.log(userType + ' ' + type)
                        if (userType == 'forgetPassword' && type == 'otp_verify') {
                            $('.newpasswordn').show();
                            $('#forgetBtn').show();
                            $('.forgetmobiledn').hide();
                        }
                    } else {
                        errors(data.message);
                        sendBtn.prop('disabled', false);
                        verifyBtn.prop('disabled', false);
                    }
                }).fail(function() {
                    errors('Server error, please try again later.');
                    sendBtn.prop('disabled', false);
                    verifyBtn.prop('disabled', false);
                })
            }

            $('#forgetBtn').on('click', function(e) {
                e.preventDefault();
                var forget_mobile = $('#forget_mobile').val();
                var forget_otp = $('#forget_otp').val();
                var new_password = $('#new_password').val();
                var confirm_Password = $('#confirm_Password').val();
                console.log('gg' + new_password);
                console.log('hhu' + confirm_Password);

                $.ajax({
                    url: "{{ route('corporate.resetforgetPassword') }}",
                    method: 'post',
                    data: {
                        'forget_mobile': forget_mobile,
                        'forget_otp': forget_otp,
                        'new_password': new_password,
                        'confirm_Password': confirm_Password,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {

                            $('.newpasswordn').hide();
                            $('#forgetBtn').hide();
                            $('.forgetmobiledn').show();

                            Swal.fire({
                                title: 'Congratulation!',
                                text: "Your password has been reset successfully!Please login to continue.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Back to Login'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location = "{{ route('corporatelogin') }}"
                                }
                            })

                        } else {
                            errors(response.message)
                        }
                    },
                    error: function(xhr, status, errorType) {
                        errors(xhr.responseText)
                    }
                })
            });
        </script>

    </body>

</html>
