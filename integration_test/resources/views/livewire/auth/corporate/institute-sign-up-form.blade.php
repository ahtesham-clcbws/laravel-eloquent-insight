<div class="card" style="border-radius: 1rem;">
    <div class="row g-0">
        <div class="col-md-6 col-lg-5 d-none d-md-block">

            <img src="https://www.21kschool.com/blog/wp-content/uploads/2021/01/rptgtpxd-1396254731.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;height:100%" />
        </div>
        <div class="col-md-6 col-lg-7 d-flex align-items-center">
            <div class="card-body p-4 p-lg-5 text-black">

                <form wire:submit="signUp">
                    @csrf
                    <div class="d-flex align-items-center mb-3 pb-1">
                        <span class="h1 fw-bold mb-0">
                            <img src="{{ asset('website/assets/images/fav-icon.png') }}" alt="Signup form" class="img-thumnails" style="border-radius: 1rem 0 0 1rem;height:100%" />
                        </span>
                    </div>

                    <div class="form-outline mb-3">
                        <label class="form-label" for="institude_code">Branch code<span class="text-danger"> *</span></label>
                        <input type="text" id="institude_code" wire:model.live="institude_code" placeholder="Branch code" title="Please enter valid Branch code" class="form-control">
                        @error('institude_code')
                        <div class="text-danger small">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 col">
                            <div class="form-outline mb-3">
                                <label class="form-label" for="phone">Phone Number<span class="text-danger"> *</span></label>
                                <input type="tel" id="phone" wire:model.live="phone" placeholder="Enter Phone number" title="Please enter valid Phone number" class="form-control">
                                @error('phone')
                                <div class="text-danger small">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col">
                            <div class="form-outline mb-3">
                                <label class="form-label" for="form2Example17">Email ID<span class="text-danger"> *</span></label>
                                <input type="text" wire:model.live="email" placeholder="Email ID" title="Please enter valid Email ID" class="form-control">
                                @error('email')
                                <div class="text-danger small">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col">
                            <div class="form-outline mb-3">
                                <label class="form-label" for="form2Example27">Password<span class="text-danger"> *</span></label>
                                <div style="display: flex;">
                                    <input type="password" wire:model.live="password" placeholder="Password " class="form-control">
                                    <i toggle="#password-field" style="cursor: pointer;margin-left: -31px;margin-top: 10px;" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                </div>
                                @error('password')
                                <div class="text-danger small">{{$message}}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6 col">
                            <div class="form-outline mb-3">
                                <label class="form-label" for="password_confirmation">Confirm Password<span class="text-danger"> *</span></label>
                                <div style="display: flex;">
                                    <input type="password" wire:model.live="password_confirmation" id="password_confirmation" placeholder="Confirm Password " class="form-control">
                                    <i toggle="#password-field" style="cursor: pointer;margin-left: -31px;margin-top: 10px;" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                </div>
                                @error('password_confirmation')
                                <div class="text-danger small">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-12 mb-3">
                            <?php

                            use Illuminate\Support\Facades\DB;

                            $institudeTermsCondition = DB::table('terms_conditions')->where([['status', 1], ['type', 'institute'], ['page_name', 'terms-and-condition']])->first();
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="terms" id="terms">
                                <label class="form-check-label" for="terms">
                                    I accept the @if($institudeTermsCondition) <a style="text-decoration: underline;" href="{{ asset('home/'.$institudeTermsCondition->terms_condition_pdf) }}" target="_blank"> Terms & Conditions </a>@else Terms & Conditions @endif
                                </label>
                            </div>
                            @error('terms')
                            <div class="text-danger small">{{$message}}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="pt-1 mb-3">
                        <button class="btn btn-dark btn-md btn-block" style="width: 100%;background: #1616c9;font-weight: 700;" type="submit">
                            <span class="spinner-border spinner-border-sm mr-3" wire:loading wire:target="signUp" role="status" aria-hidden="true"></span>Signup
                        </button>
                    </div>

                    <div class="col-12">
                        Already have an account? <a href="{{ route('corporatelogin') }}">Login Here</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>