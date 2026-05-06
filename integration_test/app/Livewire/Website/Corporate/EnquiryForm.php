<?php

namespace App\Livewire\Website\Corporate;

use App\Mail\InstituteRequestForCollaboration;
use App\Models\Corporate;
use App\Models\OtpVerifications;
use App\Services\Msg91Service;
use App\Models\TermsCondition;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EnquiryForm extends Component
{
    use WithFileUploads;

    public $institudeTermsCondition = null;

    #[Validate('required', message: 'Please enter your name.')]
    #[Validate('min:5', message: 'Name must be minimum of 5 characters.')]
    #[Validate('max:250', message: 'Name must not exceeds 250 characters.')]
    public $name;

    #[Validate('required', message: 'Please enter Institute name.')]
    #[Validate('min:5', message: 'Institute name must be minimum of 5 characters.')]
    #[Validate('max:250', message: 'Institute name must not exceeds 250 characters.')]
    public $institute_name;

    #[Validate('required', message: 'Please select type of institution.')]
    public $type_institution;

    #[Validate('required', message: 'Please select establishment year.')]
    public $established_year;

    #[Validate('required', message: 'Please select your interest.')]
    public $interested_for = [];

    #[Validate('required', message: 'Please enter your phone number.')]
    #[Validate('min_digits:10', message: 'Phone number must be minimum of 10 digits.')]
    #[Validate('max_digits:10', message: 'Phone number must not exceeds 10 digits.')]
    #[Validate('unique:corporates,phone', message: 'Phone number is already in use')]
    public $phone;

    #[Validate('required', message: 'Please enter your email.')]
    #[Validate('email', message: 'Please enter valid email address')]
    #[Validate('unique:corporates,email', message: 'Email address is already in use')]
    public $email;

    #[Validate('required', message: 'Please select state.')]
    public $state_id;

    #[Validate('required', message: 'Please select district.')]
    public $district_id;

    #[Validate('required', message: 'Please enter your institute address.')]
    #[Validate('min:15', message: 'Address must be minimum of 15 characters.')]
    #[Validate('max:250', message: 'Address must not exceeds 250 characters.')]
    public $address;

    #[Validate('required', message: 'Please enter pincode.')]
    #[Validate('min_digits:6', message: 'Pincode must be minimum of 6 digits.')]
    #[Validate('max_digits:6', message: 'Pincode must not exceeds 6 digits.')]
    public $pincode;

    #[Validate('required', message: 'Please select contact person image.')]
    #[Validate('image', message: 'Image must be an image file.')]
    #[Validate('mimes:jpeg,png', message: 'Image must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'Image size must not exceed 2MB.')]
    public $attachment;

    #[Validate('required', message: 'Please select institute images pdf.')]
    #[Validate('mimes:pdf', message: 'Institute images must be in PDF format.')]
    #[Validate('max:2048', message: 'PDF size must not exceed 2MB.')]
    public $attachment_profile;

    #[Validate('required', message: 'Please accept our terms and conditions.')]
    public $privacy_policy;

    public $otpRequestId = '';
    public $otpSendSuccess = false;

    #[Validate('required', message: 'Please enter OTP')]
    #[Validate('required', message: 'OTP is required.')]
    public $userOtp;

    public $isOtpVerfied = false;

    public function mount()
    {
        $this->institudeTermsCondition = TermsCondition::where([['status', 1], ['type', 'institute'], ['page_name', 'terms-and-condition']])->first();
    }

    public function render()
    {
        return view('livewire.website.corporate.enquiry-form');
    }

    public function sendOTP()
    {
        $validated = $this->validateOnly('phone');
        if ($validated) {
            $otp = rand(100000, 999999);
            $smsSent = app(Msg91Service::class)->sendSms($this->phone, $otp);

            if ($smsSent) {
                // The service already saves to OtpVerifications, but we need the record for otpRequestId
                $otpCreated = OtpVerifications::where('credential', $this->phone)->where('otp', $otp)->latest()->first();
                if ($otpCreated) {
                    $this->otpRequestId = $otpCreated->id;
                    $this->otpSendSuccess = true;
                }
                $this->js('toastr.success("OTP send successfully, please check your phone.")');
            } else {
                $this->js('toastr.error("Failed to send OTP. Please try again.")');
            }
        } else {
            $this->js('toastr.error("Phone number, id not valid.")');
        }
    }

    public function verifyOtp()
    {
        if (verifyOtp($this->userOtp, $this->phone)) {
            $this->isOtpVerfied = true;
        } else {
            $this->addError('userOtp', 'Enter correct OTP.');
            return false;
        }
    }

    public function VerifyAndSubmit()
    {
        $this->validate();

        if (!$this->otpRequestId || !$this->otpSendSuccess) {
            $this->addError('phone', 'Please verify your phone number.');
            return false;
        }
        if (!$this->isOtpVerfied) {
            $this->addError('userOtp', 'OTP is not valid, Please check again.');
            return false;
        }

        try {
            $institute = new Corporate();

            $institute->name = $this->name;
            $institute->institute_name = $this->institute_name;
            $institute->type_institution = $this->type_institution;
            $institute->established_year = $this->established_year;
            $institute->interested_for = implode(',', $this->interested_for);

            $institute->phone = $this->phone;
            $institute->email = $this->email;

            $institute->state_id = $this->state_id;
            $institute->district_id = $this->district_id;

            $institute->address = $this->address;
            $institute->pincode = $this->pincode;

            $institute->attachment = $this->attachment->store('corporate-attachment', 'public');
            $institute->attachment_profile = $this->attachment_profile->store('corporate-attachment', 'public');

            $institute->is_otp_verified = true;
            $institute->save();

            $data = [
                'name' => $institute->name,
                'institute_name' => $institute->institute_name,
                'email' => $institute->email,
                'city' => $institute->district?->name ? $institute->district->name : null,
            ];
            Mail::to($institute)->send(new InstituteRequestForCollaboration($data));

            $this->js("toastr.success('Corporate inquiry submitted successfully!')");
            // $this->reset();
            $this->js("window.location.href = '/'");
        } catch (\Throwable $th) {
            // throw $th;
            $this->otpRequestId = '';
            $this->otpSendSuccess = false;

            logger('Registration Failed:', [$th]);
            $this->js('toastr.error(' . $th->getMessage() . ')');
            $this->js("toastr.error('Unable to submitted enquiry, try after some time.')");
            return false;
        }
    }
}
