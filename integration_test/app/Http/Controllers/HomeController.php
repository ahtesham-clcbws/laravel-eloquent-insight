<?php

namespace App\Http\Controllers;

use App\Mail\OTPCorporateMail;
use App\Mail\OTPMail;
use App\Models\AboutUs;
use App\Models\AboutUsFounder;
use App\Models\AboutUsSectionFive;
use App\Models\AboutUsSectionFour;
use App\Models\AboutUsSectionOne;
use App\Models\AboutUsSectionSix;
use App\Models\AboutUsSectionThree;
use App\Models\BenefitsModel;
use App\Models\BlognewsModel;
use App\Models\Center;
use App\Models\CompanyModel;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\CourseDetailsModel;
use App\Models\District;
use App\Models\EducationType;
use App\Models\EProspectusModel;
use App\Models\ExamSubject;
use App\Models\FaqModel;
use App\Models\GovtwebsiteModel;
use App\Models\MobileNumber;
use App\Models\NotificationModel;
use App\Models\OtpVerifications;
use App\Models\OurContributor;
use App\Models\OurJourney;
use App\Models\QuickContactModel;
use App\Models\ScholarshipHome;
use App\Models\ScholorshipExamModel;
use App\Models\ScholorshipFormModel;
use App\Models\SliderModel;
use App\Models\State;
use App\Models\Student;
use App\Models\TermsCondition;
use App\Models\TestimonialsModel;
use App\Models\User;
use App\Models\PasswordResetModel;
use App\Models\UserRegistration;
use App\Notifications\Admin\AdminOtpSent;
use App\Notifications\MailOtp;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    protected $smsService;

    public function __construct(Msg91Service $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $sliders = SliderModel::where('status', 1)->get();

        $govtwebsites = GovtwebsiteModel::where('status', 1)->get();

        $blogNews = BlognewsModel::where('status', 1)->get();

        $notifications = NotificationModel::where('status', 1)->get();

        $courses = CourseDetailsModel::where('status', 1)->where('is_featured', 1)->select('id', 'title', 'course_logo')->get();

        $educations = ScholarshipHome::with('educationType')->where('is_featured', 1)->get();

        $ourJourneys = OurJourney::where('is_featured', 1)->get();

        $ourContributors = OurContributor::where('is_featured', 1)->get();

        $benefits = BenefitsModel::where('is_featured', 1)->get();

        $studentTestimonials = TestimonialsModel::where('type', 'student')
            ->where('status', true)
            ->orderBy('id', 'desc')
            ->with([
                'student.latestStudentCode'
            ])
            ->get();

        $corporateTestimonials = TestimonialsModel::where('type', 'corporate')->where('status', true)->orderBy('id', 'desc')->get();

        // $studentTestimonials = TestimonialsModel::where('status', 1)->where('type', 'student')->orderBy('id','desc')->get();

        // $corporateTestimonials = TestimonialsModel::where('status', 1)->where('type', 'corporate')->orderBy('id','desc')->get();

        $institudeTermsCondition = TermsCondition::where('status', 1)->where('type', 'institute')->orderBy('created_at')->first();

        return view('website.homepage', compact('institudeTermsCondition', 'corporateTestimonials', 'studentTestimonials', 'benefits', 'ourContributors', 'ourJourneys', 'sliders', 'educations', 'govtwebsites', 'courses', 'notifications', 'blogNews'));
    }

    public function companyInsert(Request $request) {}

    public function addscholorship()
    {
        return view('admin.scholarship.add');
    }

    public function scholarshipList()
    {
        $scholarshipExams = ScholorshipExamModel::all();
        $examSubjects = ExamSubject::all();
        return view('admin.scholarship.list', compact('scholarshipExams', 'examSubjects'));
    }

    public function saveScholorship(Request $request)
    {
        try {
            // Validate form data
            $request->validate([
                'examname' => 'required',
                'instruction' => 'required',
                'price' => 'required|numeric',
                'image' => 'required|image',
                'available_from' => 'required|date',
                'available_to' => 'required|date',
                'admit_Card_from' => 'required|date',
                'result_on' => 'required|date',
                'maximum_forms' => 'required|numeric',
                'name.*' => 'required',
                'totalques.*' => 'required|numeric',
                'marks.*' => 'required|numeric',
                'exam_on' => 'required|date',
            ]);

            // Store main scholarship exam data
            $scholarshipExam = new ScholorshipExamModel();
            $scholarshipExam->name = $request->input('examname');
            $scholarshipExam->instruction = $request->input('instruction');
            $scholarshipExam->price = $request->input('price');
            // Handle image upload and save path to 'image' field
            $imagePath = $request->file('image')->store('images');
            $scholarshipExam->image = $imagePath;
            $scholarshipExam->available_from = $request->input('available_from');
            $scholarshipExam->available_to = $request->input('available_to');
            $scholarshipExam->admit_Card_from = $request->input('admit_Card_from');
            $scholarshipExam->result_on = $request->input('result_on');
            $scholarshipExam->maximum_forms = $request->input('maximum_forms');
            $scholarshipExam->additional_column1 = $request->input('exam_on');
            $scholarshipExam->save();

            // Store subjects data
            foreach ($request->input('name') as $key => $name) {
                $subject = new ExamSubject();
                $subject->scholorship_exam_id = $scholarshipExam->id;
                $subject->name = $name;
                $subject->total_ques = $request->input('totalques')[$key];
                $subject->marks = $request->input('marks')[$key];
                $subject->save();
            }
            return redirect()->back();
        } catch (\Exception $e) {
            // If an exception occurs during the save operation,
            // flash an error message to the session
            // Session::flash('error', 'An error occurred while saving your data.');
            Session::flash('error', $e->getMessage());
            // Redirect back to the form with the error message
            return redirect()->back();
        }
    }

    public function couponcode()
    {
        $scholarshipForm = ScholorshipFormModel::where('user_id', Auth::user()->id)->first();
        return view('website.couponcode', compact('scholarshipForm'));
    }

    public function scholorship_insert(Request $request)
    {
        try {
            // Validate the incoming data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'fathers_name' => 'required|string',
                'dob' => 'required|date',
                'gender' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust validation rules according to your needs
                'sign' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust validation rules according to your needs
                'disability' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'qualification' => 'required|string',
                'participate_exam' => 'required|string',
                'center1' => 'required|string',
                'center2' => 'required|string',
                'center3' => 'required|string',
                'exam1' => 'required|string',
                'exam2' => 'nullable|string',
                'exam3' => 'nullable|string',
                'year' => 'nullable|string',
                'roll_no' => 'nullable|string',
                'family_income' => 'required|string',
                'occupation' => 'required|string',
                'privacy_policy' => 'required|accepted',
            ]);

            // Create a new instance of the model
            $scholarshipForm = ScholorshipFormModel::where('user_id', Auth::user()->id)->first();

            if ($scholarshipForm) {
                // Update the existing form
                $scholarshipForm->update($validatedData);
                return redirect()->route('home.couponcode');
            } else {
                // Create a new form
                $scholarshipForm = new ScholorshipFormModel();
                // Assign values from the request to the model instance
                $scholarshipForm->user_id = Auth::user()->id;
                $scholarshipForm->name = $validatedData['name'];
                $scholarshipForm->fathersname = $validatedData['fathers_name'];
                $scholarshipForm->dob = $validatedData['dob'];
                $scholarshipForm->gender = $validatedData['gender'];
                $scholarshipForm->image = $request->file('image')->store('images');  // Store the image file and save the path
                $scholarshipForm->sign = $request->file('sign')->store('images');  // Store the sign file and save the path
                $scholarshipForm->disability = $validatedData['disability'];
                $scholarshipForm->address = $validatedData['address'];
                $scholarshipForm->city = $validatedData['city'];
                $scholarshipForm->qualification = $validatedData['qualification'];
                $scholarshipForm->participate_exam = $validatedData['participate_exam'];
                $scholarshipForm->center1 = $validatedData['center1'];
                $scholarshipForm->center2 = $validatedData['center2'];
                $scholarshipForm->center3 = $validatedData['center3'];
                $scholarshipForm->exam1 = $validatedData['exam1'];
                $scholarshipForm->exam2 = $validatedData['exam2'];
                $scholarshipForm->exam3 = $validatedData['exam3'];
                $scholarshipForm->year = $validatedData['year'];
                $scholarshipForm->roll_no = $validatedData['roll_no'];
                $scholarshipForm->family_income = $validatedData['family_income'];
                $scholarshipForm->occupation = $validatedData['occupation'];
                $scholarshipForm->save();
                // Redirect to a success page or return a success response
                return redirect()->route('home.couponcode');
            }
        } catch (\Exception $e) {
            // If an exception occurs during the save operation,
            // flash an error message to the session
            // Session::flash('error', 'An error occurred while saving your data.');
            Session::flash('error', $e->getMessage());
            // Redirect back to the form with the error message
            return redirect()->back();
        }
    }

    public function saveCompany(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'softwareurl' => 'nullable|string',
            'companyname' => 'nullable|string',
            'shortname' => 'nullable|string',
            'cin' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust file validation rules as needed
            'pan' => 'nullable|string',
            'tan' => 'nullable|string',
            'gst' => 'nullable|string',
            'companycategory' => 'nullable|string',
            'companyclass' => 'nullable|string',
            'authorizedcapital' => 'nullable|string',
            'paidupcapital' => 'nullable|string',
            'sharenominalvalue' => 'nullable|string',
            'stateofregistration' => 'nullable|string',
            'incorporationdate' => 'nullable|date',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'landlineno' => 'nullable|string',
            'whatsappno' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'razorpay_marchent_id' => 'nullable|string',
            'razorpay_marchent_key' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_api_link' => 'nullable|string',
            'address' => 'nullable|string',
            'about' => 'nullable|string',
        ]);

        // Handle file upload if a logo is present in the request
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos');  // Adjust storage path as needed
            $validatedData['logo'] = $logoPath;
        }

        // Create a new company record
        $company = CompanyModel::where('softwareurl', $request->softwareurl)->first();

        // If the company exists, update its details; otherwise, create a new company
        if ($company) {
            // Update the existing company
            $company->update($validatedData);
        } else {
            // Create a new company
            CompanyModel::create($validatedData);
        }

        // Redirect back with success message or handle response as needed
        return redirect()->back()->with('success', 'Company details saved successfully!');
    }

    public function scholarshipForm()
    {
        $scholarshipExams = ScholorshipExamModel::all();
        $examSubjects = ExamSubject::all();
        $centers = Center::all();
        $courseList = CourseDetailsModel::all();
        return view('website.scholorship', compact('scholarshipExams', 'examSubjects', 'centers', 'courseList'));
    }

    public function contactinsert(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'full_name' => 'required|string',
            'mobile' => [
                'required',
                'numeric',
                'digits:10',
                // Rule::unique('contact_infos')->where(function ($query) {
                //     return $query->where('status', 0);
                // }),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                // Rule::unique('contact_infos')->where(function ($query) {
                //     return $query->where('status', 0);
                // }),
            ],
            'city' => 'required|string',
            'reason_contact' => 'required|string',
            'message' => 'nullable|string',
        ], [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.max' => 'The full name may not be greater than 255 characters.',
            'mobile.required' => 'The mobile number field is required.',
            'mobile.numeric' => 'The mobile number must be a number.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            // 'email.unique' => 'The email has already been taken.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'reason_contact.required' => 'The reason for contact field is required.',
            'mobile.digits' => 'Invalid Mobile Number.'
        ]);

        // Create a new contact entry and save it to the database
        $contact = new ContactInfo();
        $contact->fullname = $request->full_name;
        $contact->mobile = $request->mobile;
        $contact->email = $request->email;
        $contact->city = $request->city;
        $contact->reason_contact = $request->reason_contact;
        $contact->message = $request->message;
        $contact->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Message Submitted. Our team will contact you soon!');
    }

    public function contactInfo()
    {
        return view('Admin.Home.contactinfo');
    }

    public function faq()
    {
        $faq = FaqModel::all();
        return view('administrator.Home.faq', ['faq' => $faq]);
    }

    public function company()
    {
        $company = CompanyModel::find(1);
        return view('Admin.Home.company', compact('company'));
    }

    public function usersignup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required',
        ]);

        // Get the category name from the request
        $username = $request->input('name');
        $userEmail = $request->input('email');
        $userMobile = $request->input('mobile');
        $userPassword = $request->input('password');
        $userGender = $request->input('gender');
        $disability = $request->input('disability');
        $password = Hash::make($userPassword);
        // Create a new Category instance
        $usersignup = new UserRegistration();
        $usersignup->name = $username;
        $usersignup->email = $userEmail;
        $usersignup->mobile = $userMobile;
        $usersignup->password = $password;
        $usersignup->gender = $userGender;
        $usersignup->disability = $disability;
        $usersignup->save();

        // Create a corresponding user instance for authentication
        $user = new User();
        $user->name = $username;
        $user->email = $usersignup->email;
        $user->password = $password;
        $user->mobile = $userMobile;
        // You may need to set other attributes if required

        $user->save();

        // Log the user in after registration
        Auth::login($user);
        //  print_r($user);
        // Redirect back or return a response
        return redirect()->route('studentDashboard');
    }

    public function logout()
    {
        Auth::logout();

        // Redirect to a specific route or return a response
        return redirect()->route('logout-success')->with('success', 'You have been logged out.');
    }

    public function userLoginCheck(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('mobile', 'password');
        $remember = $request->has('remember');  // Check if the "Remember Me" checkbox is checked

        if (Auth::attempt($credentials, $remember)) {
            // Authentication successful
            // Redirect the user to the intended page or any specific route
            return redirect()->intended('/');
        } else {
            // Authentication failed
            return back()->withInput()->withErrors(['mobile' => 'Invalid credentials']);
        }
    }

    public function aboutus()
    {
        $banners = AboutUs::where('status', 1)->get();
        $bannerSectionTwos = AboutUsSectionOne::where('status', 1)->get();
        $bannerSectionThrees = AboutUsSectionThree::where('status', 1)->get();
        $bannerSectionThreeHeader = AboutUsSectionThree::whereNotNull('section_title')->whereNotNull('section_remarks')->first();

        $bannerSectionFours = AboutUsSectionFour::where('status', 1)->get();
        $bannerSectionFourHeader = AboutUsSectionFour::whereNotNull('section_title')
            ->whereNotNull('section_remarks')
            ->first();

        $bannerSectionFives = AboutUsSectionFive::where('status', 1)->get();
        $bannerSectionFiveHeader = AboutUsSectionFive::whereNotNull('section_title')
            ->whereNotNull('section_remarks')
            ->first();

        $corporateTestimonials = TestimonialsModel::where('status', 1)->where('type', 'corporate')->with(['corporate'])->get();

        $bannerSectionSixs = AboutUsSectionSix::where('status', 1)->get();
        $bannerSectionSixHeader = AboutUsSectionSix::whereNotNull('section_title')
            ->whereNotNull('section_remarks')
            ->first();

        $founderThoughts = AboutUsFounder::where('status', 1)->get();

        return view('website.aboutus', compact('founderThoughts', 'banners', 'bannerSectionTwos', 'bannerSectionThrees', 'bannerSectionThreeHeader', 'bannerSectionFours', 'bannerSectionFourHeader', 'bannerSectionFives', 'bannerSectionFiveHeader', 'corporateTestimonials', 'bannerSectionSixHeader', 'bannerSectionSixs'));
    }

    public function preparation()
    {
        $featuredCourses = CourseDetailsModel::with(['scholarshipCategory'])
            ->where('status', 1)
            ->select('id', 'scholarship_category', 'title', 'featured_image', 'is_featured', 'course_full_name', 'vacancies')
            // ->where('is_featured', 1)
            ->get();

        return view('website.preparation', compact('featuredCourses'));
    }

    public function scholarship()
    {
        $scholarShips = ScholarshipHome::with(['overview', 'educationType'])->where('is_featured', 1)->get();

        return view('website.scholarship', compact('scholarShips'));
    }

    public function contact()
    {
        $faq = FaqModel::where('status', 1)->get();
        return view('website.contact', ['faq' => $faq]);
    }

    public function faqList()
    {
        $faq = FaqModel::where('status', 1)->get();
        return view('website.faq', ['faq' => $faq]);
    }

    public function sendVerificationOtp(Request $request)
    {
        $smsService = $this->smsService;

        if ($request->form_user == 'admin') {
            $otp = mt_rand(100000, 999999);
            $user = User::where('email', trim($request->email))->first();
            
            $isAdmin = false;
            if ($user) {
                $attrs = $user->getAttributes();
                $isAdmin = (isset($attrs['roles']) && in_array($attrs['roles'], ['admin', 'superadmin', 'sub_admin', 'administrator'])) 
                        || (isset($attrs['isAdminAllowed']) && $attrs['isAdminAllowed'] == 1);
            }

            if (!$user || !$isAdmin) {
                \Log::warning('Admin Login: Account not found or not authorized for email ' . $request->email);
                return response()->json(['status' => false, 'message' => 'Admin account not found.']);
            }

            $admin = $user;

            $mobileNumber = preg_replace('/[^0-9]/', '', $admin->mobile);
            
            // Normalize to 10 digits if it has a country code (like 91...)
            if (strlen($mobileNumber) > 10) {
                $mobileNumber = substr($mobileNumber, -10);
            }

            if (empty($mobileNumber) || strlen($mobileNumber) != 10) {
                \Log::error('Admin Login: Invalid mobile number for email ' . $request->email . ' - Processed Number: ' . $mobileNumber);
                return response()->json(['status' => false, 'message' => 'Admin does not have a valid 10-digit mobile number.']);
            }

            \Log::info('Admin Login: Attempting to send OTP to ' . $mobileNumber . ' for email ' . $request->email);
            $smsService->sendSms($mobileNumber, $otp);
 
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
 
            $admin->notify(new AdminOtpSent($otp, $ipAddress, $userAgent));

            return response()->json(['status' => true, 'message' => 'OTP Sent Successfully to ' . maskMobile($mobileNumber)]);
        }

        $mobileNumber = $request->mobile;

        $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));

        $verifiedMobile = MobileNumber::where('mobile', $mobileNumber)->where('isOtpRequired', 1)->first();

        if ($verifiedMobile || ($request->otp && verifyOtp($request->otp, $mobileNumber))) {
            return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.']);
        }

        $otpVerifications = OtpVerifications::where('credential', $mobileNumber)
            ->where('created_at', '>=', $time)
            ->first();

        // send once in only 10 minutes

        if ($request->form_name == 'otp_verify') {
            $otp = $request->otp;
            if (is_null($otp)) {
                return response()->json(['status' => false, 'message' => 'Please Enter OTP.']);
            }

            if (verifyOtp($otp, $mobileNumber)) {
                return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.']);
            }

            return response()->json(['status' => false, 'message' => 'Invalid Otp.']);
        }
        if ($request->form_user == 'forgetPassword') {
            $student = Student::where('mobile', $mobileNumber)->first();

            if (is_null($student)) {
                return response()->json(['status' => false, 'message' => 'You are not registered.']);
            }
        }

        if (is_null($otpVerifications)) {
            $otp = mt_rand(100000, 999999);
            $smsService->sendSms($mobileNumber, $otp);

            return response()->json(['status' => true, 'message' => 'Otp sent Successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid attempt.']);
        }
    }

    public function CorporateSendVerificationOtp(Request $request)
    {
        $smsService = $this->smsService;

        $mobileNumber = $request->mobile;

        $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));

        $verifiedMobile = MobileNumber::where('mobile', $mobileNumber)->where('isOtpRequired', 1)->first();

        $otpVerifications = OtpVerifications::where('credential', $mobileNumber)
            ->where('created_at', '>=', $time)
            ->first();

        if ($verifiedMobile) {
            return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.']);
        }

        $otpVerifications = OtpVerifications::where('credential', $mobileNumber)
            ->where('created_at', '>=', $time)
            ->first();
        // send once in only 10 minutes

        if ('otp_verify' == $request->form_name) {
            $otp = $request->otp;
            if (is_null($otp)) {
                return response()->json(['status' => false, 'message' => 'Please Enter OTP.']);
            }

            if (verifyOtp($otp, $mobileNumber)) {
                return response()->json(['status' => true, 'message' => 'Otp Verified Successfully.']);
            }

            return response()->json(['status' => false, 'message' => 'Invalid Otp.']);
        }
        if ($request->form_user == 'forgetPassword') {
            $student = Corporate::where('phone', $mobileNumber)->first();

            if (is_null($student)) {
                return response()->json(['status' => false, 'message' => 'You are not registered.']);
            }

            $corporateEmail = $student->email;
        }

        if (is_null($otpVerifications)) {
            $otp = mt_rand(100000, 999999);
            $smsService->sendSms($mobileNumber, $otp);

            Mail::to($corporateEmail)->send(new OTPCorporateMail($otp));

            return response()->json(['status' => true, 'message' => 'Otp sent Successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Try again afetr 10 min.']);
        }
    }

    public function sendMail(Request $request)
    {
        $otp = mt_rand(100000, 999999);

        $email = $request->input('email');
        // Save OTP in session
        $request->session()->put('otp', $otp);
        $request->session()->put('email', $email);

        // Mail::to($email)->send(new OTPMail($otp));
        Mail::to('educrafteducations@gmail.com')->send(new OTPMail($otp));
        return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function enquirySubmit(Request $request)
    {
        $name = $request->name;
        $mobile = $request->mobile;
        $message = $request->message;
        $enq = new QuickContactModel();
        $enq->name = $name;
        $enq->mobile = $mobile;
        $enq->message = $message;
        $enq->save();
        if ($enq) {
            return redirect()->back()->with('success', 'Enquiry Submit');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function corporateSubmit(Request $request)
    {
        // return \json_encode($request->all());
        // return response()->json(['success' => false, 'message' => 'Error occurred while processing the request.', 'request' => $request->all()], 500);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'institute_name' => 'required|string',
            'type_institution' => 'required|string',
            'interested_for' => 'required',
            'established_year' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'otp' => 'required|string',  // Ensure OTP is provided
            'address' => 'required|string',
            // 'city' => 'required|string',
            'pincode' => 'required|string|digits:6',
            'attachment' => 'nullable|image|max:2048',
            'attachment_profile' => 'nullable|image|max:2048',
            'privacy_policy' => 'required|accepted',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
        ]);
        try {
            if (!verifyOtp($request->otp, $request->phone)) {
                return response()->json(['success' => false, 'message' => 'Otp is not verified.']);
            }

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('upload2/corporate2', 'public');
                $validatedData['attachment'] = $attachmentPath;
            }

            if ($request->hasFile('attachment_profile')) {
                $attachmentProfilePath = moveFile('upload2/corporate2', $request->attachment_profile);
                $validatedData['attachment_profile'] = $attachmentProfilePath;
            }

            // Create a new Corporate instance and save the data
            $institute = new Corporate();
            $institute->forceFill($validatedData);
            $institute->interested_for = implode(',', $request->interested_for);
            $institute->is_otp_verified = true;
            $institute->save();

            return response()->json(['success' => true, 'message' => 'Corporate inquiry submitted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Error occurred while processing the request.'], 500);
        }
    }

    public function slider()
    {
        $sliders = SliderModel::all();

        return view('administrator.Home.slider', ['sliders' => $sliders]);
    }

    public function saveSlider(Request $request)
    {
        // Validate the request data
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust file types and size as needed
        ]);
        $imagePath = null;
        // Save the image file
        // $imagePath = $request->file('image')->store('slider', 'public');
        if ($request->hasFile('image')) {
            $imagePath = moveFile('home/slider', $request->file('image'));
        }
        // Create a new Category instance
        $slider = new SliderModel();
        $slider->image = $imagePath;  // Save the image path to the database
        $slider->remark = $request->remark;  // Save the image path to the database
        $slider->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function deleteSlider($id)
    {
        // Find the category by its ID
        $slider = SliderModel::find($id);

        // Check if the category exists
        if (!$slider) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        // Delete the category
        $slider->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function govtwebsite()
    {
        $website = GovtwebsiteModel::all();
        return view('administrator.Home.govtwebsite', ['website' => $website]);
    }

    public function deleteGovtwebsite($id)
    {
        $govtWebsite = GovtwebsiteModel::find($id);
        // Check if the category exists
        if (!$govtWebsite) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        // Delete the category
        $govtWebsite->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function savegovtwebsite(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'nullable|exists:govtwebsite,id',
            'website_link' => 'required',
            'image' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->id) {
            $govtWebsite = GovtwebsiteModel::find($request->id);
            $message = 'Website updated successfully!';
        } else {
            $govtWebsite = new GovtwebsiteModel();
            $message = 'Website added successfully!';
        }

        // Handle image upload using Laravel Storage
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is in storage
            if ($govtWebsite->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($govtWebsite->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($govtWebsite->image);
            }
            
            $imagePath = $request->file('image')->store('govt_websites', 'public');
            $govtWebsite->image = $imagePath;
        }

        $govtWebsite->website_link = $request->website_link;
        $govtWebsite->remark = $request->remark;
        $govtWebsite->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', $message);
    }

    public function faqSave(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'details' => 'required',
        ]);

        $title = str_replace(['<p>', '</p>'], ['', ''], $request->title);
        $details = str_replace(['<p>', '</p>'], ['', ''], $request->details);

        if ($request->id && $request->id > 0) {
            $faq = FaqModel::find($request->id);
            $message = 'Faq updated successfully!';
        } else {
            $faq = new FaqModel();
            $message = 'Faq added successfully!';
        }

        $faq->title = $title;
        $faq->details = $details;
        $faq->save();
        return redirect()->back()->with('success', $message);
    }

    public function faqDelete($id)
    {
        $faq = FaqModel::find($id);
        if (!$faq) {
            return redirect()->back()->with('error', 'Faq not found');
        } else {
            $faq->delete();
            return redirect()->back()->with('success', 'Faq Deleted');
        }
    }

    public function prospectus()
    {
        $prospectus = EProspectusModel::orderBy('created_at')->get();
        return view('administrator.Home.eprospectus', ['prospectus' => $prospectus]);
    }

    public function prospectusSave(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'e_prospectus' => 'required',
        ]);
        try {
            $eprospectus = null;
            if ($request->hasFile('e_prospectus'))
                $eprospectus = moveFile('home/eprospectus', $request->file('e_prospectus'));

            $prospectus = new EProspectusModel();
            $prospectus->title = $request->title;
            $prospectus->e_prospectus = $eprospectus;
            $prospectus->save();
            return redirect()->back()->with('success', 'prospectus added successfully!');
        } catch (\Throwable $th) {
            logger('failed:', [$th]);
            return redirect()->back()->with('error', 'prospectus failed to add!');
        }
    }

    public function prospectusDelete($id)
    {
        $prospectus = EProspectusModel::find($id);
        if (!$prospectus) {
            return redirect()->back()->with('error', 'prospectus not found');
        } else {
            $prospectus->delete();
            return redirect()->back()->with('success', 'prospectus Deleted');
        }
    }

    public function career($course)
    {
        $course = CourseDetailsModel::find(decodeId($course));

        return view('website.career', compact('course'));
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'forget_mobile' => 'required',
            'forget_otp' => 'required',
            'new_password' => 'required',
            'confirm_Password' => 'required|same:new_password',
        ]);

        $student = Student::where('mobile', $request->forget_mobile)->first();

        $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $otpVerification = OtpVerifications::where([['credential', '=', $request->forget_mobile], ['otp', '=', $request->forget_otp], ['status', '=', 1], ['created_at', '>=', $time]])->first();

        if (is_null($otpVerification)) {
            return response()->json(['success' => false, 'msg' => 'Otp expired.']);
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

    public function ourJourney(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'title' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $ourJourney = new OurJourney();

            $image = null;
            if ($request->hasFile('image'))
                $validated['image'] = moveFile('home', $request->file('image'));

            $logo = null;
            if ($request->hasFile('logo'))
                $validated['logo'] = moveFile('home', $request->file('logo'));

            $ourJourney->fill($validated);
            $ourJourney->save();

            return back()->with('success', 'Saved Successfully.');
        }

        $ourJourneys = OurJourney::orderBy('created_at')->get();
        return view('administrator.Home.ourJourney.our_journey', compact('ourJourneys'));
    }

    public function ourJourneyDelete($id)
    {
        $ourJourney = OurJourney::find($id);
        if (!$ourJourney) {
            return redirect()->back()->with('error', 'Our Journey not found.');
        }

        $ourJourney->delete();

        return redirect()->back()->with('success', 'Our Journey deleted successfully.');
    }

    public function ourContributor(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'title' => 'required',
                'link' => 'nullable|url',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $ourContributor = new OurContributor();

            $logo = null;
            if ($request->hasFile('logo'))
                $validated['logo'] = moveFile('home', $request->file('logo'));

            $ourContributor->fill($validated);
            $ourContributor->save();

            return back()->with('success', 'Saved Successfully.');
        }

        $ourContributors = OurContributor::orderBy('created_at')->get();
        return view('administrator.Home.ourContributor.our_contributor', compact('ourContributors'));
    }

    public function ourContributorDelete($id)
    {
        $ourContributor = OurContributor::find($id);
        if (!$ourContributor) {
            return redirect()->back()->with('error', 'Our Contributor not found.');
        }

        $ourContributor->delete();

        return redirect()->back()->with('success', 'Our Contributor deleted successfully.');
    }

    public function benefits(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'benefit' => 'required',
            ]);
            $benefit_txt = $request->benefit;
            $icon = $request->icon;

            $benefit = new BenefitsModel();
            $benefit->benefits = $benefit_txt;
            $benefit->icon = $icon;
            $benefit->save();

            return redirect()->back()->with('success', 'Benefit added successfully!');
        }
        $ourBenefits = BenefitsModel::orderBy('created_at')->get();
        return view('administrator.Home.benefit.benefit', compact('ourBenefits'));
    }

    public function deletebenefits($id)
    {
        $benefit = BenefitsModel::find($id);
        if (!$benefit) {
            return redirect()->back()->with('error', 'Benefits not found.');
        }

        $benefit->delete();

        return redirect()->back()->with('error', 'Benefits deleted successfully.');
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

        $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $otpVerification = OtpVerifications::where([['credential', '=', $request->forget_mobile], ['otp', '=', $request->forget_otp], ['status', '=', 1], ['created_at', '>=', $time]])->first();

        if (is_null($otpVerification)) {
            return response()->json(['success' => false, 'msg' => 'Otp expired.']);
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

    public function studentRecoverPassword($id, $reset_id)
    {
        $resetData = PasswordResetModel::where('id', $reset_id)->where('user_id', $id)->first();
        if (!$resetData || $resetData->status == 1) {
            return redirect()->route('website.homepage')->with('error', 'Invalid or expired reset link.');
        }

        $student = User::find($id);
        if (!$student) {
            return redirect()->route('website.homepage')->with('error', 'User not found.');
        }

        return view('auth.passwords.reset', [
            'token' => $reset_id,
            'email' => $student->email,
        ]);
    }
}
