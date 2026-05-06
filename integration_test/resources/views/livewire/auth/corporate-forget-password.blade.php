<div style="min-height: 450px;">

    <div class="d-flex align-items-center mb-5 pb-1">
        <span class="h1 fw-bold mb-0">
            <img class="img-thumnails" src="{{ asset('website/assets/images/fav-icon.png') }}" alt="login form"
                style="border-radius: 1rem 0 0 1rem;height:100%" />
        </span>
    </div>

    <h5 class="fw-normal mb-4 pb-3" style="letter-spacing: 1px;">
        To Reset your password. Please provide your registered Email
    </h5>

    @if (!$otpSendSuccess)
        <div class="col-12 form-row mb-5 g-1">
            <label class="form-label mb-0">Email</label>
            <div class="input-group">
                <input class="form-control form-control-sm @error('email') is-invalid @enderror" type="email"
                    wire:model.blur="email" placeholder="Valid email address" wire:loading.attr="readonly"
                    wire:target="sendOTP">
                <div class="input-group-append">
                    <button type="button" style="background: #1616c9;" @class(['btn btn-sm newButton btn-dark'])
                        wire:click="sendOTP" wire:loading.attr="disabled" wire:target="sendOTP">
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
        <div class="col-12 mb-3">
            <label class="form-label mb-0">OTP</label>
            <input class="form-control form-control-sm @error('opt') is-invalid @enderror" type="number"
                wire:model.live="opt" placeholder="Enter 6 Digits OTP" min="100000" max="999999" minlength="6"
                maxlength="6">
            @error('opt')
                <small class="text-danger small">{{ $message }}</small>
            @enderror
        </div>

        <div class="row mb-3">
            <!-- password -->
            <div class="col-md-6 mb-2">
                <label class="form-label mb-0">Password</label>
                <div class="input-group">
                    <input class="form-control form-control-sm @error('password') is-invalid @enderror"
                        type="{{ $showPassword ? 'text' : 'password' }}" wire:model.blur="password"
                        placeholder="Password *">
                    <div class="input-group-append">
                        <button class="btn btn-outline-dark" type="button" style="background-color: light-gray;"
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
                    <input class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                        type="{{ $showPassword ? 'text' : 'password' }}" wire:model.blur="password_confirmation"
                        placeholder="Confirm Password *">
                    <div class="input-group-append">
                        <button class="btn btn-outline-dark" type="button" style="background-color: light-gray;"
                            wire:click="$toggle('showPassword')">
                            <i class="fa fa-fw {{ $showPassword ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        </button>
                    </div>
                </div>
                @error('password_confirmation')
                    <small class="text-danger small">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-12 mb-3">
            <button class="btn btn-dark w-100" type="button"
                style="background-color: #1616c9; border-color: #1616c9 !important;" wire:click="setPassword">
                <span class="spinner-border spinner-border-sm mr-3" role="status" aria-hidden="true" wire:loading
                    wire:target="setPassword"></span>
                Submit
            </button>
        </div>
    @endif

    <a class="small text-muted mt-5" href="{{ route('corporatelogin') }}">back to
        login?</a>
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
