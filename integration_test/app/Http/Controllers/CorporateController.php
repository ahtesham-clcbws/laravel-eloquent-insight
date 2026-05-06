<?php

namespace App\Http\Controllers;

use App\Mail\OTPCorporateMail;
use App\Mail\OTPMail;
use App\Models\Corporate;
use App\Models\CouponCode;
use App\Models\MobileNumber;
use App\Models\OtpVerifications;
use App\Models\Student;
use App\Models\StudentCode;
use App\Models\TestimonialsModel;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CorporateController extends Controller
{
    protected $smsService;

    public function __construct(Msg91Service $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $corporate = Auth::guard('corporate')->check();
        $corporateId = Auth::guard('corporate')->id();

        $newStudents = Student::where('isNew', 1)
            ->whereHas('studentCode', function ($query) use ($corporateId) {
                $query
                    ->where('corporate_id', $corporateId)
                    ->where('is_coupan_code_applied', 1);
            })
            ->with(['studentCode' => function ($query) use ($corporateId) {
                $query
                    ->where('corporate_id', $corporateId)
                    ->where('is_coupan_code_applied', 1);
            }])
            ->count();
        $students = Student::where('isNew', 0)
            ->whereHas('studentCode', function ($query) use ($corporateId) {
                $query
                    ->where('corporate_id', $corporateId)
                    ->where('is_coupan_code_applied', 1);
            })
            ->with(['studentCode' => function ($query) use ($corporateId) {
                $query
                    ->where('corporate_id', $corporateId)
                    ->where('is_coupan_code_applied', 1);
            }])
            ->count();

        $totalStudents = $students + $newStudents;
        $coupons = CouponCode::where('corporate_id', $corporateId)->where('status', 1)->count();

        return view('corporate.dashboard.dashboard', compact('corporate', 'students', 'newStudents', 'totalStudents', 'coupons'));
    }

    public function login(Request $request)
    {
        $authUser = Auth::guard('corporate')->check();
        if ($authUser) {
            return redirect()->route('corporateDashboard');
        }

        if ($request->isMethod('POST')) {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ], [
                'email.required' => 'Please Enter Registration Email ID',
                'password.required' => 'Please Enter Password',
            ]);
            $corporate = Corporate::where('email', $request->email)->first();

            if ($corporate && Hash::check($request->password, $corporate->password) && $corporate->signup_approved && Auth::guard('corporate')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('corporateDashboard')->with('success', 'Login Successfully.');
            } else {
                return back()->withErrors('Invalid credentials');
            }
        }
        return view('corporate.corporate_login');
    }

    function signup(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'branch_code' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'confirm_password' => 'required',
                'password' => 'required|same:confirm_password'
            ], [
                'email.required' => 'Please Enter Registration Email ID',
                'password.required' => 'Please Enter Password',
            ]);

            $corporate = Corporate::where('institude_code', $request->branch_code)
                ->where('email', $request->email)
                ->where('phone', $request->mobile)
                ->first();

            if ($corporate) {
                $corporate->signup_at = now();
                $corporate->login_password = $request->password;
                $corporate->password = Hash::make($request->password);
                $corporate->save();

                return redirect('/')->with('success', 'You are SignUp Successfully. Please Check Your Email for Confirmation Login Link.');
            } else {
                return back()->withErrors('The Email Or Mobile is Incorrect.');
            }
        }

        $branchBode = base64_decode($request->query('branch_code'));

        $corporate = Corporate::where('institude_code', $branchBode)->first();

        if ($corporate && is_null($corporate->email_verified_at)) {
            $corporate->email_verified_at = now();
            $corporate->save();
        }

        return view('corporate.corporate_signup', compact('corporate'));
    }

    public function logout()
    {
        Auth::guard('corporate')->logout();
        return redirect('/');
    }

    public function studentList(Request $request, $student = null)
    {
        $corporate = Auth::guard('corporate')->user();

        $query = Student::whereHas('studentCode', function ($query) use ($corporate) {
            $query
                ->where('corporate_id', $corporate->id)
                ->where('is_coupan_code_applied', 1);
        });

        if ($request->type == 'new') {
            $query->where('isNew', 1);
        }

        $students = $query->with(['studentCode' => function ($query) use ($corporate) {
            $query
                ->where('corporate_id', $corporate->id)
                ->where('is_coupan_code_applied', 1);
        }, 'studentCode.voucher', 'scholarShipCategory', 'scholarShipOptedFor'])->get();

        // Mark as seen
        $newStudentIds = $students->where('isNew', true)->pluck('id');
        if ($newStudentIds->isNotEmpty()) {
            Student::whereIn('id', $newStudentIds)->update(['isNew' => false]);
        }

        // if ($student) {
        //     $student = $students->where('id', $student)->first();
        //     return view('corporate.dashboard.student.view', compact('student'));
        // }
        // Flatten student codes collection
        $studentCodes = $students->flatMap->studentCodes;

        return view('corporate.dashboard.student.list', compact('students', 'studentCodes'));
    }

    public function updateAdmitCardStatus(Request $request)
    {
        $studCodeIds = $request->input('studcode_ids');
        $status = $request->input('status');

        $corporateStatus = $status == 0 ? 1 : 0;
        $count = 0;
        if (is_array($studCodeIds)) {
            // Only update students whose admit card is NOT issued yet
            $count = StudentCode::whereIn('id', $studCodeIds)
                ->where('issued_admitcard', 0)
                ->update(['issued_admitcard' => $status, 'corporate_stop_admitcard' => $corporateStatus]);
        } else {
            $studentCode = StudentCode::find($studCodeIds);
            if ($studentCode && $studentCode->issued_admitcard == 0) {
                $studentCode->issued_admitcard = $status;
                $studentCode->corporate_stop_admitcard = $corporateStatus;
                $studentCode->save();
                $count = 1;
            }
        }

        return response()->json(['status' => true, 'message' => "$count AdmitCard Status Updated Successfully."]);
    }

    public function corporateCouponlist(Request $request)
    {
        $corporate = Auth::guard('corporate')->user();

        $coupons = CouponCode::orderByDesc('created_at')->where('corporate_id', $corporate->id)->where('status', 1)->get();

        $counts = '';
        $appliedCount = '';
        $prefix = '';
        $codeValue = '';
        $issuedCount = '';

        if ($request->method() == 'POST') {
            $name = $request->input('name');

            $filteredCoupons = $coupons->where('prefix', $name);

            $counts = $filteredCoupons->count();

            $appliedCount = $filteredCoupons->where('is_applied', 1)->count();

            $issuedCount = $filteredCoupons->where('is_issued', 1)->count();

            $codeType = $filteredCoupons->first()?->valueType;

            $prefix = $filteredCoupons->first()?->prefix;

            $codeValue = $filteredCoupons->first()?->value;

            $codeValue = $codeValue ? $codeValue . '  ' . ($codeType == 'amount' ? 'Rs.' : '%') : '';

            return response()->json(['issuedCount' => $issuedCount, 'coupons' => $filteredCoupons, 'counts' => $counts, 'appliedCount' => $appliedCount, 'codeValue' => $codeValue, 'prefix' => $prefix]);
        }
        $counts = $coupons->count();

        $appliedCount = $coupons->where('is_applied', 1)->count();

        return view('corporate.dashboard.corporate_coupon_list', compact('issuedCount', 'coupons', 'counts', 'appliedCount', 'prefix', 'codeValue', 'corporate'));
    }

    public function sayAboutUs(Request $request)
    {
        $corporate = Corporate::findOrFail(Auth::guard('corporate')->id());

        $testimonial = $corporate->testimonial;

        if ($request->isMethod('POST') && is_null($testimonial)) {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'testimonials_msg' => 'required',
            ]);

            $message = $request->testimonials_msg;

            $testimonials = $request->id ? TestimonialsModel::where('type', 'corporate')->where('id', $request->id)->first() : new TestimonialsModel();
            $testimonials->name = $corporate->name;
            $testimonials->type_id = $corporate->id;
            $testimonials->type = 'corporate';

            $formattedMessage = $message . ' <div class="institute_nameTestimonialsModel"><br><b>Director Name: ' . $corporate->name . '</b><br><b>Institute / school name: ' . $corporate->institute_name . '</b>br><br>  <b> City: ' . $corporate->city . '</b></div>';
            $testimonials->message = $formattedMessage;

            if ($request->hasFile('profile_image')) {
                $imagePath = moveFile('home', $request->file('profile_image'));
                $testimonials->image = $imagePath;
            }

            $testimonials->save();

            return redirect()->back()->with('success', 'Testimonial added successfully!');
        }

        $testimonial = $corporate->testimonial;

        return view('corporate.dashboard.testimonial', compact('corporate', 'testimonial'));
    }

    public function profilePage(Request $request)
    {
        $corporate = Corporate::find(Auth::guard('corporate')->id());

        if ($request->isMethod('POST')) {
            $validatedData = $request->validate([
                'name' => 'required|string',
                // 'email' => 'required|string|lowercase|email|unique:students,id,' . $student->id,
                // 'phone' => 'required|digits:10|unique:corporates,phone,' . $corporate->id,
                // 'gender' => 'nullable',
                // 'disability' => 'required',
            ]);

            try {
                $corporate->forceFill($validatedData);
                $corporate->save();

                return redirect()->back()->with(['success' => 'Updated successfully']);
            } catch (\Throwable $th) {
                logger('message failed:', [$th]);
                return back()->withErrors('Failed to updated' . $th);
            }
        }

        return view('corporate.dashboard.profile', compact('corporate'));
    }

    public function changePassword(Request $request)
    {
        $student = Corporate::find(Auth::guard('corporate')->id());

        if ($request->isMethod('POST')) {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

            if (!Hash::check($request->old_password, $student->password)) {
                return back()->with('error', 'The old password incorrect.');
            }

            if (Hash::check($request->new_password, $student->password)) {
                return back()->with('error', 'The New Password can not be the same as the Current Password.');
            }

            $student->password = bcrypt($request->new_password);
            $student->login_password = $request->new_password;
            $student->save();

            // return redirect()->route('student.payment');
        }
        return view('corporate.dashboard.changePassword', compact('student'));
    }

    public function uploadPhoto(Request $request)
    {
        $student = Corporate::find(Auth::guard('corporate')->id());

        try {
            $validatedData = $request->validate([
                'photograph' => 'required|image|mimes:jpeg,png|max:2048',
                'signature' => 'nullable|image|mimes:jpeg,png|max:2048',
            ]);

            if ($request->hasFile('photograph')) {
                $validatedData['photograph'] = moveFile('upload/corporate', $request->photograph);

                $student->photograph = $validatedData['photograph'];
                $student->save();

                return response()->json(['message' => 'uploaded image.', 'photo_path' => 'upload/corporate/' . $validatedData['photograph']], 200);
            }

            if ($request->hasFile('signature')) {
                $validatedData['signature'] = moveFile('upload/student', $request->signature);

                $student->signature = $validatedData['signature'];
                $student->save();

                return response()->json(['message' => 'uploaded image.', 'photo_path' => 'upload/corporate/' . $validatedData['photograph']], 200);
            }
            return response()->json(['message' => 'Failed to upload11.'], 403);
        } catch (\Throwable $th) {
            logger('failed:', [$th]);
            return response()->json(['error' => 'Failed to upload image1222.' . $th], 503);
        }
    }

    public function forgotpassword(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validatedData = $request->validate([
                'email' => 'required|string',
            ]);

            try {
                $corporate = Corporate::where('email', $request->email)->first();
                $corporate->forceFill($validatedData);
                $corporate->save();

                return redirect()->back()->with(['success' => 'Updated successfully']);
            } catch (\Throwable $th) {
                logger('message failed:', [$th]);
                return back()->withErrors('Failed to updated' . $th);
            }
        }

        return view('corporate.corporate_forgotpassword');
    }

    public function resetForgotPassword(Request $request)
    {
        $request->validate([
            'forget_mobile' => 'required',
            'forget_otp' => 'required',
            'new_password' => 'required',
            'confirm_Password' => 'required|same:new_password',
        ]);

        $student = Corporate::where('phone', $request->forget_mobile)->first();

        if (!verifyOtp($request->forget_otp, $request->forget_mobile)) {
            return response()->json(['success' => false, 'msg' => 'Otp expired or invalid.']);
        }
        if ($student) {
            $student->password = bcrypt($request->new_password);
            $student->login_password = $request->new_password;
            $student->save();

            return response()->json(['status' => true, 'message' => ' Password Reset successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'You are not registered.']);
        }
    }

    public function CorporateSendVerificationOtpMobileNoChange(Request $request)
    {
        $smsService = $this->smsService;

        $mobileNumber = $request->mobile;

        // check mobile no already exist
        $checkMobile = Corporate::where('phone', $mobileNumber)->first();
        if (isset($checkMobile) && !empty($checkMobile)) {
            // get no of times
            // $noof_time = $checkMobile

            return response()->json(['status' => false, 'message' => 'Mobile no already taken! try with different number. Max 3 times can be changed', 'mobile_no' => '']);
        } else {
            // get user id
            $userId = Auth::guard('corporate')->id();
            $getCorporateDetails = Corporate::where('id', $userId)->first();

            if (isset($getCorporateDetails) && !empty($getCorporateDetails)) {
                // check nof of attempt
                $noofattempt = $getCorporateDetails->no_of_time_phone_no_changed;

                if ($noofattempt > 2) {
                    return response()->json(['status' => false, 'message' => 'Maximum 3 times you can changed mobile no.', 'mobile_no' => '']);
                } else {
                    $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));

                    $verifiedMobile = MobileNumber::where('mobile', $mobileNumber)->where('isOtpRequired', 1)->first();

                    if ($verifiedMobile) {
                        return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.', 'mobile_no' => '']);
                    }

                    $otpVerifications = OtpVerifications::where('credential', $mobileNumber)
                        ->where('created_at', '>=', $time)
                        ->first();

                    if ('otp_verify' == $request->form_name) {
                        $otp = $request->otp;
                        if (is_null($otp)) {
                            return response()->json(['status' => false, 'message' => 'Please Enter OTP.', 'mobile_no' => '']);
                        }

                        if (!verifyOtp($otp, $mobileNumber)) {
                            return response()->json(['status' => false, 'message' => 'Invalid Otp.', 'mobile_no' => '']);
                        }

                        // update phone no.
                        $getCorporateDetails->phone = $mobileNumber;
                        $newpp = $noofattempt + 1;
                        $getCorporateDetails->no_of_time_phone_no_changed = $newpp;
                        $getCorporateDetails->save();

                        return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.', 'mobile_no' => $mobileNumber]);
                    }

                    if (is_null($otpVerifications)) {
                        // $corporateEmail = $getCorporateDetails->email;
                        $otp = mt_rand(100000, 999999);
                        $smsService->sendSms($mobileNumber, $otp);

                        // Mail::to($corporateEmail)->send(new OTPCorporateMail($otp));

                        return response()->json(['status' => true, 'message' => 'Otp sent Successfully.', 'mobile_no' => '']);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Try again after 10 minutes later.', 'mobile_no' => '']);
                    }
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Session Expired! Please login again to continue', 'mobile_no' => '']);
            }
        }
    }
}
