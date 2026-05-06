@extends('corporate.layouts.master')
@section('content')


@section('content')


<section class="content mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadImage" action="{{route('corporate.profilePage')}}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" value="{{$corporate->name}}" placeholder="Name" title="Please enter valid Name" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">Institute Name</label>
                                        <input type="text" name="instname" readonly="readonly" value="{{$corporate->institute_name}}" placeholder="Institute Name" title="Please enter institute Name" class="form-control" required="">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-8 col">
                            <div class="row">

                                <div class="col-md-6 col">
                                    <label for="Mobile No." class="control-label">Mobile No.</label><br>
                                    <div class="input-group">

                                        <input type="number" id="forget_mobile_new" name="phone" minlength="10" maxlength="10" required="" class="form-control" value="{{$corporate->phone}}" readonly="readonly">
                                        <button class="btn bg-dark text-white append forget_send_otp_btn" type="button" style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;" data-toggle="modal" data-target="#exampleModalCenter">
                                            Change Mobile No
                                        </button>

                                    </div>
                                </div>



                                <div class="col-md-6 col">
                                    <div class="form-group">
                                        <label for="Email" class="control-label">Email</label>
                                        <input readonly type="email" id="email" name="email" class="form-control" style="border: 1px solid #aaa;" value="{{$corporate->email}}" required>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="photo">Attach Photograph (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="photograph" class="custom-file-input" value="{{$corporate->photograph}}" id="photo" onchange="validateImage(this,'imagepdf')" {{$corporate->photograph ? '' : 'required'}}>
                                        <label class="custom-file-label" for="photo">Choose file</label>
                                        @error('photograph')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    @if($corporate->photograph)
                                    <div class="input-group-append photograph-image" style="margin-left: 10px;margin-top: -4rem;">
                                        <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('upload/corporate/'.$corporate->photograph) }}" style="background: none;border: none;">
                                            <img src="{{ asset('upload/corporate/'.$corporate->photograph) }}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10re;">
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success" type="submit" id="imageSaveButton">
                                Update
                            </button>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change Mobile No</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form id="forgetForm" method="post" action="#">
                            @csrf

                            <label class="form-label" for="form2Example17">New Mobile No</label>
                            <div class="input-group">
                                <input type="text" pattern="[6-9]{1}[0-9]{9}" name="mobile" placeholder="Enter New Mobile Number" id="forget_mobile" title="Please enter valid mobile" class="form-control" required>
                                <button class="btn bg-dark text-white append forget_send_otp_btn" onclick="sendOtp('forgetPassword','otp_send')" type="button" style="width:80px;border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;">
                                    Get Otp
                                </button>
                            </div>

                            <br>
                            <label class="form-label" for="form2Example17">OTP Code</label>
                            <div class="input-group">

                                <input type="text" name="otp" placeholder="otp Number" id="forget_otp" title="Please enter valid otp" class="form-control" required>
                                <button class="btn bg-dark text-white append forget_verify_otp_btn" onclick="sendOtp('forgetPassword','otp_verify')" type="button" style="width:80px;border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;">
                                    Verfiy Otp
                                </button>
                            </div>



                        </form>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#photo').change(function() {
            var formData = new FormData();
            formData.append('photograph', $(this)[0].files[0]);

            $.ajax({
                url: '{{ route("corporate.upload.photo") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    location.reload();
                },
                error: function(xhr, status) {
                    error(xhr.responseText);
                }
            });
        });
    });

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
            //errors('Invalid user type.');

            Swal.fire({
                title: 'Woops!',
                text: 'Invalid user type.',
                icon: 'error',
                confirmButtonText: 'Close'
            });

            sendBtn.prop('disabled', false);
            verifyBtn.prop('disabled', false);
            return;
        }

        var mobile = mobileField.val();
        if (!mobile) {
            //errors('Please input a valid mobile number.');
            Swal.fire({
                title: 'Woops!',
                text: 'Please input a valid mobile number.',
                icon: 'error',
                confirmButtonText: 'Close'
            });
            sendBtn.prop('disabled', false);
            verifyBtn.prop('disabled', false);
            return;
        }

        var mobileNumber = parseInt(mobile);
        if (isNaN(mobileNumber) || mobileNumber.toString().length !== 10) {
            // errors('10 digit mobile number is required.');
            Swal.fire({
                title: 'Woops!',
                text: '10 digit mobile number is required.',
                icon: 'error',
                confirmButtonText: 'Close'
            });
            sendBtn.prop('disabled', false);
            verifyBtn.prop('disabled', false);
            return;
        }

        formData.append('form_name', type);
        formData.append('form_user', userType);
        formData.append('mobile', mobileNumber);

        $.ajax({
            url: '/corporate/corporate_otp_verification_mobile_no_change',
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

                if (data.mobile_no === '') {

                    Swal.fire({
                        title: 'Congratulation!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });

                } else {
                    Swal.fire({
                        title: 'Congratulation!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Close'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#forget_mobile_new").val(data.mobile_no);
                            $("#exampleModalCenter").hide();

                        }
                    });
                }
            } else {
                // errors(data.message);
                Swal.fire({
                    title: 'Woops!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });

            }
        }).fail(function() {

            //errors('Server error, please try again later.');
            Swal.fire({
                title: 'Woops!',
                text: 'Server error, please try again later.',
                icon: 'error',
                confirmButtonText: 'Close'
            });

        })
    }
</script>
@endsection