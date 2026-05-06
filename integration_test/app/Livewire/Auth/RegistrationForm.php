<?php

namespace App\Livewire\Auth;

use App\Models\CouponCode;
use App\Models\District;
use App\Models\Student;
use App\Models\StudentCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

// board_agency_exam

// #[Layout('layouts.website')]
class RegistrationForm extends Component
{
    public $showPassword = false;

    public $isFormValid = true;
    public $formError = null;


    public $selectedBoard = null;
    public $selectedScholarship = null;

    public $selectedState = null;
    public $selectedDistrict = null;
    public $selectedDistrictData = null;

    public $districts = [];

    public $institudeTermsCondition;

    // form inputs data
    #[Validate('required')]
    public string $name;
    public $nameError = null;

    #[Validate('required')]
    public string $gender;
    public $genderError = null;

    #[Validate('required|email')]
    public string $email;
    public $emailError = null;

    #[Validate('required')]
    public string $mobile;
    public $mobileError = null;

    public $isEmailValid = false;
    public $isMobileValid = false;

    #[Validate('required')]
    public string $password;
    public $passwordError = null;
    #[Validate('required')]
    public string $confirmPassword;
    public $confirmPasswordError = null;

    public $referrenceCode = '';
    public $referrenceCodeError = null;
    public $referrenceCodeValidated = false;

    public string $disability = 'No';
    public $disabilityError = null;
    public string $terms;
    public $termsError = null;

    public $otpRequestId = null;
    public bool $otpSendSuccess = false;
    public string $userOtp;
    public $userOtpError = null;

    public $isMobileVerified = false;

    public $needReferrenceCode = true;

    public function mount()
    {
        $terms = DB::table('terms_conditions')->where([['status', 1], ['type', 'student'], ['page_name', 'terms-and-condition']])->first();
        if ($terms) {
            $this->institudeTermsCondition = $terms->terms_condition_pdf;
            $this->terms = false;
        } else {
            $this->institudeTermsCondition = null;
            $this->terms = true;
        }
    }

    public function rules()
    {
        return [
            'referrenceCode' => $this->needReferrenceCode ? 'required' : 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'referrenceCode.required' => 'Referrence code is required',
        ];
    }

    public function render()
    {
        return view('livewire.auth.registration-form');
    }

    public function updated($property)
    {
        if ($property == 'selectedBoard') {
            $this->selectedScholarship = null;
            $this->selectedState = null;
            $this->selectedDistrict = null;
            $this->selectedDistrictData = null;
        }
        if ($property == 'selectedScholarship') {
            $this->selectedState = null;
            $this->selectedDistrict = null;
            $this->selectedDistrictData = null;
        }
        if ($property == 'selectedState') {
            $this->selectedDistrict = null;
            $this->selectedDistrictData = null;
        }
        if ($property == 'selectedState') {
            $this->selectedDistrictData = null;
        } else if ($property == 'selectedDistrict') {
            $this->selectedDistrictData = District::find($this->selectedDistrict);
        }

        $this->mobileEmailValidation();

        // if ($this->email && !empty(trim($this->email))) {
        //     $this->mobileEmailValidation('email');
        // }
        // if ($this->mobile && !empty(trim($this->mobile))) {
        //     $this->mobileEmailValidation('mobile');
        // }

        if ($this->needReferrenceCode) {
            // if ($property == 'referrenceCode') {
            $this->verifyReferrenceCode();
            // }
        }
    }
    public function mobileEmailValidation()
    {
        $emailMobileError = false;
        if ($this->email && !empty(trim($this->email))) {
            $checkEmail = Student::where('email', $this->email)->first();
            if ($checkEmail) {
                $this->emailError = 'Email already exist, please change.';
                $this->js("toastr.error('Email already exist, please change.')");
                $this->isEmailValid = false;
                $emailMobileError = false;
                // return false;
            } else {
                $this->emailError = null;
                $this->isEmailValid = true;
                // return true;
            }
        }
        if ($this->mobile && !empty(trim($this->mobile))) {
            $checkPhone = Student::where('mobile', $this->mobile)->first();
            if ($checkPhone) {
                $this->mobileError = 'Phone number already exist, please change.';
                $this->js("toastr.error('Phone number already exist, please change.')");
                $this->isMobileValid = false;
                $emailMobileError = false;
                // return false;
            } else {
                $this->mobileError = null;
                $this->isMobileValid = true;
                // return true;
            }
        }

        if ($emailMobileError) {
            return false;
        }
        return true;
    }
    public function register()
    {
        // $checkPhone = Student::where('mobile', $this->mobile)->first();
        // if ($checkPhone) {
        //     $this->mobileError = 'Phone number already exist, please change.';
        //     $this->js("toastr.error('Phone number already exist, please change.')");
        // } else {
        //     $this->mobileError = null;
        // }
        // $checkEmail = Student::where('email', $this->email)->first();
        // if ($checkEmail) {
        //     $this->emailError = 'Email already exist, please change.';
        //     $this->js("toastr.error('Email already exist, please change.')");
        // } else {
        //     $this->emailError = null;
        // }

        if ($this->needReferrenceCode) {
            $validCode = $this->verifyReferrenceCode();
        } else {
            $validCode = true;
        }

        if ($this->isMobileValid && $this->isEmailValid && $validCode) {
            try {
                // validate other fields
                if (!in_array(strtolower($this->gender), ['male', 'female', 'transgender']) || strlen($this->mobile) != 10 || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || $this->password != $this->confirmPassword) {
                    if (!in_array(strtolower($this->gender), ['male', 'female', 'transgender'])) {
                        $this->genderError = 'Select Gender';
                        $this->js("toastr.error('Select Gender')");
                    } else {
                        $this->genderError = null;
                    }

                    if (strlen($this->mobile) != 10) {
                        $this->mobileError = 'Input valid phone number, and it should be 10 digits.';
                        $this->js("toastr.error('Input valid phone number, and it should be 10 digits.')");
                    } else {
                        $this->mobileError = null;
                    }

                    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                        $this->emailError = 'Enter valid email address';
                        $this->js("toastr.error('Enter valid email address')");
                    } else {
                        $this->emailError = null;
                    }

                    if ($this->password != $this->confirmPassword) {
                        $this->confirmPasswordError = 'Password doesn\'t match.';
                        $this->js("toastr.error('Password doesn\'t match.')");
                    } else {
                        $this->confirmPasswordError = null;
                    }
                    return false;
                }
                if (!$this->otpSendSuccess || !$this->otpRequestId) {
                    // continue to send otp to the user mobile
                    $otp = rand(100000, 999999);
                    $smsSent = app(\App\Services\Msg91Service::class)->sendSms($this->mobile, $otp);

                    if ($smsSent) {
                        $otpCreated = \App\Models\OtpVerifications::where('credential', $this->mobile)->where('otp', $otp)->latest()->first();
                        $this->otpRequestId = $otpCreated ? $otpCreated->id : 'sent';
                        $this->otpSendSuccess = true;
                        $this->js("toastr.success('OTP sent successfully, please check your phone.')");
                        $this->js('setTimeout(() => { document.getElementById("student_otp_form")?.focus(); }, 100)');
                    } else {
                        $this->js("toastr.error('Failed to send OTP. Please try again.')");
                        return false;
                    }
                } else {
                    // verify otp here
                    if (!verifyOtp($this->userOtp, $this->mobile)) {
                        $this->js("toastr.error('OTP doesn\'t match, try again.')");
                        return false;
                    }
                    $this->isMobileVerified = true;
                    // register the user
                    try {
                        // check by disctrict for real time data now
                        // $dstrict = District::find($this->selectedDistrict);
                        // if (intval($dstrict->getRemainingForms()) > 0) {
                        // DB::beginTransaction();
                        $student = new Student();

                        $student->qualification = $this->selectedBoard;
                        $student->scholarship_category = $this->selectedScholarship;

                        $student->state_id = $this->selectedState;
                        $student->district_id = $this->selectedDistrict;

                        $student->name = $this->name;
                        $student->email = $this->email;
                        $student->mobile = $this->mobile;
                        $student->gender = $this->gender;
                        $student->disability = $this->disability;

                        $student->password = Hash::make($this->password);
                        $student->login_password = $this->password;
                        $student->save();

                        if ($this->needReferrenceCode) {
                            $this->applyCoupon($student->id, $this->referrenceCode);
                        }

                        // DB::commit();
                        // Log the user in after registration
                        Auth::guard('student')->login($student);
                        $this->js("toastr.success('Registered successfully.')");

                        return $this->redirect('/students/studentDashboard');
                        // } else {
                        //     $this->js("toastr.error('Forms not available for this district right now, please after some time, or contact administrator.')");
                        //     return false;
                        // }
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        logger('Registration Failed:', [$th]);
                        $this->js("toastr.error('Unable to register, try after some time.')");
                        return false;
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public function verifyReferrenceCode()
    {
        if (!empty(trim($this->referrenceCode)) && $this->needReferrenceCode) {
            try {
                $couponCode = CouponCode::where('is_applied', 0)
                    ->where('status', 1)
                    ->where('couponcode', $this->referrenceCode)
                    ->first();
                if ($couponCode) {
                    if ($couponCode->corporate && $couponCode->corporate->district_id != $this->selectedDistrict) {
                        $this->referrenceCodeError = 'Referrence code is not valid';
                        $this->js("toastr.error('Referrence code is not valid for this city/district')");
                        $this->referrenceCodeValidated = false;
                        return false;
                    } else {
                        $this->referrenceCodeError = null;
                        $this->referrenceCodeValidated = true;
                        return true;
                    }
                } else {
                    $this->referrenceCodeError = 'Referrence code not found.';
                    $this->js("toastr.error('Referrence code not found.')");
                    $this->referrenceCodeValidated = false;
                    return false;
                }
            } catch (\Throwable $th) {
                //throw $th;
                $this->js("toastr.error('Email already exist, please change.')");
                return false;
            }
        }
    }

    public function applyCoupon($studentId, $coupon)
    {
        try {
            $studentCode = StudentCode::where('stud_id', $studentId)->get()->last();
            if (!$studentCode) {
                $studentCode = new StudentCode();
                $studentCode->stud_id = $studentId;
            }

            $couponCode = CouponCode::where('couponcode', $coupon)->first();
            if ($couponCode) {
                $couponCode->is_applied = 1;

                $afterAppliedRemainValue = $this->couponValueApply($couponCode->valueType, $couponCode->value);

            $corporate = $couponCode->corporate;
            if ($corporate) {
                $studentCode->corporate_id = $corporate->id;
                $studentCode->corporate_name = $corporate->institute_name ?? $corporate->name;
            }

            $studentCode->coupan_code = $couponCode->couponcode;
            $studentCode->is_coupan_code_applied = 1;
            $studentCode->coupan_value = 850 - $afterAppliedRemainValue > 0 ? 850 - $afterAppliedRemainValue : 0;
            $studentCode->fee_amount = $afterAppliedRemainValue;

            if ($studentCode->fee_amount <= 0) {
                $studentCode->used_coupon = 1;
                $studentCode->is_paid = 1;
            }
                if ($studentCode->save()) {
                    $couponCode->save();
                }
            } else {
                $studentCode->fee_amount = 850;
                $studentCode->coupan_value = 0;
                $studentCode->save();
            }
        } catch (\Throwable $th) {
            $this->js("toastr.error('" . $th->getMessage() . "')");
        }
    }

    public function couponValueApply($valueType, $value)
    {
        $valueAmount = $valueType == 'amount' ? $value : (850 * ($value / 100));
        return 850 - $valueAmount;
    }
}
