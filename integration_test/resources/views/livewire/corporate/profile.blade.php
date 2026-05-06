<section class="content mt-5">
    <style>
        .loader-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* transform: translate(-50%, -50%); */
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            /* width: 80px; */
            /* height: 80px; */
            transform: translate(-50%, -50%);
            /* animation: loader 1.4s infinite ease-in-out; */

        }
    </style>
    <div class="card">
        <div class="card-body position-relative">
            <div class="row">
                <div class="col-sm-3">
                    <div class="position-relative">
                        <img src="{{ $photo?->temporaryUrl() ?? asset('/storage/'.$corporate->attachment) }}" class="avatar img-thumbnail" alt="avatar">
                        <div class="position-absolute" style="top: 15px; right:15px;">
                            <button class="btn btn-sm btn-primary" style="border-radius:50%;" onclick="document.getElementById('attachment').click()">
                                <i class="fa fa-camera"></i>
                            </button>
                        </div>
                        @if ($photo && $photo->temporaryUrl())
                        <div class="btn-group w-100" role="group" aria-label="Basic example">
                            @error('photo')
                            @else
                            <button type="button" class="btn btn-success" wire:click="uploadImage">Upload</button>
                            @enderror
                            <button type="button" class="btn btn-danger" onclick="window.location.reload()">Cancel</button>
                        </div>
                        @endif
                        <div class="loader-wrapper" wire:loading wire:target="uploadImage">
                            <div class="loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @error('photo')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                    @enderror

                    <div class="form-group mt-4" style="display: none;">
                        <label for="attachment" class="control-label">Photograph <small>(Max 2MB)</small></label>
                        <input type="file" wire:model="photo" id="attachment" class="form-control text-center center-block file-upload">
                    </div>
                </div>
                <div class="col-sm-9">

                    <div class="form row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <div class="input-group">
                                    <input type="text" name="name" wire:model.change="name" placeholder="Name" title="Please enter valid Name" class="form-control" required="">
                                    @if ($corporate->name != $name)
                                    <div class="input-group-append">
                                        <button class="btn bg-danger" type="button" wire:click="updateName">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="updateName">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Update
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">Institute Name</label>
                                <input type="text" name="instname" readonly="readonly" value="{{$corporate->institute_name}}" placeholder="Institute Name" title="Please enter institute Name" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="forget_mobile_new" class="control-label">Mobile No.</label>
                                <div class="input-group">
                                    <input type="number" id="forget_mobile_new" wire:model.live="new_phone" minlength="10" maxlength="10" required="" class="form-control" {{ $changePhoneNumber ? '' : 'readonly' }}>
                                    @error('new_phone')
                                    @else
                                    @if ($corporate->phone != $new_phone)
                                    <div class="input-group-append">
                                        <button class="btn bg-danger" type="button" wire:click="updatePhone">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="updatePhone">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Update
                                        </button>
                                    </div>
                                    @endif
                                    @enderror
                                    <div class="input-group-append">
                                        <button class="btn bg-warning" type="button" wire:click="togglePhoneChange">
                                            {!! $changePhoneNumber ? '<i class="fa fa-times"></i>' : '<i class="fa fa-edit"></i>' !!}
                                        </button>
                                    </div>
                                </div>
                                @error('new_phone')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                                @if ($corporate->new_phone && $corporate->phone != $corporate->new_phone)
                                <div class="text-danger mt-2">
                                    <small>
                                        Phone number changed,<br />
                                        <button class="btn btn-xs btn-info" wire:click="$toggle('otpVerifyModalInputOpen')">Verify <b>{{ $corporate->new_phone }}</b></button>
                                        or
                                        <button class="btn btn-xs btn-secondary" wire:click="keepOldPhone">Keep <b>{{ $corporate->phone }}</b></button>
                                    </small>
                                </div>
                                @endif
                            </div>

                            @if ($otpVerifyModalInputOpen)
                            <div class="form-group mt-2">
                                <div class="input-group">
                                    <input type="number" name="user_otp" placeholder="Enter OTP" wire:model.change="user_otp" class="form-control text-center" min="100000" max="999999">
                                    <div class="input-group-append">
                                        <button class="btn bg-success" type="button" wire:click="verifyPhone">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="verifyPhone">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Verify
                                        </button>
                                    </div>
                                </div>
                                @error('user_otp')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="control-label">Email</label>
                                <div class="input-group">
                                    <input type="email" id="email" wire:model.live="new_email" class="form-control" style="border: 1px solid #aaa;" {{ $changeEmailAddress ? '' : 'readonly' }} required>
                                    @error('new_email')
                                    @else
                                    @if ($corporate->email != $new_email)
                                    <div class="input-group-append">
                                        <button class="btn bg-danger" type="button" wire:click="updateEmail">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="updateEmail">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Update
                                        </button>
                                    </div>
                                    @endif
                                    @enderror
                                    <div class="input-group-append">
                                        <button class="btn bg-warning" type="button" wire:click="toggleEmailChange">
                                            {!! $changeEmailAddress ? '<i class="fa fa-times"></i>' : '<i class="fa fa-edit"></i>' !!}
                                        </button>
                                    </div>
                                </div>
                                @error('new_email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                                @if ($corporate->new_email && $corporate->email != $corporate->new_email && !$corporate->email_verified_at)
                                <div class="text-danger mt-2">
                                    <small>
                                        Email address changed,<br />
                                        <button class="btn btn-xs btn-info" wire:click="$toggle('emailVerifyModalInputOpen')">Verify <b>{{ $corporate->new_email }}</b></button>
                                        or
                                        <button class="btn btn-xs btn-secondary" wire:click="keepOldEmail">Keep old email</button>
                                    </small>
                                </div>
                                @endif
                            </div>
                            @if ($emailVerifyModalInputOpen)
                            <div class="form-group mt-2">
                                <div class="input-group">
                                    <input type="number" name="email_code" placeholder="Enter Email OTP" wire:model.change="email_code" class="form-control text-center" min="100000" max="999999">
                                    <div class="input-group-append">
                                        <button class="btn bg-success" type="button" wire:click="verifyEmail">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="verifyEmail">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Verify
                                        </button>
                                    </div>
                                </div>
                                @error('email_code')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

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