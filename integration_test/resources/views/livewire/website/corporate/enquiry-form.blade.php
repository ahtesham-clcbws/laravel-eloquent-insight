<div>
    <style>
        .select2 textarea {
            border-color: none !important;
            box-shadow: none !important;
            outline: 0 none;
        }

        .input-group input {
            border-end-end-radius: 0 !important;
            border-start-end-radius: 0 !important;
        }

        .input-group button {
            border-end-start-radius: 0 !important;
            border-start-start-radius: 0 !important;
        }

        .select2-search__field,
        .select2-search.select2-search--inline,
        .select2-selection.select2-selection--multiple,
        .select2-selection__rendered {
            border-color: #ffc7b4 !important;
        }

        .registrationFormCard select {
            padding: .375rem .50rem;
        }
    </style>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container text-center py-5 pb-4">
            <h2 style="font-size:32px" class="text-white">Collaboration Form</h2>
            <p class="text-white" style="font-size:20px">Only For Coaching Institutes, School/ Colleges, Trust/ Societies, Social Workers</p>
        </div>
    </div>


    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mx-auto card registrationFormCard">
                <div class="card-body">

                    <form class="form-row g-1" wire:submit="VerifyAndSubmit">
                        <div class="mb-2 col-12">
                            <input type="text" wire:model.change="name" id='name' placeholder="Your Name" class="form-control">
                            @error('name')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-12">
                            <input type="text" wire:model.change="institute_name" placeholder="Institute/School/Brand Name" class="form-control">
                            @error('institute_name')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <select class="form-control form-control-lg" wire:model.change="type_institution" id="type_institution">
                                <option value="">Type of Institution</option>
                                <option value="Coaching Institute">Coaching Institute</option>
                                <option value="School (High School)">School (High School)</option>
                                <option value="School (Intermediate School)">School (Intermediate School)</option>
                                <option value="College (Degree College)">College (Degree College)</option>
                                <option value="Society, Trust">Society/ Trust</option>
                            </select>
                            @error('type_institution')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <select class="form-control form-control-lg" wire:model.change="established_year" id="established_year">
                                <option value="">Established Year</option>
                                @for($i = now()->year; $i >= 2001; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('established_year')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-12">
                            <div class="form-control" style="height: auto !important;">
                                <label class="form-control-label"><b>Interested For: </b></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model.change="interested_for" id="school_welfare_program" value="Institute/School welfare program">
                                    <label class="form-check-label" for="school_welfare_program">Institute/School welfare program</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model.change="interested_for" id="student_scholarship_program" value="Students Scholarship Program">
                                    <label class="form-check-label" for="student_scholarship_program">Students Scholarship Program</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model.change="interested_for" id="society_welfare_program" value="Society/Trust Welfare Program">
                                    <label class="form-check-label" for="society_welfare_program">Society/Trust Welfare Program</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model.change="interested_for" id="private_welfare_program" value="Individual (Private Tuition) Welfare Program">
                                    <label class="form-check-label" for="private_welfare_program">Individual (Private Tuition) Welfare Program</label>
                                </div>
                            </div>
                            @error('interested_for')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <div class="form-row g-1">
                                <!-- phone number -->
                                <div class="mb-2 col">
                                    <div class="input-group">
                                        <input type="number" wire:model.live="phone" placeholder="Valid phone number" class="form-control @error('phone') is-invalid @enderror" min="6000000000" max="9999999990" minlength="10" maxlength="10" @if($isOtpVerfied) readonly @endif>
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary btn-sm" style="background-color: #f73f05; border-color: #f73f05 !important;" type="button" wire:click="sendOTP" @if($isOtpVerfied) disabled @endif>
                                                Get OTP
                                            </button>
                                        </div>
                                    </div>
                                    @error('phone')
                                    <small class="text-danger small">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- OTP -->
                                <div class="mb-2 col">
                                    <div class="input-group">
                                        <input type="number" wire:model.live="userOtp" placeholder="Enter 6 Digits OTP" class="form-control @error('userOtp') is-invalid @enderror @if($isOtpVerfied) is-valid @endif" min="100000" max="999999" minlength="6" maxlength="6" @if (!$otpSendSuccess) disabled @endif @if($isOtpVerfied) readonly @endif>
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary btn-sm" style="background-color: #f73f05; border-color: #f73f05 !important;" type="button" wire:click="verifyOtp" @if (!$otpSendSuccess) disabled @endif @if($isOtpVerfied) disabled @endif>
                                                Verify OTP
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
                            

                        <div class="mb-2 col-12">
                            <div class="input-with-button">
                                <input type="email" wire:model.change="email" id="email" placeholder="Email id *" class="form-control">
                            </div>
                            @error('email')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">
                            <select class="form-control form-control-lg" wire:model.live="state_id" id="state_id">
                                <option value="">State</option>
                                @foreach (\App\Models\State::select('id','name', 'status')->get() as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <select class="form-control form-control-lg" wire:model="district_id" wire:key="{{ $state_id }}" name="district_id" id="district_id">
                                <option value="">District</option>
                                @foreach (\App\Models\District::where('state_id', $state_id)->get() as $district)
                                <option value="{{ $district->id }}"> {{ $district->name }}</option>
                                @endforeach
                            </select>
                            @error('district_id')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <input type="text" wire:model.change="address" id="address" placeholder="Address" class="form-control">
                            @error('address')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <input type="text" wire:model.change="pincode" placeholder="Pincode" id="pincode" class="form-control">
                            @error('pincode')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">
                            <label for="attachment" class="mb-0">Person Image</label>
                            <input type="file" wire:model.change="attachment" id="attachment" class="form-control form-control-sm" accept=".jpeg,.jpg,.png">
                            @error('attachment')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">
                            <label for="attachment_profile" class="mb-0">Institute Image PDF</label>
                            <input type="file" wire:model.change="attachment_profile" id="attachment_profile" class="form-control form-control-sm" accept=".pdf">
                            @error('attachment_profile')
                            <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12 mb-2">
                            <div class="form-group form-check mb-0">
                                <input class="form-check-input text-start" type="checkbox"
                                    wire:model.change="privacy_policy" id="privacy_policy">
                                <label class="form-check-label d-inline-block" for="termsCheckbox">
                                    I accept the&nbsp;<a class="inline-block" href="/p/terms-and-conditions"
                                        style="text-decoration: underline;" target="_blank">Terms &
                                        Conditions</a>
                                </label>
                            </div>
                            @error('privacy_policy')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2 col-12">
                            <button type="submit" class="btn-custom w-100 d-flex justify-content-center align-items-center" @if (!$otpSendSuccess || !$isOtpVerfied) disabled @endif>
                                Submit
                            </button>
                        </div>
                    </form>
                    
                </div>
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