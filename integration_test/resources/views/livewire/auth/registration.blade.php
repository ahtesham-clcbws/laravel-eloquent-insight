<div>
    <style>
        .input-group input {
            border-end-end-radius: 0 !important;
            border-start-end-radius: 0 !important;
        }

        .input-group button {
            border-end-start-radius: 0 !important;
            border-start-start-radius: 0 !important;
        }

        .newButton {
            background-color: #f73f05;
            border-color: #f73f05;
            color: white;
        }
    </style>

    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container py-5 pb-4 text-center">
            <h2 class="text-white" style="font-size:32px">Student Registration</h2>
            <p class="mt-3 text-white" style="font-size: 18px;">Registration fee <b>&#8377; <span
                        style="font-size: 140%;">850</span></b> will be applicable</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mx-auto">

                @if ($isRegistrationActive)
                    <div class="card registrationFormCard">

                        <form class="form-row g-1 card-body" wire:submit="register">
                            @csrf


                            <div class="col-12 form-row g-1">

                                <!-- qualification -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Qualification</label>
                                    <select class="form-control @error('selectedBoard') is-invalid @enderror"
                                        wire:model.live="selectedBoard">
                                        <option value="" style="font-size: 12px;">Select qualification...</option>
                                        @foreach (\App\Models\BoardAgencyStateModel::select('id', 'name')->get() as $board)
                                            <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedBoard')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- scholarship -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Scholarship Category</label>
                                    <select class="form-control @error('selectedScholarship') is-invalid @enderror"
                                        wire:model.live="selectedScholarship" wire:key="{{ $selectedBoard }}">
                                        <option value="">Select scholarship category...</option>
                                        @foreach (\App\Models\Gn_DisplayExamAgencyBoardUniversity::where('board_id', 'LIKE', '%' . $selectedBoard . '%')->with('educations')->get()->pluck('educations')->flatten()->unique('id') as $education)
                                            <option value="{{ $education->id }}">{{ $education->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedScholarship')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- state -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">State</label>
                                    <select class="form-control @error('selectedState') is-invalid @enderror"
                                        wire:model.live="selectedState">
                                        <option value="" style="font-size: 12px;">Select your state...</option>
                                        @foreach (\App\Models\State::select('id', 'name', 'status')->get() as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedState')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- distrcit -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">City/District</label>
                                    <select class="form-control @error('selectedDistrict') is-invalid @enderror"
                                        wire:model.live="selectedDistrict" wire:key="{{ $selectedState }}">
                                        <option value="">Select your city...</option>
                                        @foreach (\App\Models\District::where('state_id', $selectedState)->whereHas('districtScholarshipLimits', function ($query) use ($selectedScholarship) {
                                                $query->forEducationType($selectedScholarship);
                                            })->get() as $district)
                                            <option value="{{ $district->id }}"
                                                {{ intval($district->getLimit($selectedScholarship)->limit) == 0 ? 'disabled' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedDistrict')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if ($remainingForms <= 725)
                                    <div class="col-12">
                                        <label class="form-label mb-0">Referrence Code</label>
                                        <div class="input-group input-group-sm">
                                            <input
                                                class="form-control form-control-sm @error('couponcode') is-invalid @enderror"
                                                type="text" @if ($isCouponVerify) readonly @endif
                                                wire:model="couponcode" placeholder="Reference code by Institute"
                                                wire:key="{{ $selectedDistrict }}">
                                            <div class="input-group-append">
                                                <button
                                                    class="btn @if ($isCouponVerify) btn-success @else btn-outline-secondary @endif btn-sm"
                                                    id="button-addon2" type="button" wire:click="couponVerify">
                                                    @if ($isCouponVerify)
                                                        Verified
                                                    @else
                                                        Verify
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                        @error('couponcode')
                                            <small class="text-danger small">{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif

                                @error('customErrors')
                                    <small class="text-danger small">{{ $message }}</small>
                                @enderror

                                <div class="col-12 text-center">
                                    @if ($selectedState && $selectedDistrict && $remainingForms <= 725)
                                        <hr />
                                        <span class="text-danger">Only <b>{{ $remainingForms }}</b> Forms are remain
                                            for
                                            <b>{{ $selectedDistrictData?->name ?? 'N/A' }}</b></span>
                                    @endif
                                    <hr />
                                </div>


                                <!-- name -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Name</label>
                                    <input
                                        class="form-control form-control-sm @error('selectedBoard') is-invalid @enderror"
                                        type="text" wire:model.blur="name" placeholder="Full Name">
                                    @error('name')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- gender -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                        wire:model.blur="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">FeMale</option>
                                        <option value="Transgender">Transgender</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-row g-1">
                                        <!-- mobile -->
                                        <div class="col mb-2">
                                            <label class="form-label mb-0">Mobile</label>
                                            <div class="input-group">
                                                <input
                                                    class="form-control form-control-sm @error('mobile') is-invalid @enderror"
                                                    type="number" wire:model.live="mobile"
                                                    placeholder="Valid mobile number" min="6000000000" max="9999999990"
                                                    minlength="10" maxlength="10"
                                                    @if ($isOtpVerfied) readonly @endif>
                                                <div class="input-group-append">
                                                    <button type="button" @class(['btn btn-sm newButton', 'btn-success' => $isOtpVerfied])
                                                        wire:click="sendOTP"
                                                        @if ($isOtpVerfied) disabled @endif>
                                                        Get OTP
                                                    </button>
                                                </div>
                                            </div>
                                            @error('mobile')
                                                <small class="text-danger small">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <!-- OTP -->
                                        <div class="col mb-2">
                                            <label class="form-label mb-0">OTP</label>
                                            <div class="input-group">
                                                <input
                                                    class="form-control form-control-sm @error('userOtp') is-invalid @enderror @if ($isOtpVerfied) is-valid @endif"
                                                    type="number" wire:model.live="userOtp" id="student_otp_reg"
                                                    placeholder="Enter 6 Digits OTP" min="100000" max="999999"
                                                    minlength="6" maxlength="6"
                                                    @if (!$otpSendSuccess) disabled @endif
                                                    @if ($isOtpVerfied) readonly @endif>
                                                <div class="input-group-append">
                                                    <button type="button" @class(['btn btn-sm newButton', 'btn-success' => $isOtpVerfied])
                                                        wire:click="verifyOtp"
                                                        @if (!$otpSendSuccess) disabled @endif
                                                        @if ($isOtpVerfied) disabled @endif>
                                                        {{ $isOtpVerfied ? 'Verified' : 'Verify OTP' }}
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($otpSendSuccess && !$isOtpVerfied)
                                                <small class="text-success small">OTP successfully sent.</small>
                                            @endif
                                            @error('userOtp')
                                                <small class="text-danger small">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- email -->
                                <div class="col-12 mb-2">
                                    <label class="form-label mb-0">Email</label>
                                    <input class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        type="email" wire:model.blur="email" placeholder="Valid email address">
                                    @error('email')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- password -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Password</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control form-control-sm @error('password') is-invalid @enderror"
                                            type="{{ $showPassword ? 'text' : 'password' }}"
                                            wire:model.blur="password" placeholder="Password *">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                wire:click="$toggle('showPassword')">
                                                <i
                                                    class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- confirm password -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label mb-0">Confirm Password</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                            type="{{ $showPassword ? 'text' : 'password' }}"
                                            wire:model.blur="password_confirmation" placeholder="Confirm Password *">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                wire:click="$toggle('showPassword')">
                                                <i
                                                    class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-12 mb-2">
                                    <div class="d-flex">
                                        <label class="form-check-label mr-3">Person Disability:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" id="inlineCheckbox1" name="disability"
                                                type="radio" value="Yes" wire:model="disability">
                                            <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" id="inlineCheckbox2" name="disability"
                                                type="radio" value="No" wire:model="disability">
                                            <label class="form-check-label" for="inlineCheckbox2">No</label>
                                        </div>
                                    </div>
                                    @error('disability')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="form-group form-check mb-0">
                                        <input class="form-check-input text-start" id="termsCheckbox" type="checkbox"
                                            wire:model.live="terms">
                                        <label class="form-check-label d-inline-block" for="termsCheckbox">
                                            I accept the&nbsp;<a class="inline-block" href="/p/terms-and-conditions"
                                                style="text-decoration: underline;" target="_blank">Terms &
                                                Conditions</a>&nbsp;of Career without barrier
                                        </label>
                                    </div>
                                    @error('terms')
                                        <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-12 mb-2">
                                <button class="btn btn-secondary w-100" type="submit"
                                    style="background-color: #f73f05; border-color: #f73f05 !important;"
                                    @if (!$otpSendSuccess || !$isOtpVerfied) disabled @endif>
                                    <span class="spinner-border spinner-border-sm mr-3" role="status"
                                        aria-hidden="true" wire:loading wire:target="register"></span>
                                    Register
                                </button>
                            </div>
                            <div class="col-12 mb-2">
                                Already have an account? <a id="loginFormOpen" data-toggle="modal"
                                    data-target="#myModalLogin" href="#">Login Here</a>
                            </div>

                        </form>
                    </div>
                @else
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-5 text-center">
                            <div class="mb-4">
                                <span class="text-warning" style="font-size: 3rem;">
                                    <i class="fa fa-calendar-times-o"></i>
                                </span>
                            </div>
                            <h4 class="text-secondary mb-3">{!! $registrationMessage !!}</h4>
                            <p class="text-muted mb-4">For any queries, please feel free to reach out to us on our
                                contact page.</p>
                            <a class="btn btn-primary px-4 py-2" href="{{ route('home.contact') }}">
                                <i class="fa fa-envelope-o mr-2"></i> Contact Us
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@push('custom-styles')
    <style>
        small.small {
            font-size: 70%;
            font-weight: 500;
        }

        .registrationFormCard {
            border: 1px solid #ffc7b4 !important;
            border-radius: .6rem !important;
            box-shadow: 0 0 0 5px #ffd8ca;
        }

        .registrationFormCard input,
        .registrationFormCard select,
        .registrationFormCard .input-group button {
            border-color: #ffc7b4 !important;
        }

        .registrationFormCard input::placeholder,
        .registrationFormCard select {
            font-size: 12px;
        }
    </style>
@endpush
