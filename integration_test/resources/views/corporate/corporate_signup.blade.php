<html>

<head>

    <link rel="stylesheet" href="{{asset('css/f1font-awesome.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{ asset('website/assets/images/fav-icon.png') }}" type="image/x-icon">
    <script src="{{ asset('website/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('website/assets/js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <title>Corporate Signup</title>
    <style>
        .toast-top-right-below {
            top: 20px;
            right: 40%;
            height: 100px;
            color: black !important;
        }

        .toast-top-right-below .toast-error {
            min-height: 70px;
            opacity: 5 !important;
            font-size: 20px;
            background-color: #28a745 !important;
        }
    </style>
</head>

<body class="d-flex align-items-center" style="background-color: #4629cc;">
    <div class="container p-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">

                            <img src="https://www.21kschool.com/blog/wp-content/uploads/2021/01/rptgtpxd-1396254731.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;height:100%" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">

                                <form method="post">
                                    @csrf
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <span class="h1 fw-bold mb-0">
                                            <img src="{{ asset('website/assets/images/fav-icon.png') }}" alt="Signup form" class="img-thumnails" style="border-radius: 1rem 0 0 1rem;height:100%" />
                                        </span>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <label class="form-label" for="form2Example17">Branch code<span class="text-danger"> *</span></label>
                                        <input type="text" name="branch_code" placeholder="Branch code" title="Please enter valid Branch code" class="form-control" required value="{{old('branch_code')}}">
                                        @error('branch_code')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col">
                                            <div data-mdb-input-init class="form-outline mb-3">
                                                <label class="form-label" for="form2Example17">Mobile Number<span class="text-danger"> *</span></label>
                                                <input type="text" name="mobile" placeholder="Enter Mobile number" title="Please enter valid  Mobile number" class="form-control" required value="{{old('mobile')}}">
                                                @error('mobile')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div data-mdb-input-init class="form-outline mb-3">
                                                <label class="form-label" for="form2Example17">Email ID<span class="text-danger"> *</span></label>
                                                <input type="text" name="email" placeholder="Email ID" title="Please enter valid Email ID" class="form-control" required value="{{old('email')}}">
                                                @error('email')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col">
                                            <div data-mdb-input-init class="form-outline mb-3">
                                                <label class="form-label" for="form2Example27">Password<span class="text-danger"> *</span></label>
                                                <div style="display: flex;">
                                                    <input type="password" name="password" placeholder="Password " class="form-control" required value="{{old('password')}}">
                                                    <i toggle="#password-field" style="cursor: pointer;margin-left: -31px;margin-top: 10px;" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                                </div>
                                                @error('password')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-md-6 col">
                                            <div data-mdb-input-init class="form-outline mb-3">
                                                <label class="form-label" for="form2Example27">Confirm Password<span class="text-danger"> *</span></label>
                                                <div style="display: flex;">
                                                    <input type="password" name="confirm_password" placeholder="Confirm Password " class="form-control" required value="{{old('confirm_password')}}">
                                                    <i toggle="#password-field" style="cursor: pointer;margin-left: -31px;margin-top: 10px;" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                                </div>
                                                @error('confirm_password')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <br>


                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div data-mdb-input-init class="form-outline mb-3">

                                                <div style="display: flex;">
                                                    @php($institudeTermsCondition = DB::table('terms_conditions')->where([['status',1],['type','institute'],['page_name','terms-and-condition']])->first())
                                                    <input type="checkbox" style="width: 20px;height:20px" name="privacy_policy" id="privacy_policy" required> &nbsp; I accept the &nbsp;
                                                    @if($institudeTermsCondition) <a style="text-decoration: underline;" href="{{ asset('home/'.$institudeTermsCondition->terms_condition_pdf) }}" target="_blank"> Terms & Conditions </a>@endif

                                                </div>

                                            </div>
                                            <br>


                                        </div>

                                    </div>
                                    <div class="pt-1 mb-3">
                                        <button class="btn btn-dark btn-md btn-block" style="width: 100%;background: #1616c9;font-weight: 700;" type="submit">Signup</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</body>

</html>