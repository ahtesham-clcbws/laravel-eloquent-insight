<?php

namespace App\Livewire\Auth;

use App\Models\Corporate;
use App\Models\CouponCode;
use App\Models\District;
use App\Models\OtpVerifications;
use App\Services\Msg91Service;
use App\Models\PolicyPage;
use App\Models\Student;
use App\Models\StudentCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Registration extends Component
{
    public $showPassword = false;

    #[Validate('required', message: 'Please select Qualification')]
    public $selectedBoard = null;

    #[Validate('required', message: 'Please select Scholarship')]
    public $selectedScholarship = null;

    #[Validate('required', message: 'Please select State')]
    public $selectedState = null;

    #[Validate('required', message: 'Please select District')]
    public $selectedDistrict = null;

    public District $selectedDistrictData;

    #[Validate('required', message: 'Please enter your name')]
    #[Validate('min:3', message: 'Full name must be minimum 8 characters')]
    public $name;

    #[Validate('required', message: 'Please select Gender')]
    public $gender;

    #[Validate('required', message: 'Please enter mobile number')]
    #[Validate('min_digits:10', message: 'Mobile number must minimum 10 digits')]
    #[Validate('max_digits:10', message: 'Mobile number have maximum 10 digits')]
    #[Validate('unique:students,mobile', message: 'Mobile number is already in use')]
    public $mobile;

    #[Validate('required', message: 'Please enter your email address')]
    #[Validate('email', message: 'Please enter valid email address')]
    #[Validate('unique:students,email', message: 'Email address is already in use')]
    public $email;

    #[Validate('required', message: 'Password is required')]
    #[Validate('min:8', message: 'Password should be minimum 8 characters')]
    public $password;

    #[Validate('required', message: 'Password is required')]
    #[Validate('min:8', message: 'Password should be minimum 8 characters')]
    #[Validate('same:password', message: 'Confirm password should matched with password')]
    public $password_confirmation;

    #[Validate('required', message: 'Disability is required')]
    #[Validate('in:Yes,No', message: 'Please choose disability')]
    public $disability = 'No';

    #[Validate('required', message: 'Please accept our term & conditions before proceed further')]
    public $terms = null;

    public $remainingForms = 725;
    public ?string $couponcode = null;
    public bool $isCouponVerify = false;
    public $customErrors = null;
    public $otpRequestId = '';
    public $otpSendSuccess = false;

    #[Validate('required', message: 'Please enter OTP')]
    public $userOtp;

    public $isOtpVerfied = false;
    public $institudeTermsCondition;

    public function couponVerify()
    {
        $this->resetValidation(['couponcode']);
        if (empty(trim($this->couponcode))) {
            $this->isCouponVerify = false;
            return $this->addError('couponcode', 'Referrence code is required');
        } else {
            $couponAvailable = CouponCode::where('couponcode', $this->couponcode)->where('status', 1)->where('is_applied', 0)->first();
            if ($couponAvailable) {
                if ($couponAvailable->corporate_id && $couponAvailable->is_issued) {
                    $corporate = Corporate::find($couponAvailable->corporate_id);
                    if ($corporate && $corporate->district_id != $this->selectedDistrict) {
                        $this->isCouponVerify = false;
                        return $this->addError('couponcode', 'Referrence code is not valid for this city.');
                    }
                }
            } else {
                $this->isCouponVerify = false;
                return $this->addError('couponcode', 'Referrence code is not Valid.');
            }
            $this->isCouponVerify = true;
            return true;
        }
        $this->isCouponVerify = false;
        return false;
    }

    public function updated($property)
    {
        if ($property == 'selectedBoard') {
            $this->selectedScholarship = null;
            $this->selectedState = null;
            $this->selectedDistrict = null;
        }
        if ($property == 'selectedScholarship') {
            $this->selectedState = null;
            $this->selectedDistrict = null;
        }
        if ($property == 'selectedState') {
            $this->selectedDistrict = null;
        }
        // $this->js('console.log(' . json_encode($property) . ')');
        if ($property == 'selectedDistrict' && $this->selectedState && $this->selectedDistrict) {
            $this->selectedDistrictData = District::find($this->selectedDistrict);
            $data = $this->selectedDistrictData?->getLimit($this->selectedScholarship);
            $limit = $data?->limit ?? 0;
            $this->remainingForms = $data?->remaining ?? 0;
            $this->isCouponVerify = false;
            $this->couponcode = null;
        }
    }


    public bool $isRegistrationActive = true;
    public string $registrationMessage = '';

    public function mount()
    {
        $this->checkRegistrationStatus();
    }

    public function checkRegistrationStatus()
    {
        $setting = \App\Models\RegistrationSetting::first();
        if (!$setting || !$setting->is_enabled) {
            $this->isRegistrationActive = false;
            $this->registrationMessage = 'Registrations are disabled for now. Please contact the administrator.';
            return;
        }

        $now = now();
        if ($setting->start_date && $now->lt($setting->start_date)) {
            $this->isRegistrationActive = false;
            $this->registrationMessage = 'Registrations will be available from <br />' . $setting->start_date->format('d M Y, h:i A') . '.';
            return;
        }

        if ($setting->end_date && $now->gt($setting->end_date)) {
            $this->isRegistrationActive = false;
            $this->registrationMessage = 'Registrations are now closed. Please contact the administrator.';
            return;
        }

        $this->isRegistrationActive = true;
    }

    public function rules()
    {
        return [
            'couponcode' => $this->remainingForms <= 725 ? 'required' : 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'couponcode.required' => 'Referrence code is required',
        ];
    }

    public function render()
    {
        $this->checkRegistrationStatus();
        return view('livewire.auth.registration');
    }

    public function sendOTP()
    {
        $validated = $this->validateOnly('mobile');
        if ($validated) {
            $otp = rand(100000, 999999);
            $smsSent = app(Msg91Service::class)->sendSms($this->mobile, $otp);

            if ($smsSent) {
                // The service already saves to OtpVerifications, but we need the record for otpRequestId
                $otpCreated = OtpVerifications::where('credential', $this->mobile)->where('otp', $otp)->latest()->first();
                if ($otpCreated) {
                    $this->otpRequestId = $otpCreated->id;
                    $this->otpSendSuccess = true;
                }
                $this->js('toastr.success("OTP send successfully, please check your phone.")');
                $this->js('setTimeout(() => { document.getElementById("student_otp_reg")?.focus(); }, 100)');
            } else {
                $this->js('toastr.error("Failed to send OTP. Please try again.")');
            }
        } else {
            $this->js('toastr.error("Mobile number, id not valid.")');
        }
    }

    public function verifyOtp()
    {
        if (verifyOtp($this->userOtp, $this->mobile)) {
            $this->isOtpVerfied = true;
        } else {
            // $this->addError('mobile', 'Enter correct mobile number.');
            $this->addError('userOtp', 'Enter correct OTP.');
            return false;
        }
    }

    public function registerVerifyCoupon()
    {
        if (!empty(trim($this->couponcode))) {
            $coupon = CouponCode::where('couponcode', $this->couponcode)->where('status', 1)->where('is_applied', 0)->first();
            if ($coupon->corporate_id && $coupon->is_issued) {
                $corporate = Corporate::find($coupon->corporate_id);
                if ($corporate && $corporate->district_id == $this->selectedDistrict) {
                    $this->isCouponVerify = false;
                    return (object) [
                        'success' => true,
                    ];
                }
            }
            return (object) [
                'success' => false,
                'message' => 'Referrence code is not valid for this city.'
            ];
        } else {
            return (object) [
                'success' => true
            ];
        }
    }

    public function register()
    {
        $this->validate();

        // $couponVerify = $this->registerVerifyCoupon();
        // if ($couponVerify && !$couponVerify->success) {
        //     $this->isCouponVerify = false;
        //     $this->addError('couponcode', $couponVerify->message);
        //     return false;
        // } else {
        //     $this->isCouponVerify = true;
        //     $this->resetValidation(['couponcode']);
        // }

        if ($this->remainingForms <= 725 && !$this->isCouponVerify) {
            $couponVerify = $this->couponVerify();
            if (!$couponVerify) {
                return false;
            } else {
                $this->isCouponVerify = true;
                $this->resetValidation(['couponcode']);
            }
            // return $this->couponVerify();
        }

        if (!$this->otpRequestId || !$this->otpSendSuccess) {
            $this->addError('mobile', 'Please verify your mobile number.');
            return false;
        }
        if (!$this->isOtpVerfied) {
            $this->addError('userOtp', 'OTP is not valid, Please check again.');
            return false;
        }

        // return false;

        try {
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

            if ($this->remainingForms <= 725) {
                $this->applyCoupon($student->id, $this->couponcode);
            }

            // DB::commit();
            // Log the user in after registration
            Auth::guard('student')->login($student);
            $this->js("toastr.success('Registered successfully.')");
            return $this->redirect('/students/studentDashboard');
        } catch (\Throwable $th) {
            logger('Registration Failed:', [$th]);
            $this->js("toastr.error('Unable to register, try after some time.')");
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
                $couponCode->save();
            } else {
                $studentCode->fee_amount = 850;
                $studentCode->coupan_value = 0;
            }

            if ($studentCode->fee_amount <= 0) {
                $studentCode->used_coupon = 1;
                $studentCode->is_paid = 1;
            }
            $studentCode->save();
        } catch (\Throwable $th) {
            \logger('Apply Coupon Error: ' . $th->getMessage());
            $this->js("toastr.error('" . $th->getMessage() . "')");
        }
    }

    public function couponValueApply($valueType, $value)
    {
        $valueAmount = $valueType == 'amount' ? $value : (850 * ($value / 100));
        return 850 - $valueAmount;
    }
}
