<?php

namespace App\Livewire\Auth\Corporate;

use App\Models\Corporate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.fullpage')]
class InstituteSignUpForm extends Component
{
    #[Validate('exists:corporates,institude_code', message: 'Branch code is not valid.')]
    public $institude_code;

    #[Validate('required', message: 'Please enter phone number')]
    #[Validate('min_digits:10', message: 'Phone number must minimum 10 digits')]
    #[Validate('max_digits:10', message: 'Phone number have maximum 10 digits')]
    #[Validate('exists:corporates,phone', message: 'Phone number is not valid.')]
    public $phone;
    // public $phone2;

    #[Validate('required', message: 'Please enter your email address')]
    #[Validate('email', message: 'Please enter valid email address')]
    #[Validate('exists:corporates,email', message: 'Email is not valid.')]
    public $email;
    // public $email2;

    #[Validate('required', message: 'Password is required')]
    #[Validate('min:8', message: 'Password should be minimum 8 characters')]
    public $password;
    #[Validate('required', message: 'Password is required')]
    #[Validate('min:8', message: 'Password should be minimum 8 characters')]
    #[Validate('same:password', message: 'Confirm password should matched with password')]
    public $password_confirmation;

    #[Validate('required', message: 'Please accept our term & conditions before proceed further')]
    public $terms = null;


    public function render()
    {
        return view('livewire.auth.corporate.institute-sign-up-form');
    }

    public function signUp()
    {

        $corporate = Corporate::where('institude_code', $this->institude_code)->first();

        if ($corporate->phone != $this->phone) {
            $this->addError('phone2', 'Phone number not match with your details');
            return false;
        }
        if ($corporate->email != $this->email) {
            $this->addError('email', 'Email address not match with your details');
            return false;
        }

        $this->validate();
        try {
            $corporate->signup_at = now();
            $corporate->login_password = $this->password;
            $corporate->password = Hash::make($this->password);
            $corporate->save();

            $this->js("success('You are SignUp Successfully. Please Check Your Email for Confirmation Login Link.')");
            return $this->redirect('/');
        } catch (\Throwable $th) {
            // throw $th;
            logger('Institute Signup Failed:', [$th]);
            $this->js("error('Unable to signup, try after some time.')");
        }
    }
}
