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
            <a href="{{ route('studentDashboard') }}"
                style="color: #000 !Important;
                    position: absolute;
                    top: 8px;
                    right: 8px;
                    cursor: pointer;
                    z-index: 9999;">
                <svg class="bi bi-x-lg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                </svg>
            </a>
            <div class="row">
                <div class="col-sm-3">
                    <div class="position-relative">
                        <img class="avatar img-thumbnail"
                            src="{{ $photo?->temporaryUrl() ?? asset('/storage/' . $student->photograph) }}"
                            alt="avatar">
                        <div class="position-absolute" style="top: 15px; right:15px;">
                            <button class="btn btn-sm btn-primary" style="border-radius:50%;"
                                onclick="document.getElementById('photograph').click()">
                                <i class="fa fa-camera"></i>
                            </button>
                        </div>
                        @if ($photo && $photo->temporaryUrl())
                            <div class="btn-group w-100" role="group" aria-label="Basic example">
                                @error('photo')
                                @else
                                    <button class="btn btn-success" type="button" wire:click="uploadImage">Upload</button>
                                @enderror
                                <button class="btn btn-danger" type="button"
                                    onclick="window.location.reload()">Cancel</button>
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
                        <label class="control-label" for="photograph">Photograph <small>(Max 2MB)</small></label>
                        <input class="form-control center-block file-upload text-center" id="photograph" type="file"
                            wire:model="photo">
                    </div>
                </div>
                <div class="col-sm-9">

                    <div class="form row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <div class="input-group">
                                    <input class="form-control" name="name" type="text"
                                        title="Please enter valid Name" wire:model.live="name" placeholder="Name"
                                        required="">
                                    @if ($student->name != $name)
                                        <div class="input-group-append">
                                            <button class="btn bg-danger" type="button" wire:click="updateName">
                                                <div class="spinner-border spinner-border-sm" role="status"
                                                    wire:loading wire:target="updateName">
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
                                <label class="control-label" for="gender">Gender</label>
                                <div class="input-group">
                                    <select class="form-control" wire:model.live="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">FeMale</option>
                                        <option value="Transgender">Transgender</option>
                                    </select>

                                    @if ($student->gender != $gender)
                                        <div class="input-group-append">
                                            <button class="btn bg-danger" type="button" wire:click="updateGender">
                                                <div class="spinner-border spinner-border-sm" role="status"
                                                    wire:loading wire:target="updateGender">
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
                                <label class="control-label" for="forget_mobile_new">Mobile No.</label>
                                <div class="input-group">
                                    <input class="form-control" id="forget_mobile_new" type="number"
                                        wire:model.live="new_mobile" minlength="10" maxlength="10" required=""
                                        {{ $changeMobileNumber ? '' : 'readonly' }}>
                                    @error('new_mobile')
                                    @else
                                        @if ($student->mobile != $new_mobile)
                                            <div class="input-group-append">
                                                <button class="btn bg-danger" type="button" wire:click="updateMobile">
                                                    <div class="spinner-border spinner-border-sm" role="status"
                                                        wire:loading wire:target="updateMobile">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    Update
                                                </button>
                                            </div>
                                        @endif
                                    @enderror
                                    <div class="input-group-append">
                                        <button class="btn bg-warning" type="button"
                                            wire:click="toggleMobileChange">
                                            {!! $changeMobileNumber ? '<i class="fa fa-times"></i>' : '<i class="fa fa-edit"></i>' !!}
                                        </button>
                                    </div>
                                </div>
                                @error('new_mobile')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                                @if ($student->new_mobile && $student->mobile != $student->new_mobile)
                                    <div class="text-danger mt-2">
                                        <small>
                                            Mobile number changed,<br />
                                            <button class="btn btn-xs btn-info"
                                                wire:click="$toggle('otpVerifyModalInputOpen')">Verify
                                                <b>{{ $student->new_mobile }}</b></button>
                                            or
                                            <button class="btn btn-xs btn-secondary" wire:click="keepOldMobile">Keep
                                                <b>{{ $student->mobile }}</b></button>
                                        </small>
                                    </div>
                                @endif
                            </div>

                            @if ($otpVerifyModalInputOpen)
                                <div class="form-group mt-2">
                                    <div class="input-group">
                                        <input class="form-control text-center" name="user_otp" type="number"
                                            placeholder="Enter OTP" wire:model.change="user_otp" min="100000"
                                            max="999999">
                                        <div class="input-group-append">
                                            <button class="btn bg-success" type="button" wire:click="verifyMobile">
                                                <div class="spinner-border spinner-border-sm" role="status"
                                                    wire:loading wire:target="verifyMobile">
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
                                <label class="control-label" for="email">Email</label>
                                <div class="input-group">
                                    <input class="form-control" id="email" type="email"
                                        style="border: 1px solid #aaa;" wire:model.live="new_email"
                                        {{ $changeEmailAddress ? '' : 'readonly' }} required>
                                    @error('new_email')
                                    @else
                                        @if ($student->email != $new_email)
                                            <div class="input-group-append">
                                                <button class="btn bg-danger" type="button" wire:click="updateEmail">
                                                    <div class="spinner-border spinner-border-sm" role="status"
                                                        wire:loading wire:target="updateEmail">
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
                                @if ($student->new_email && $student->email != $student->new_email && !$student->email_verified_at)
                                    <div class="text-danger mt-2">
                                        <small>
                                            Email address changed,<br />
                                            <button class="btn btn-xs btn-info"
                                                wire:click="$toggle('emailVerifyModalInputOpen')">Verify
                                                <b>{{ $student->new_email }}</b></button>
                                            or
                                            <button class="btn btn-xs btn-secondary" wire:click="keepOldEmail">Keep
                                                old email</button>
                                        </small>
                                    </div>
                                @endif
                            </div>
                            @if ($emailVerifyModalInputOpen)
                                <div class="form-group mt-2">
                                    <div class="input-group">
                                        <input class="form-control text-center" name="email_code" type="number"
                                            placeholder="Enter Email OTP" wire:model.change="email_code"
                                            min="100000" max="999999">
                                        <div class="input-group-append">
                                            <button class="btn bg-success" type="button" wire:click="verifyEmail">
                                                <div class="spinner-border spinner-border-sm" role="status"
                                                    wire:loading wire:target="verifyEmail">
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
                        {{-- <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="control-label">Email</label>
                                <input type="email" id="email" class="form-control" value="{{ $student->email }}" disabled>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="mobile" class="control-label">Mobile Number</label>
                        <input type="tel" id="mobile" class="form-control" value="{{ $student->mobile }}" disabled>
                    </div>
                </div> --}}


                        <div class="col-md-6 mt-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-group">
                                    <label class="control-label" for="disability">Person Disability</label>
                                    <br />
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="disability_yes" type="radio"
                                            value="Yes" wire:model.live="disability">
                                        <label class="form-check-label" for="disability_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="disability_no" type="radio"
                                            value="No" wire:model.live="disability">
                                        <label class="form-check-label" for="disability_no">No</label>
                                    </div>
                                </div>

                                @if ($student->disability != $disability)
                                    <div class="input-group-append">
                                        <button class="btn bg-danger btn-sm" type="button"
                                            wire:click="updateDisability">
                                            <div class="spinner-border spinner-border-sm" role="status" wire:loading
                                                wire:target="updateDisability">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Update Disability
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>
    </div>

</section>
