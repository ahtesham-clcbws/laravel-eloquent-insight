<?php

namespace App\Livewire\Corporate;

use App\Models\Corporate;
use App\Services\Msg91Service;
use App\Notifications\AnyUserEmailVerify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('corporate.layouts.master')]
class Profile extends Component
{
    use WithFileUploads;

    public Corporate $corporate;

    public $name;

    #[Validate('image', message: 'The photo must be an image file.')]
    #[Validate('mimes:jpeg,png', message: 'The photo must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'File size must not exceed 2MB.')]
    public $photo;

    #[Validate('min_digits:10', message: 'Mobile number must minimum 10 digits')]
    #[Validate('max_digits:10', message: 'Mobile number have maximum 10 digits')]
    #[Validate('unique:corporates,phone', message: 'Mobile number is already in use')]
    public $new_phone;
    public $newPhoneVerified = false;
    public $changePhoneNumber = false;

    #[Validate('email', message: 'Please enter valid email address')]
    #[Validate('unique:corporates,email', message: 'Email address is already in use')]
    public $new_email;
    public $newEmailVerified = false;
    public $changeEmailAddress = false;

    public $user_otp;
    public $email_code;

    public function mount()
    {
        $corporateUser = Auth::guard('corporate')->user();
        $this->corporate = \App\Models\Corporate::find($corporateUser->id);

        $this->name = $this->corporate->name;

        $this->new_phone = $this->corporate->phone;
        $this->new_email = $this->corporate->email;

        $this->newPhoneVerified = $this->corporate->is_otp_verified;
        $this->newEmailVerified = $this->corporate->email_verified_at;
    }
    public function render()
    {
        return view('livewire.corporate.profile');
    }

    public function cancelUpload()
    {
        $this->js('window.location.reload()');
        try {
            $this->photo = null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function uploadImage()
    {
        $this->validate([
            'photo' => 'image|max:2048'
        ]);

        $attachment = $this->photo->store('corporate-attachment', 'public');

        $this->corporate->attachment = $attachment;
        $this->corporate->save();

        $this->photo = null;

        $this->js('success("Profile picture updated successfully")');
        $this->js('window.location.reload()');
    }

    public function updateName()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255'
        ]);

        $this->corporate->name = $this->name;
        $this->corporate->save();

        $this->js('success("Name updated successfully")');
    }

    public function togglePhoneChange()
    {
        if ($this->changePhoneNumber) {
            $this->changePhoneNumber = false;
            $this->new_phone = $this->corporate->phone;
        } else {
            $this->changePhoneNumber = true;
        }
    }
    public function updatePhone()
    {
        $this->validate([
            'new_phone' => 'required|numeric|digits:10'
        ]);

        $otp = rand(100000, 999999);
        $smsSent = app(Msg91Service::class)->sendSms($this->new_phone, $otp);

        if ($smsSent) {
            $this->corporate->new_phone = $this->new_phone;
            $this->corporate->is_otp_verified = false;
            $this->corporate->save();

            $this->otpVerifyModalInputOpen = true;
            $this->changePhoneNumber = false;

            $this->js('success("OTP sent to your new phone number. Please verify.")');
        } else {
            $this->js('error("Failed to send OTP. Please try again.")');
        }
    }
    public function keepOldPhone()
    {
        $this->corporate->new_phone = null;
        $this->corporate->is_otp_verified = true;
        $this->corporate->save();
    }
    public $otpVerifyModalInputOpen = false;
    public function verifyPhone()
    {
        if (verifyOtp($this->user_otp, $this->corporate->new_phone)) {
            $this->corporate->phone = $this->corporate->new_phone;
            $this->corporate->new_phone = null;
            $this->corporate->is_otp_verified = true;
            $this->corporate->save();

            $this->new_phone = $this->corporate->phone;
            $this->otpVerifyModalInputOpen = false;

            $this->js('success("Phone number verified successfully")');
        } else {
            $this->addError('user_otp', 'Invalid OTP');
            $this->js('error("Invalid OTP")');
        }
    }

    public function toggleEmailChange()
    {
        if ($this->changeEmailAddress) {
            $this->changeEmailAddress = false;
            $this->new_email = $this->corporate->email;
        } else {
            $this->changeEmailAddress = true;
        }
    }
    public function updateEmail()
    {
        $this->validate([
            'new_email' => 'required|email'
        ]);

        $emailCode = rand(100000, 999999);

        $this->corporate->new_email = $this->new_email;
        $this->corporate->email_code = $emailCode;
        $this->corporate->save();

        $this->toggleEmailChange();

        Notification::route('mail', $this->corporate->new_email)->notify(new AnyUserEmailVerify($this->corporate, $emailCode));

        $this->js('success("Email updated successfully, please verify your email")');
    }
    public function keepOldEmail()
    {
        $this->corporate->new_email = null;
        $this->corporate->save();
    }
    public $emailVerifyModalInputOpen = false;
    public function verifyEmail()
    {
        if ($this->email_code == $this->corporate->email_code) {
            $this->corporate->email = $this->corporate->new_email;
            $this->corporate->email_verified_at = Carbon::now();
            $this->corporate->save();

            $this->new_email = $this->corporate->email;

            $this->emailVerifyModalInputOpen = false;

            $this->js('success("Email address verified successfully")');
        } else {
            $this->addError('email_code', 'Invalid OTP');
            $this->js('error("Invalid OTP")');
        }
    }
}
