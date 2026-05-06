<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Center;
use App\Models\CourseDetailsModel;
use App\Models\ExamSubject;
use App\Models\Payment;
use App\Models\ScholorshipExamModel;
use App\Models\ScholorshipFormModel;
use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsCsvExport;
use App\Models\ApplicationCodeList;
use App\Models\BoardAgencyStateModel;
use App\Models\Category;
use App\Models\City;
use App\Models\CouponCode;
use App\Models\District;
use App\Models\EducationType;
use App\Models\Gn_DisplayExamAgencyBoardUniversity;
use App\Models\Gn_OtherExamClassDetailModel;
use App\Models\MobileNumber;
use App\Models\OtpVerifications;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentClaimForm;
use App\Models\StudentCode;
use App\Models\StudentPaperExported;
use App\Models\TermsCondition;
use App\Models\TestimonialsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentController extends Controller
{
    public function studentslist()
    {
        $student = UserRegistration::all();
        //studentslist
        return view('Admin.student.studentlist', compact('student'));
    }

    public function uploadexcel(Request $request)
    {
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            // Process the uploaded file
            try {
                $import = new StudentsImport();
                $data = Excel::toArray($import, $file);

                // Optionally, you can return the data to a view for display
                return $data;
            } catch (\Exception $e) {
                // Handle any errors that occur during the import process
                return redirect()->back()->with('error', 'Error occurred while processing the Excel file: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Please select a file to upload.');
        }
    }

    public function exportCsv(Request $request)
    {
        $participate_exam = $request->exam_id;
        $results = ScholorshipFormModel::with('examSubjects')
            ->where('participate_exam', $participate_exam)
            ->get();

        if ($results->isEmpty()) {
            return 'Data Not found';
        } else {
            return Excel::download(new StudentsCsvExport($results), 'students.csv');
        }
    }

    public function scholorshipApplyList()
    {
        $student = ScholorshipFormModel::with('examSubjects')->get();
        $exam = ScholorshipExamModel::all();
        return view('admin.scholarship.applied', compact('student', 'exam'));
    }

    public function scholorshipApply()
    {
        $scholarshipExams = ScholorshipFormModel::with('examSubjects')->get();
        $centers = Center::all();
        $courseList = CourseDetailsModel::all();
        return view('website.scholorship', compact('scholarshipExams', 'centers', 'courseList'));
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function allpayment()
    {
        $paymentList = Payment::all();
        return view('admin.student.allpayment', compact('paymentList'));
    }

    public function userDashboard()
    {
        $student = Auth::guard('student')->user();

        return view('student.dashboard.home', compact('student'));
    }

    public function notification()
    {
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect('/');
    }

    public function studentDashboard()
    {
        $student = Auth::guard('student')->user();
        return view('student.dashboard.home', compact('student'));
    }


    public function studentDashboardAfterPaid()
    {
        $student = Auth::guard('student')->user();

        return view('student.dashboard.dashboard', compact('student'));
    }

    public function studentDashboardsss()
    {
        $student = Auth::guard('student')->user();

        return view('student.dashboard.dashboard', compact('student'));
    }


    public function studentform()
    {
        $student = Auth::guard('student')->user();
        $categories = Category::all();
        $states = State::all();

        return view('student.dashboard.studentform', compact('student', 'categories', 'states'));
    }

    public function additionalDetailsCreate(Request $request)
    {
        $student = Auth::guard('student')->user();

        $termsCondition = TermsCondition::where('status', 1)->where('type', 'student')->orderBy('created_at')->first();

        return view('student.dashboard.student_additional_details', compact('student', 'termsCondition'));
    }

    public function form_review()
    {
        $student = Auth::guard('student')->user();
        $states = State::all();

        $termsCondition = TermsCondition::where('status', 1)->where('type', 'student')->orderBy('created_at')->first();

        return view('student.dashboard.student_review', compact('student', 'states', 'termsCondition'));
    }

    public function student_payment()
    {
        $student = Auth::guard('student')->user();
        $student->latestStudentCode;

        return view('student.dashboard.student_payment', compact('student'));
    }

    public function usersignup(Request $request)
    {
        if (!$request->mobile) {
            return response()->json(['success' => false, 'msg' => 'Mobile number  is required.']);
        }
        // $verifiedMobile = MobileNumber::where('mobile', $request->mobile)->where('isOtpRequired', 1)->first();
        // if ($verifiedMobile) {
        //     $uniqueMobile = '';
        //     $uniqueEmail = '';
        // } else {
        $uniqueMobile = "unique:" . Student::class;
        $uniqueEmail = "unique:" . Student::class;
        // }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => "required|string|lowercase|email|$uniqueEmail",
            'mobile' => "required|digits:10|$uniqueMobile",
            'gender' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'disability' => 'required',
            'confirmpassword' => 'required|same:password',
        ]);

        try {
            if (!verifyOtp($request->otp, $request->mobile)) {
                return response()->json(['status' => false, 'message' => 'Invalid Otp.']);
            }
            $student = new Student();
            $student->forceFill(collect($validatedData)->except('confirmpassword', 'otp')->all());
            $student->password = Hash::make($request->password);
            $student->login_password = $request->password;
            $student->save();

            // Log the user in after registration
            Auth::guard('student')->login($student);

            return response()->json(['success' => true, 'msg' => 'Registered and login successfully']);
        } catch (\Throwable $th) {
            logger('message failed:', [$th]);
            return response()->json(['success' => false, 'msg' => $th->getMessage()]);
        }
        // Redirect back or return a response
        return redirect()->route('studentDashboard');
    }

    public function login(Request $request)
    {
        if (Auth::guard('student')->check())
            return redirect()->route('studentDashboard');

        if ($request->method() == 'POST') {
            $request->validate([
                'mobile' => 'required',
                'password' => 'required'

            ], [
                'mobile.required' => 'Please Enter Registration no.',
                'password.required' => 'Please Enter Password',
            ]);
            // dd(['mobile' => $request->mobile, 'password' => $request->password]);
            if (Auth::guard('student')->attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }

        return back();
    }

    public function addQualificationsCreate(Request $request)
    {
        $student = Auth::guard('student')->user();
        $city = District::where('state_id', $student->state_id)->get();

        $choiceCenterA = $city;
        $choiceCenterB = $city->whereNotIn('id', [$student->district_id]);

        return view('student.dashboard.qualification_student', compact('student', 'choiceCenterA', 'choiceCenterB'));
    }

    public function addstudent(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());

        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string',
            'father_name' => 'required|string',
            'dob' => 'required| | date | before:' . date('Y-m-d', strtotime('-16 years')),
            'gender' => 'required',
            // 'category_id' => 'required',
            // 'subcategory_id' => 'required',
            // 'subject' => 'nullable',
            'address' => 'required|string',
            // 'state_id' => 'required',
            // 'district_id' => 'required',
            'pincode' => 'required|digits:6',
            'disability' => 'required',
            'landmark' => 'nullable',
        ]);

        try {
            //code...
            $student->forceFill($validated);
            if ($student->form_step == 0)
                $student->form_step = 1;
            $student->save();
        } catch (\Throwable $th) {

            return back()->withErrors('Failed to save');
            //throw $th;
        }
        // Redirect back or return a response
        return redirect()->route('students.addQualificationsCreate')->with(['success' => 'Student record created successfully.']);
    }

    public function addQualifications(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());
        $validated = $request->validate([
            // 'qualification' => 'required|string',
            // 'scholarship_category' => 'required|string',
            'scholarship_opted_for' => 'required|string',
            'test_center_a' => 'nullable|string',
            'test_center_b' => 'nullable|string',
        ]);

        try {
            $student->forceFill($validated);
            if ($student->form_step == 1)
                $student->form_step = 2;
            $student->save();
        } catch (\Throwable $th) {
            return back()->withErrors('Failed to save');
        }
        return redirect()->route('students.additionalDetailCreate')->with(['success' => 'Student Qualification saved successfully.']);
    }

    public function additionalDetailStore(Request $request)
    {

        try {
            $student = Student::find(Auth::guard('student')->id());
    
            $examRequired = $request->is_gov_exam_participated == 'yes' ? 'required' : 'nullable';
            $careerRequired = $request->is_apply_career_without_barrier == 'yes' ? 'required' : 'nullable';
    
            $photoReq = $student->photograph ? 'nullable' : 'required';
            $signReq = $student->signature ? 'nullable' : 'required';
    
            $validatedData = $request->validate(
                [
                    'is_gov_exam_participated' => 'required',
                    'is_apply_career_without_barrier' => 'required',
                    'terms_conditions' => 'required',
                    'govt_exams_1' => "$examRequired|string",
                    'govt_exams_2' => 'nullable|string',
                    'year' => "$careerRequired|string",
                    'roll_no' => "nullable|string",
                    'family_income' => 'nullable|string',
                    'father_occupation' => 'nullable|string',
                    'mother_occupation' => 'nullable|string',
                    'photograph' => "$photoReq|file|mimes:jpeg,png,jpg",
                    'signature' => "$signReq|file|mimes:jpeg,png,jpg",
                    'exam_one_year' => "nullable|string",
                    'exam_one_result' => "nullable|string",
                    'exam_two_year' => 'nullable|string',
                    'exam_two_result' => 'nullable|string',
                    'career_exams_1' => 'nullable',
                    'career_one_result' => 'nullable',
                    'career_exams_2' => 'nullable',
                    'career_two_year' => 'nullable',
                    'career_two_result' => 'nullable',
    
                ],
                [
                    'is_gov_exam_participated.required' => 'The government exam participation field is required.',
                    'is_apply_career_without_barrier.required' => 'The career application without barrier field is required.',
                    'govt_exams_1.required' => 'The first government exam field is required.',
                    'year.required' => 'The year field is required.',
                    'terms_conditions.required' => 'The terms_conditions field is required.',
                    'roll_no.required' => 'The roll number field is required.',
                    'photograph.required' => 'The photograph field is required.',
                    'photograph.file' => 'The photograph must be a file.',
                    'photograph.mimes' => 'The photograph must be a file of type: jpeg, png.',
                    'signature.required' => 'The signature field is required.',
                    'signature.file' => 'The signature must be a file.',
                    'signature.mimes' => 'The signature must be a file of type: jpeg, png.',
                    'signature.max' => 'The signature may not be greater than 2048 kilobytes.'
                ]
            );
            if ($request->hasFile('photograph')) {
                $validatedData['photograph'] = moveFile('upload/student', $request->photograph);
            }
    
            if ($request->hasFile('signature')) {
                $validatedData['signature'] = moveFile('upload/student', $request->signature);
            }
            $student->forceFill($validatedData);

            if ($student->form_step == 2)
                $student->form_step = 3;
            $student->save();
        } catch (\Throwable $th) {
            return back()->withErrors('Failed to save');
        }

        // Redirect back or return a response
        return redirect()->route('students.formReview')->with(['success' => 'Student record created successfully.']);
    }

    public function finalSubmit(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());
        $validated = $request->validate([
            'name_checked' => 'required',
            'father_name_checked' => 'required',
            'dob_checked' => 'required',
            'mobile_checked' => 'required',
            'email_checked' => 'required',
            'qualification_checked' => 'required',
            'scholarship_category_checked' => 'required',
            'scholarship_opted_for_checked' => 'required',
            'choiceCenterA_checked' => 'required',
            'choiceCenterB_checked' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $student->forceFill($validated);
            if ($student->form_step == 3) {
                $student->form_step = 4;
            }

            $student->is_final_submitted = 1;
            $student->save();

            $studentCode = StudentCode::where('stud_id', $student->id)->get()->last();
            if (!$studentCode) {
                $studentCode = new StudentCode();
                $studentCode->stud_id = $student->id;
            }
            $studentCode->application_code = $this->generateAppCode($student);
            $studentCode->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors('Failed to save');
        }
        return redirect()->route('student.payment')->with(['success' => 'Student finally submitted successfully.']);
    }

    public function generateAppCode($student)
    {
        try {
            DB::beginTransaction();

            $city = $student->district->name;
            $cityPrefix = strtoupper(substr($city, 0, 3));

            // Check if the city already exists in the roll_numbers table
            $appCodeRecord = ApplicationCodeList::orderBy('last_app_code', 'desc')->first();

            if ($appCodeRecord) {
                $existCityAppCodeList = ApplicationCodeList::where('city', $city)->orderBy('last_app_code', 'desc')->first();

                if ($existCityAppCodeList) {
                    $defaultStartNumber = $appCodeRecord->last_app_code;
                    $defaultStartNumber = $defaultStartNumber + 1;
                } else {
                    $defaultStartNumber = $appCodeRecord->last_app_code;
                    $additionalIncrement = 20;
                    $defaultStartNumber = $defaultStartNumber + $additionalIncrement;
                }
                $appCodeRecord = new ApplicationCodeList();
                $appCodeRecord->city = $city;
                $appCodeRecord->last_app_code = $defaultStartNumber;
                $appCodeRecord->save();
            } else {

                $defaultStartNumber = 31010;

                $appCodeRecord = new ApplicationCodeList();

                $appCodeRecord->city = $city;
                $appCodeRecord->last_app_code = $defaultStartNumber;
                $appCodeRecord->save();
            }

            // Generate the full roll number
            $appCode = $appCodeRecord->last_app_code;
            $fullAppCodeList = $cityPrefix . $appCode;

            DB::commit();

            return $fullAppCodeList;
        } catch (\Throwable $th) {
            DB::rollBack();
            logger('Failed to generate App Code', [$th]);
        }
    }

    public function applyCoupon(Request $request)
    {
        $student = Auth::guard('student')->user();

        $studentCode = StudentCode::where('stud_id', $student->id)->get()->last();
        if (!$studentCode) {
            $studentCode = new StudentCode();
            $studentCode->stud_id = $student->id;
        }

        $validated = $request->validate([
            'coupan_code' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $couponCode = CouponCode::where('is_applied', 0)
                ->where('couponcode', $validated['coupan_code'])
                ->first();

            if (is_null($couponCode)) {
                return response()->json([
                    'status' => false,
                    'message' => "Coupon Code invalid"
                ]);
            }

            $couponCode->is_applied = 1;
            $couponCode->save();


            $afterAppliedRemainValue = couponValueApply($couponCode->valueType, $couponCode->value);

            $corporate = $couponCode->corporate;
            if ($corporate) {
                $studentCode->corporate_id = $corporate->id;
                $studentCode->corporate_name = $corporate->institute_name ?? $corporate->name;
            }
            $studentCode->forceFill($validated);
            $studentCode->coupan_code = $couponCode->couponcode;
            $studentCode->is_coupan_code_applied = 1;
            $studentCode->coupan_value = 850 - $afterAppliedRemainValue > 0 ? 850 - $afterAppliedRemainValue : 0;
            $studentCode->fee_amount = $afterAppliedRemainValue;

            if ($studentCode->fee_amount <= 0) {
                $studentCode->used_coupon = 1;
            }
            $studentCode->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Coupon code applied successfully.',
                'amount' => $studentCode->fee_amount,
                'discount_amount' => $studentCode->coupan_value,
                'coupon_code' => $couponCode->couponcode,
                'corporate_name' => $studentCode->corporate_name
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            logger('Failed:', [$th]);
            return back()->withErrors('Failed to apply code');
        }

        // Redirect back or return a response

    }

    public function removeCoupon(Request $request)
    {
        $student = Auth::guard('student')->user();

        try {
            DB::beginTransaction();
            $studentCode = StudentCode::where('stud_id', $student->id)->where('coupan_code', $request->coupon_code)->first();

            if (!$studentCode) {
                return response()->json([
                    'status' => false,
                    'message' => 'No coupon applied to remove.',
                ]);
            }

            $studentCode->corporate_name = null;
            $studentCode->corporate_id = null;
            $studentCode->coupan_code = null;
            $studentCode->is_coupan_code_applied = false;
            $studentCode->fee_amount = 850;
            if ($studentCode->fee_amount > 0) {
                $studentCode->used_coupon = false;
            }
            $studentCode->coupan_value = 0;
            $studentCode->save();

            $couponCode = CouponCode::where('couponcode', $request->coupon_code)->first();

            if ($couponCode) {
                $couponCode->is_applied = false;
                $couponCode->save();
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Coupon removed successfully.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            logger('Failed:', [$th]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove coupon.',
            ]);
        }
    }

    public function admitCardSearch()
    {
        $student = Student::find(Auth::guard('student')->id());

        $studCode = $student?->latestStudentCode;

        $qualification = $student?->qualifications;

        return view('student.dashboard.admitCardSearch', compact('student', 'studCode', 'qualification'));
    }

    public function admitCard(Request $request)
    {
        $request->validate([
            'app_code' => 'required',
            'class' => 'required'
        ]);

        $student = Student::find(Auth::guard('student')->id());

        // if($student->student_roll_number != $request->app_code){
        //     return back()->withErrors('The Roll is Not Correct.');
        // }
        $appCode = $student->latestStudentCode;
        if ($appCode && $appCode->exam_center) {
            $appCode->load(['examCenter.state', 'examCenter.city']);
        }

        return view('student.dashboard.admitCard', compact('student', 'appCode'));
    }

    public function resultDownload()
    {
        $student = Auth::guard('student')->user();
        $appCode = $student->latestStudentCode;


        $studentPaperDetails = StudentPaperExported::with('subjectPaperDetail')->where('app_code', $appCode?->application_code)->where('student_id', $student->id)->get();

        return view('student.dashboard.result_download', compact('student', 'appCode', 'studentPaperDetails'));
    }

    public function getScholarshipCategory($id, $type = null)
    {

        if ($type == 'Yes') {
            $education = EducationType::where('id', $id)->get();

            return response()->json(['status' => true, 'message' => 'Select another Qualification.', 'data' => $education]);
        }
        $boardConnection = Gn_DisplayExamAgencyBoardUniversity::where('board_id', 'LIKE', '%' . $id . '%')
            ->with('educations')
            ->get();
        $educations = $boardConnection->pluck('educations')->flatten()->unique('id');

        return response()->json(['status' => true, 'message' => 'Select another Qualification.', 'data' => $educations]);
    }

    public function getScholarshipCategoryOptedFor($id, $qualificationId, $type = null)
    {

        $scholarOptedFor = Gn_OtherExamClassDetailModel::where('education_type_id', $id)->where('agency_board_university_id', $qualificationId)->get();

        if ($scholarOptedFor->isNotEmpty()) {
            return response()->json(['status' => true, 'scholarOptedFor' => $scholarOptedFor]);
        }
        return response()->json(['status' => false, 'message' => 'Select another scholarship category.', 'scholarOptedFor' => $scholarOptedFor]);
    }

    public function changePassword(Request $request)
    {

        $student = Student::find(Auth::guard('student')->id());

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

            return redirect()->route('student.payment');
        }
        return view('student.dashboard.changePassword', compact('student'));
    }

    public function profilePage(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());

        if ($request->isMethod('POST')) {

            $validatedData = $request->validate([
                'name' => 'required|string',
                // 'email' => 'required|string|lowercase|email|unique:students,id,' . $student->id,
                // 'mobile' => 'required|digits:10|unique:students,id,' . $student->id,
                'gender' => 'required',
                'disability' => 'required',
            ]);

            try {
                $student->forceFill($validatedData);
                $student->save();

                return redirect()->back()->with(['success' => 'Updated successfully']);
            } catch (\Throwable $th) {
                logger('message failed:', [$th]);
                return back()->withErrors('Failed to updated');
            }
        }

        return view('student.dashboard.profile', compact('student'));
    }

    public function uploadPhoto(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());

        try {
            $validatedData = $request->validate([
                'photograph' => 'required|image|mimes:mimes:jpeg,png,jpg',
                'signature' => 'nullable|image|mimes:mimes:jpeg,png,jpg',
            ]);

            if ($request->hasFile('photograph')) {
                $validatedData['photograph'] = moveFile('upload/student', $request->photograph);

                $student->photograph = $validatedData['photograph'];
                $student->save();

                return response()->json(['message' => 'uploaded image.', 'photo_path' => 'upload/student/' . $validatedData['photograph']], 200);
            }

            if ($request->hasFile('signature')) {
                $validatedData['signature'] = moveFile('upload/student', $request->signature);

                $student->signature = $validatedData['signature'];
                $student->save();

                return response()->json(['message' => 'uploaded image.', 'photo_path' => 'upload/student/' . $validatedData['photograph']], 200);
            }
            return response()->json(['message' => 'Failed to upload.'], 403);
        } catch (\Throwable $th) {
            logger('failed:', [$th]);
            return response()->json(['error' => 'Failed to upload image.'], 503);
        }
    }

    public function studentSayAboutUs(Request $request)
    {
        $student = Student::findOrFail(Auth::guard('student')->id())->load('latestStudentCode');

        $testimonial = $student->testimonial;

        if ($request->isMethod('POST') && is_null($testimonial)) {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'testimonials_msg' => 'required',
            ]);

            $message = $request->testimonials_msg;

            $testimonials = $request->id ? TestimonialsModel::where('type', 'student')->where('id', $request->id)->first() : new TestimonialsModel();
            $testimonials->name = $student->name;
            $testimonials->type_id = $student->id;
            $testimonials->type = 'student';

            $formattedMessage = $message . '<br><div class="student-testimonial text-right"><br> <b>Student Name: ' . ucfirst($student->name) . '</b><br><b> City: ' . $student->district?->name . '</b></div>';
            $testimonials->message = $formattedMessage;

            if ($request->hasFile('profile_image')) {
                $imagePath = moveFile('home', $request->file('profile_image'));
                $testimonials->image = $imagePath;
            }

            $testimonials->save();

            return redirect()->back()->with('success', 'Testimonial added successfully!');
        }

        $testimonial = $student->testimonial;

        return view('student.dashboard.testimonial', compact('student', 'testimonial'));
    }

    public function deleteTestimonials($id)
    {
        $testimonials = TestimonialsModel::find($id);
        if (!$testimonials) {
            return redirect()->back()->with('error', 'Testimonials not found.');
        } else {
            $testimonials->delete();
            return redirect()->back()->with('success', 'Testimonials deleted');
        }
    }

    public function claimScholarshipForm(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());

        if ($request->isMethod('POST')) {
            $request->validate([
                'terms_conditions_scholarship' => 'required'
            ], [
                'terms_conditions_scholarship.required' => 'The Term and Condition is required.'
            ]);

            $student->terms_conditions_scholarship = $request->terms_conditions_scholarship;
            $student->save();
        }
        $cities = $student->state?->districts;
        $claimForm = StudentClaimForm::where('student_id', $student->id)->first();
        return view('student.dashboard.claim_scholarship', compact('cities', 'student', 'claimForm'));
    }

    public function claimScholarshipFormSave(Request $request)
    {

        $student = Student::find(Auth::guard('student')->id());

        $validated = $request->validate([
            'institude_name1' => 'required',
            'institude_mobile1' => 'required',
            'city_id1' => 'required',
            'institude_address1' => 'required',
            'desired_course_detail1' => 'required',
            'course_fee1' => 'required',
            'course_duration1' => 'required',
            'institude_director1' => 'nullable',
            'institude_email1' => 'nullable',

            'state1' => 'required',
            'institude_name2' => 'required',
            'institude_mobile2' => 'required',
            'city_id2' => 'required',
            'institude_address2' => 'required',
            'desired_course_detail2' => 'required',
            'course_fee2' => 'required',
            'course_duration2' => 'required',
            'institude_director2' => 'nullable',
            'state2' => 'required',
            'institude_email2' => 'nullable',

            'institude_name3' => 'nullable',
            'institude_mobile3' => 'nullable',
            'city_id3' => 'nullable',
            'institude_address3' => 'nullable',
            'desired_course_detail3' => 'nullable',
            'course_fee3' => 'nullable',
            'course_duration3' => 'nullable',
            'institude_director3' => 'nullable',
            'state3' => 'nullable',
            'institude_email3' => 'nullable',

            'institude_name4' => 'nullable',
            'institude_mobile4' => 'nullable',
            'city_id4' => 'nullable',
            'institude_address4' => 'nullable',
            'desired_course_detail4' => 'nullable',
            'course_fee4' => 'nullable',
            'course_duration4' => 'nullable',
            'institude_director4' => 'nullable',
            'state4' => 'nullable',
            'institude_email4' => 'nullable',

        ]);

        // Handle file uploads
        if ($request->hasFile('institude_prospectus1')) {
            $validated['institude_prospectus1'] = moveFile('upload/', $request->file('institude_prospectus1'));
        }

        if ($request->hasFile('institude_prospectus2')) {
            $validated['institude_prospectus2'] = moveFile('upload/', $request->file('institude_prospectus2'));
        }

        $validated['student_id'] = $student->id;

        $claimForm = StudentClaimForm::where('student_id', $student->id)->first();

        if ($claimForm) {
            $claimForm->update($validated);
        } else {
            StudentClaimForm::create($validated);
        }

        return redirect()->route('studentDashboard')->with('success', 'Form Saved successfully');
    }
}
