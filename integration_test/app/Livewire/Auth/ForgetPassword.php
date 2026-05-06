<?php

namespace App\Livewire\Auth;

use App\Models\OtpVerifications;
use App\Models\Student;
use App\Notifications\ForgetPasswordOtp;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgetPassword extends Component
{

    #[Validate('email|exists:students,email')]
    public $email;
    public $otp;
    public $password;
    public $password_confirmation;

    public $otpSendSuccess = false;

    public $showPassword = false;

    public function render()
    {
        return view('livewire.auth.forget-password');
    }

    public function sendOTP()
    {
        $validated = $this->validateOnly('email');
        if ($validated) {
            $otp = rand(123456, 998989);
            OtpVerifications::where('type', 'email')->where('credential', $this->email)->delete();
            $otpCreated = OtpVerifications::create([
                'type' => 'email',
                'credential' => $this->email,
                'otp' => $otp,
            ]);

            if ($otpCreated) {
                $this->otp = $otp;
                // send user email of the OTP from ForgetPasswordOtp
                $student = Student::where('email', $this->email)->first();
                $student->notify(new ForgetPasswordOtp($otp));
                $this->js('toastr.success("OTP send successfully, please check your email.")');
                $this->otpSendSuccess = true;
            }
        } else {
            $this->js('toastr.error("Email not valid.")');
        }
    }
    public function setPassword()
    {
        $this->validate([
            'otp' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password'
        ]);

        if (verifyOtp($this->otp, $this->email)) {
            $student = Student::where('email', $this->email)->first();
            $student->update([
                'password' => Hash::make($this->password),
                'login_password' => $this->password,
            ]);
            $this->js('toastr.success("Password updated successfully.")');
            $this->redirect('/');
        } else {
            $this->addError('otp', 'OTP not valid.');
        }
    }
}
