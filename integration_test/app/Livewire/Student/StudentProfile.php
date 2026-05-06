<?php

namespace App\Livewire\Student;

use App\Models\Student;
use App\Services\Msg91Service;
use App\Notifications\AnyUserEmailVerify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('student.layouts.master')]
class StudentProfile extends Component
{
    use WithFileUploads;

    public Student $student;

    #[Validate('required', message: 'Name is required')]
    #[Validate('min:3', message: 'Name must have minimum 3 characters')]
    #[Validate('max:255', message: 'Name does not exceed 255 characters')]
    public $name;

    #[Validate('image', message: 'The photo must be an image file.')]
    #[Validate('mimes:jpeg,png', message: 'The photo must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'File size must not exceed 2MB.')]
    public $photo;

    #[Validate('required', message: 'Please select Gender')]
    public $gender;

    #[Validate('required', message: 'Disability is required')]
    #[Validate('in:Yes,No', message: 'Please choose disability')]
    public $disability = 'No';

    #[Validate('min_digits:10', message: 'Mobile number must minimum 10 digits')]
    #[Validate('max_digits:10', message: 'Mobile number have maximum 10 digits')]
    #[Validate('unique:students,mobile', message: 'Mobile number is already in use')]
    public $new_mobile;
    public $newMobileVerified = false;
    public $changeMobileNumber = false;

    #[Validate('email', message: 'Please enter valid email address')]
    #[Validate('unique:students,email', message: 'Email address is already in use')]
    public $new_email;
    public $newEmailVerified = false;
    public $changeEmailAddress = false;

    public $user_otp;
    public $email_code;


    public function mount()
    {
        $studentUser = Auth::guard('student')->user();
        $this->student = Student::find($studentUser->id);

        $this->name = $this->student->name;

        $this->gender = $this->student->gender;

        $this->disability = $this->student->disability;

        $this->new_mobile = $this->student->mobile;
        $this->new_email = $this->student->email;

        $this->newMobileVerified = $this->student->is_otp_verified;
        $this->newEmailVerified = $this->student->email_verified_at;
    }

    public function render()
    {
        return view('livewire.student.student-profile');
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

        $photograph = $this->photo->store('student/' . $this->student->id, 'public');

        $this->student->photograph = $photograph;
        $this->student->save();

        $this->photo = null;

        $this->js('success("Profile picture updated successfully")');
        $this->js('window.location.reload()');
    }

    public function updateName()
    {
        $this->validate([
            'name' => 'required|min:3|max:255'
        ]);

        $this->student->name = $this->name;
        $this->student->save();

        $this->js('success("Name updated successfully")');
    }
    public function updateGender()
    {
        $this->validate([
            'gender' => 'required'
        ]);

        $this->student->gender = $this->gender;
        $this->student->save();

        $this->js('success("Gender updated successfully")');
    }
    public function updateDisability()
    {
        $this->validate([
            'disability' => 'required|in:Yes,No'
        ]);

        $this->student->disability = $this->disability;
        $this->student->save();

        $this->js('success("Disability updated successfully")');
    }



    public function toggleMobileChange()
    {
        if ($this->changeMobileNumber) {
            $this->changeMobileNumber = false;
            $this->new_mobile = $this->student->mobile;
        } else {
            $this->changeMobileNumber = true;
        }
    }
    public function updateMobile()
    {
        $this->validate([
            'new_mobile' => 'required|numeric|digits:10'
        ]);

        $otp = rand(100000, 999999);
        $smsSent = app(Msg91Service::class)->sendSms($this->new_mobile, $otp);

        if ($smsSent) {
            $this->student->new_mobile = $this->new_mobile;
            $this->student->is_otp_verified = false;
            $this->student->save();

            $this->otpVerifyModalInputOpen = true;
            $this->changeMobileNumber = false;

            $this->js('success("OTP sent to your new mobile number. Please verify.")');
        } else {
            $this->js('error("Failed to send OTP. Please try again.")');
        }
    }
    public function keepOldMobile()
    {
        $this->student->new_mobile = null;
        $this->student->is_otp_verified = true;
        $this->student->save();
    }
    public $otpVerifyModalInputOpen = false;
    public function verifyMobile()
    {
        if (verifyOtp($this->user_otp, $this->student->new_mobile)) {
            $this->student->mobile = $this->student->new_mobile;
            $this->student->new_mobile = null;
            $this->student->is_otp_verified = true;
            $this->student->save();

            $this->new_mobile = $this->student->mobile;
            $this->otpVerifyModalInputOpen = false;

            $this->js('success("Mobile number verified successfully")');
        } else {
            $this->addError('user_otp', 'Invalid OTP');
            $this->js('error("Invalid OTP")');
        }
    }

    public function toggleEmailChange()
    {
        if ($this->changeEmailAddress) {
            $this->changeEmailAddress = false;
            $this->new_email = $this->student->email;
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

        $this->student->new_email = $this->new_email;
        $this->student->email_code = $emailCode;
        $this->student->save();

        $this->toggleEmailChange();

        Notification::route('mail', $this->student->new_email)->notify(new AnyUserEmailVerify($this->student, $emailCode));

        $this->js('success("Email updated successfully, please verify your email")');
    }
    public function keepOldEmail()
    {
        $this->student->new_email = null;
        $this->student->save();
    }
    public $emailVerifyModalInputOpen = false;
    public function verifyEmail()
    {
        if ($this->email_code == $this->student->email_code) {
            $this->student->email = $this->student->new_email;
            $this->student->email_verified_at = Carbon::now();
            $this->student->save();

            $this->new_email = $this->student->email;

            $this->emailVerifyModalInputOpen = false;

            $this->js('success("Email address verified successfully")');
        } else {
            $this->addError('email_code', 'Invalid OTP');
            $this->js('error("Invalid OTP")');
        }
    }
}
