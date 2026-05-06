<div class="pop-up3">
    @csrf

    <button type="button" class="close" data-dismiss="modal">&times;</button>

    <h2 class="book-tit">New Corporate Enquiry</h2>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div id="errorMessages" class="text-danger"></div>

    <div class="books-now">
        <div class="contact__msg">Thank you.</div>
        <ul>
            <li class="full">
                <input type="text" name="name" id='name' placeholder="Your Name" class="form-control" required>
            </li>
            <li class="full">
                <input type="text" name="institute_name" placeholder="Institute/School/Brand Name" class="form-control" required>
            </li>
            <li class="half">
                <select class="form-control" name="type_institution" id="type_institution">
                    <option value="">Type of Institution</option>
                    <option value="Coaching Institute">Coaching Institute</option>
                    <option value="School (High School)">School (High School)</option>
                    <option value="School (Intermediate School)">School (Intermediate School)</option>
                    <option value="College (Degree College)">College (Degree College)</option>
                    <option value="Society, Trust">Society/ Trust</option>
                </select>
            </li>
            <li class="half">
                <select class="form-control" name="established_year" id="established_year">
                    <option value="">Established Year</option>
                    @for($i = 2001; $i <= now()->year; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                </select>
            </li>
            <li class="full" wire:ignore>
                <select class="js-example-basic-single w-100" name="interested_for[]" id="interested_for" multiple="multiple" placeholder="Interested For">
                    <option value="Institute/School welfare program">Institute/School welfare program</option>
                    <option value="Students Scholarship Program">Student’s Scholarship Program</option>
                    <option value="Society/Trust Welfare Program">Society/Trust Welfare Program</option>
                    <option value="Individual (Private Tuition) Welfare Program">Individual (Private Tuition) Welfare Program</option>
                </select>
            </li>

            <li class="half">
                <div class="input-group">
                    <input type="number" name="phone" id="phone" placeholder="Mobile No *" class="form-control" required>
                    <button class="btn bg-dark text-white append corporate_send_otp_btn" onclick="sendOtp('corporate','otp_send')" type="button" style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;">
                        Get Otp
                    </button>
                </div>
            </li>
            <li class="half">
                <div class="input-group">
                    <input type="text" name="otp" placeholder="otp Number" id="corporate_otp" title="Please enter valid otp" class="form-control" required>
                    <button class="btn bg-dark text-white append corporate_verify_otp_btn" onclick="sendOtp('corporate','otp_verify')" type="button" style="border-bottom-left-radius: 0;font-size: 14px;padding: 7px;border-top-left-radius: 0;">
                        Verfiy Otp
                    </button>
                </div>
            </li>
            <li class="full">
                <div class="input-with-button">
                    <input type="email" name="email" id="email" placeholder="Email id *" class="form-control" required>
                </div>
            </li>

            <li class="half">
                <select class="form-control" wire:model.live="state_id" name="state_id" id="state_id">
                    <option value="">State</option>
                    @foreach (\App\Models\State::select('id','name', 'status')->get() as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </li>
            <li class="half">
                <select class="form-control" wire:model="district_id" wire:key="{{ $state_id }}" name="district_id" id="district_id">
                    <option value="">District</option>
                    @foreach (\App\Models\District::where('state_id', $state_id)->get() as $district)
                    <option value="{{ $district->id }}"> {{ $district->name }}</option>
                    @endforeach
                </select>
            </li>
            <li class="half">
                <input type="text" name="address" id="address" placeholder="Address" class="form-control" required>
            </li>
            <li class="half">
                <input type="text" name="pincode" placeholder="Pincode" id="pincode" class="form-control" required>
            </li>

            <!-- <li class="half">
                                        <input type="text" name="city" id="city" placeholder="City *" class="form-control" required>
                                    </li> -->

            <li class="half">
                <label for="attachment">Person Image <small>JPEG/JPG/PNG</small></label>
                <input type="file" name="attachment" id="attachment" onchange="validateImage(this)" class="form-control">
            </li>
            <li class="half">
                <label for="attachment">Institute Image<small>JPEG/JPG/PNG/PDF</small></label>
                <input type="file" name="attachment_profile" id="attachment_profile" class="form-control" onchange="validateImage(this,'imagepdf')">
            </li>
            <li class="full" style="font-size: 13px;display:flex">
                <input type="checkbox" style="width: 20px;height:20px" name="privacy_policy" id="privacy_policy" required> &nbsp; I accept the &nbsp;
                @if($institudeTermsCondition) <a style="text-decoration: underline;" href="{{ asset('home/'.$institudeTermsCondition->terms_condition_pdf) }}" target="_blank"> Terms & Conditions </a>@endif

            </li>
            <li class="full">
                <input type="submit" name="submit" id="submitInstitude" value="Submit">
            </li>
        </ul>
    </div>
</div>