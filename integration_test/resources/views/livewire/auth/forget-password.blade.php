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
            <h2 class="text-white" style="font-size:32px">Forget Password</h2>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 card forgetPasswordFormCard mx-auto">

                <div class="form-row g-1 card-body">
                    @csrf

                    @if (!$otpSendSuccess)
                        <div class="col-12 form-row g-1">
                            <label class="form-label mb-0">Email</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm @error('email') is-invalid @enderror"
                                    type="email" wire:model.blur="email" placeholder="Valid email address"
                                    wire:loading.attr="readonly" wire:target="sendOTP">
                                <div class="input-group-append">
                                    <button type="button" @class(['btn btn-sm newButton']) wire:click="sendOTP"
                                        wire:loading.attr="disabled" wire:target="sendOTP">
                                        <span wire:loading wire:target="sendOTP">Sending ...</span>
                                        <span wire:loading.remove wire:target="sendOTP">Get OTP</span>
                                    </button>
                                </div>
                            </div>
                            @error('email')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                    @else
                        <div class="col-12 mb-2">
                            <label class="form-label mb-0">OTP</label>
                            <input class="form-control form-control-sm @error('opt') is-invalid @enderror"
                                type="number" wire:model.live="opt" placeholder="Enter 6 Digits OTP" min="100000"
                                max="999999" minlength="6" maxlength="6">
                            @error('opt')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- password -->
                        <div class="col-md-6 mb-2">
                            <label class="form-label mb-0">Password</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    type="{{ $showPassword ? 'text' : 'password' }}" wire:model.blur="password"
                                    placeholder="Password *">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                        wire:click="$toggle('showPassword')">
                                        <i class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
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
                                        <i class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <button class="btn btn-secondary w-100" type="button"
                                style="background-color: #f73f05; border-color: #f73f05 !important;" wire:click="setPassword">
                                <span class="spinner-border spinner-border-sm mr-3" role="status" aria-hidden="true"
                                    wire:loading wire:target="setPassword"></span>
                                Submit
                            </button>
                        </div>
                    @endif

                    {{-- <div class="col-12 mb-2 text-end">
                        <a id="loginFormOpen" data-toggle="modal" data-target="#myModalLogin" href="#">Back to
                            login?</a>
                    </div> --}}

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
