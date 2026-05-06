<div>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container text-center py-5 pb-4">
            <h2 style="font-size:32px" class="text-white">Student Registration</h2>
        </div>
    </div>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mx-auto card registrationFormCard">
                <form class="form-row g-1 card-body" wire:submit="register">
                    @csrf
                    <div class="col-12 form-row g-1 {{ $this->otpSendSuccess && $this->otpRequestId ? 'd-none' : '' }} ">

                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Qualification</label>
                            <select class="form-control" wire:model.live="selectedBoard">
                                <option style="font-size: 12px;" value="">Select qualification...</option>
                                @foreach (\App\Models\BoardAgencyStateModel::select('id','name')->get() as $board)
                                <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Scholarship Category</label>
                            <select class="form-control" wire:model.live="selectedScholarship" wire:key="{{ $selectedBoard }}">
                                <option value="">Select scholarship category...</option>
                                @foreach ((\App\Models\Gn_DisplayExamAgencyBoardUniversity::where('board_id', 'LIKE', '%' . $selectedBoard . '%')->with('educations')->get())->pluck('educations')->flatten()->unique('id') as $education)
                                <option value="{{ $education->id }}">{{ $education->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">State</label>
                            <select class="form-control" wire:model.live="selectedState">
                                <option style="font-size: 12px;" value="">Select your state...</option>
                                @foreach (\App\Models\State::select('id','name', 'status')->get() as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">City/District</label>
                            <select class="form-control" wire:model.live="selectedDistrict" wire:key="{{ $selectedState }}">
                                <option value="">Select your city...</option>
                                @foreach (\App\Models\District::where('state_id', $selectedState)
                                ->whereHas('districtScholarshipLimits', function ($query) use ($selectedScholarship) {
                                $query->forEducationType($selectedScholarship);
                                })->get() as $district)

                                <option {{ intval($district->getLimit($selectedScholarship)->limit) == 0 ? 'disabled' : '' }} value="{{ $district->id }}">
                                    {{ $district->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 text-center">
                            @if($selectedState && $selectedDistrict && $selectedDistrictData)
                            @php
                            $data = $selectedDistrictData->getLimit($selectedScholarship);
                            $limit = $data->limit;
                            $remaining = $data->remaining;
                            $needReferrenceCode = $remaining < 300 ? true : false;
                            @endphp
                                @if ($remaining < 300)
                                <hr />
                                <span class="text-danger">Only <b>{{ $remaining }}</b> Forms are remain for <b>{{ $selectedDistrictData->name }}</b></span>
                                @endif
                            @endif
                            <hr />
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Name</label>
                            <input type="text" wire:model="name" placeholder="Full Name" class="form-control form-control-sm @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Gender</label>
                            <select wire:model="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">FeMale</option>
                                <option value="Transgender">Transgender</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Mobile</label>
                            <input type="number" pattern="[6-9]{1}[0-9]{9}" wire:model.live="mobile" placeholder="Valid mobile number" class="form-control form-control-sm @error('mobile') is-invalid @enderror" required min="6000000000" max="9999999990" minlength="10" maxlength="10">
                            @error('mobile')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Email</label>
                            <input type="email" wire:model.live="email" placeholder="Valid email address" class="form-control form-control-sm @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Password</label>
                            <div class="input-group">
                                <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password" placeholder="Password *" class="form-control form-control-sm @error('password') is-invalid @enderror" required minlength="8">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" wire:click="$toggle('showPassword')">
                                        <i class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-0">Confirm Password</label>
                            <div class="input-group">
                                <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="confirmPassword" placeholder="Confirm Password *" class="form-control form-control-sm @error('confirmPassword') is-invalid @enderror" required minlength="8">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" wire:click="$toggle('showPassword')">
                                        <i class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>
                                </div>
                            </div>
                            @error('confirmPassword')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        @if ($needReferrenceCode)
                        <div class="mb-3 col-12">
                            <label class="form-label mb-0">Referrence Code</label>
                            <input type="text" wire:model.live="referrenceCode" placeholder="Reference code by Institute" class="form-control form-control-sm @error('referrenceCode') is-invalid @enderror {{ !$referrenceCodeError && $referrenceCodeValidated ? 'is-valid' : '' }}" required>
                            @error('referrenceCode')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        @endif

                        <div class="mb-3 col-12">
                            <div class="d-flex">
                                <label class="form-check-label mr-3">Person Disability:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="inlineCheckbox1" wire:model="disability" name="disability" value="Yes" required>
                                    <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="inlineCheckbox2" wire:model="disability" name="disability" value="No" required>
                                    <label class="form-check-label" for="inlineCheckbox2">No</label>
                                </div>
                            </div>
                        </div>

                        @if($institudeTermsCondition)
                        <div class="mb-3 col-12">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input <?= !$termsError ?? 'is-invalid' ?>" id="termsCheckbox" wire:model="terms" required>
                                <label class="form-check-label" for="termsCheckbox">
                                    I accept the &nbsp;<a style="text-decoration: underline;" href="{{ asset('home/'.$institudeTermsCondition) }}" target="_blank"> Terms & Conditions </a>&nbsp; of Career without barrier.
                                </label>
                            </div>
                            @error('terms')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        @endif
                    </div>

                    <div class="mb-3 col-12 {{ $this->otpSendSuccess && $this->otpRequestId ? '' : 'd-none' }} ">
                        <label class="form-label mb-0 text-center d-block"><b>Enter OTP, you recieved on Mobile number!</b></label>
                        <input type="number" wire:model="userOtp" id="student_otp_form" class="form-control form-control-lg text-center @error('userOtp') is-invalid @enderror" placeholder="_ _ _ _ _ _" min="100000" max="999999" minlength="6" maxlength="6">
                        @error('userOtp')
                            <div class="invalid-feedback text-center">{{ $message }}</div>
                        @enderror
                        <div class="text-center mt-2">
                            <button type="button" class="btn btn-link btn-sm text-secondary" wire:click="$set('otpSendSuccess', false)">Change mobile number?</button>
                        </div>
                    </div>

                    <div class="mb-3 col-12">
                        <button type="submit" class="btn-custom w-100 d-flex justify-content-center align-items-center" style="background-color: #f73f05; border-color: #f73f05; color: white; padding: 12px; border-radius: 8px;">
                            <span class="spinner-border spinner-border-sm mr-3" wire:loading wire:target="register" role="status" aria-hidden="true"></span>
                            {{ $this->otpSendSuccess && $this->otpRequestId ? 'Verify & Register' : 'Send OTP' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('custom-styles')
<style>
    .small {
        font-size: 80%;
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