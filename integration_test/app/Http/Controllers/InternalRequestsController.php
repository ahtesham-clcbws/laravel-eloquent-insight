<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\NotifyAdminInstituteSignup;
use App\Mail\NotifyAdminStudentSignup;
use App\Mail\NotifyFranchiseStudentSignup;
use App\Mail\NotifyTestUpdate;
use App\Models\Corporate;
use App\Models\FranchiseDetails;
use App\Models\OtpVerifications;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\CorporateEnquiry;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendFranchiseEmail;
use App\Mail\StudentResetCode;
use App\Models\PasswordResetModel;
use App\Models\QuestionBankModel;
use App\Models\TestQuestions;
use App\Models\BoardAgencyStateModel;
use App\Models\ClassGoupExamModel;
use App\Models\Gn_OtherExamClassDetailModel;
use App\Models\Gn_SubjectPartLessionNew;
use App\Models\SubjectPartLesson;
use App\Models\Gn_EducationClassExamAgencyBoardUniversity;
use App\Models\EducationType;
use App\Models\Subject;
use App\Models\SubjectPart;
use App\Models\Gn_DisplayClassGroupExamName;
use App\Models\Gn_DisplayExamAgencyBoardUniversity;
use App\Models\Gn_DisplayOtherExamClassDetail;
use App\Models\Gn_DisplaySubjectPart;
use App\Models\Gn_DisplaySubjectPartChapter;
use App\Models\Gn_AssignClassGroupExamName;
use App\Models\TestSections;
use App\Models\TestModal;
use App\Models\Gn_PackagePlan;
use App\Models\Studymaterial;
use Illuminate\Support\Facades\Log;
use App\Models\Gn_DisplayClassSubject;
use App\Models\GnResultSubjectMapping;
use Illuminate\Support\Facades\DB;

class InternalRequestsController extends Controller
{
    protected $returnResponse;
    public function __construct()
    {
        $this->returnResponse = ['success' => false, 'type' => 'server', 'message' => 'Server error, please try after some time.'];
    }
    public function index(Request $request)
    {
        
        //return "test";
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/custom.log'),
        ])->info('from student signup -method  index');
        if ($request->isMethod('post')) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/custom.log'),
            ])->info('from student signup -method  post ' . $request->input('form_name'));
            if ($request->input('form_name') == 'admin_login') {
                $userData = array('email' => $request->input('email'), 'password' => $request->input('password'), 'isAdminAllowed' => 1);
                if (Auth::attempt($userData)) {
                    CorporateEnquiry::generateCounts();
                    User::generateCounts();
                    return json_encode(true);
                    // return redirect()->route('administrator.dashboard')->with('loginSuccess', 'Welcome User');
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'get_cities') {
                $stateId =  $request->input('state_id');
                return getCitiesByState($stateId);
                //    $cities = City::select('id', 'name')->where('state_id', $stateId)->get();
                //    return json_encode($cities);
            }
            // code to get study material for package
            if ($request->input('form_name') == 'get_study_material') {
                $educationId =  $request->input('education_type_id');
                $classId =  $request->input('classes_group_exams_id');
                $institute_id = $request->input('institute_id');
                if($institute_id == 0)
                    $studyMaterial   = Studymaterial::where('education_type', '=', $educationId)->where('class', '=', $classId)->get();
                else
                    $studyMaterial   = Studymaterial::where('education_type', '=', $educationId)->where('class', '=', $classId)->where('institute_id', '=', $institute_id)->get();
                
                if ($studyMaterial) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $studyMaterial;
                }
                return $this->returnResponse;
                return json_encode($this->returnResponse);
            }
            // code to get test list for package
            if ($request->input('form_name') == 'get_test_package') {
                $educationId =  $request->input('education_type_id');
                $classId =  $request->input('classes_group_exams_id');
                $institute_id = $request->input('institute_id');
                $package_type = $request->input('package_type');
                if($package_type == 0){
                    $testData   = TestModal::select(['test.id','test.title','franchise_details.institute_name as institute_name'])
                                ->leftJoin('franchise_details','franchise_details.user_id','test.user_id')
                                ->where('education_type_id', '=', $educationId)
                                ->where('education_type_child_id', '=', $classId)
                                ->get();
                }else{
                    $testData   = TestModal::join('franchise_details','franchise_details.user_id','test.user_id')
                                ->where('education_type_id', '=', $educationId)
                                ->where('education_type_child_id', '=', $classId)
                                ->where('franchise_details.id', '=', $institute_id)
                                ->get();
                }
                
                if ($testData) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $testData;
                }
                return $this->returnResponse;
                return json_encode($this->returnResponse);
            }
            // code to get pakage list for create test page
            if ($request->input('form_name') == 'get_package') {
                $educationId =  $request->input('education_type_id');
                $classId =  $request->input('classes_group_exams_id');
                $test_type = $request->input('test_type');
                
                $packageData   = Gn_PackagePlan::where('education_type', '=', $educationId)->where('class', '=', $classId)->where('package_category', '=', $test_type)->get();
                
                if ($packageData) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $packageData;
                }
                return $this->returnResponse;
                return json_encode($this->returnResponse);
            }
            if ($request->input('form_name') == 'get_classes') {
                $educationId =  $request->input('education_id');
                // return getClassesByEducation($educationId);
                $classes = getClassesByEducation($educationId);
                if ($classes) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $classes;
                }
                return $this->returnResponse;
                return json_encode($this->returnResponse);
                //    $cities = City::select('id', 'name')->where('state_id', $stateId)->get();
                //    return json_encode($cities);
            }
            if ($request->input('form_name') == 'get_subject_parts') {
                // dd($request->all());
                $class_id       =  $request->input('class_id');
                $subject_id     =  $request->input('subject_id');
                $subjectParts   = SubjectPart::where('classes_group_exams_id', '=', $class_id)->where('subject_id', '=', $subject_id)->get();
                // $subjectParts = getSubjectPartsBySubject2($subject_id);
                // if ($subjectParts) {
                //     $this->returnResponse['success'] = true;
                //     $this->returnResponse['message'] = $subjectParts;
                // }
                if ($subjectParts) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $subjectParts;
                }
                return $this->returnResponse;
            }
            if ($request->input('form_name') == 'get_subject_part_lessons') {
                $subject_part_id =  $request->input('subject_part_id');
                $subjectPartLessons = getSubjectPartLessonsBySubjectPart2($subject_part_id);
                if ($subjectPartLessons) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $subjectPartLessons;
                }
                return $this->returnResponse;
            }
            if ($request->input('form_name') == 'get_boards') {
                $boards_id =  $request->input('boards_id');
                $boards = getBoardsbyClass($boards_id);
                if ($boards) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $boards;
                }
                return $this->returnResponse;
            }
            if ($request->input('form_name') == 'mobile_otp') {
                $mobileNumber =  $request->input('mobile');
                if (defaultNumberCheck($mobileNumber)) {
                    $this->returnResponse['success'] = true;
                } else {
                    // return defaultNumberCheck($this->returnResponse);
                    $time = date('Y-m-d H:i:s', strtotime('-10 minutes'));
                    $otpData = OtpVerifications::where([['type', '=', 'mobile'], ['credential', '=', $mobileNumber], ['created_at', '>', $time]])->first();
                    // send once in only 10 minutes
                    if ($otpData) {
                        $this->returnResponse['message'] = 'You already request an OTP in last 10 minutes. please wait for another attempt.';
                        return json_encode($this->returnResponse);
                    }
                    $otp = mt_rand(100000, 999999);
                    $smsSent = app(Msg91Service::class)->sendSms($mobileNumber, $otp);

                    if ($smsSent) {
                        $this->returnResponse['success'] = true;
                    }
                }
                return json_encode($this->returnResponse);
            }
            if ($request->input('form_name') == 'verify_otp') {
                $mobile =  $request->input('mobile');
                if (defaultNumberCheck($mobile)) {
                    return json_encode(true);
                }
                $otp =  $request->input('otp');
                $type =  $request->input('type');
                $time = date('Y-m-d H:i:s', strtotime('-11 minutes'));
                if (verifyOtp($otp, $mobile)) {
                    return response()->json(['status' => true, 'message' => 'Verified']);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'unique_email_check') {
                $email =  $request->input('email');
                $type =  $request->input('type');
                $query2 = false;
                $query = User::where('email', $email)->first();
                // if ($type == 'corporate') {
                $query2 = CorporateEnquiry::where('email', $email)->first();
                // }
                if ($query || $query2) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'unique_mobile_check') {
                $mobile =  $request->input('mobile');
                $type =  $request->input('type');
                $query2 = false;
                $query = User::where('mobile', $mobile)->first();
                // if ($type == 'corporate') {
                $query2 = CorporateEnquiry::where('mobile', $mobile)->first();
                // }
                if ($query || $query2) {
                    return json_encode(true);
                }
                // if ($type == 'corporate') {
                //     $query = CorporateEnquiry::where('mobile', $mobile)->first();
                //     if ($query) {
                //         return json_encode(true);
                //     }
                // }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'corporate_form') {
                $corporateDb = new CorporateEnquiry();
                $corporateDb->name =  htmlspecialchars($request->input('name'), FILTER_DEFAULT);
                $corporateDb->institute_name =  htmlspecialchars($request->input('institute_name'), FILTER_DEFAULT);
                $corporateDb->type_of_institution =  json_encode($request->input('institute_type'));
                $corporateDb->interested_for =  json_encode($request->input('interested_for'));
                $corporateDb->established_year =  htmlspecialchars($request->input('established_year'), FILTER_SANITIZE_NUMBER_INT);
                $corporateDb->email =  htmlspecialchars($request->input('email'), FILTER_SANITIZE_EMAIL);
                $corporateDb->mobile =  htmlspecialchars($request->input('contact_mobile'), FILTER_SANITIZE_NUMBER_INT);
                $corporateDb->otp =  htmlspecialchars($request->input('mobile_corporate_otp_new'), FILTER_SANITIZE_NUMBER_INT);
                // $corporateDb->address =  htmlspecialchars($request->input('address'), FILTER_DEFAULT);
                $corporateDb->state_id =  htmlspecialchars($request->input('state_id'), FILTER_SANITIZE_NUMBER_INT);
                $corporateDb->city_id =  htmlspecialchars($request->input('city_id'), FILTER_SANITIZE_NUMBER_INT);
                $corporateDb->pincode =  htmlspecialchars($request->input('pincode'), FILTER_SANITIZE_NUMBER_INT);
                $corporateDb->branch_code = generateBranchCode($request->input('institute_name'));

                if ($request->hasFile('corporate_logo')) {
                    $file = $request->file('corporate_logo');
                    $name = $file->hashName();
                    $corporateDb->photoUrl = $file->storeAs('institute', $name, 'public');
                }
                $query = $corporateDb->save();
                if ($query) {
                    if ($request->input('email')) {
                        $details = [
                            'fullname' => $request->input('name'),
                            'typeMessage' => 'Account updated.',
                            'message' => 'You are succesfully registered.'
                        ];
                        $sendMail = Mail::to($request->input('email'))->send(new SendFranchiseEmail($details));
                    }
                    CorporateEnquiry::generateCounts();
                    // send email here
                    return json_encode(true);
                }
                return json_encode(false);
                // return json_encode($corporateDb);
                // $query = $corporateDb->save();
                // if ($query) {
                //     if ($request->input('email')) {
                //         $details = [
                //             'fullname' => $request->input('name'),
                //             'typeMessage' => 'Account updated.',
                //             'message' => 'You are succesfully registered.'
                //         ];
                //         // $mailToSend = new sendFranchiseEmail($details);
                //         // $sendMail = Mail::to($request->input('email'))->send($mailToSend);
                //         \Mail::to($request->input('email'))->send(new \App\Mail\sendFranchiseEmail($details));

                //         //super admin mail notification
                //         $admin_datails = [
                //             'fullname' => $request->input('name'),
                //             'email_id' => $request->input('email'),
                //             'institute_code' => ''
                //         ];

                //         $super_admins = User::where('roles', 'superadmin')->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                //         $emails = [];
                //         foreach ($super_admins as $super_admin) {
                //             array_push($emails, $super_admin['email']);
                //         }
                //         // $superAdminMailToSend = new NotifyAdminStudentSignup($admin_datails);
                //         // $sendAdminMail = Mail::to($emails)->send($superAdminMailToSend);
                //         \Mail::to($emails)->send(new \App\Mail\NotifyAdminStudentSignup($admin_datails));
                //         Log::build([
                //             'driver' => 'single',
                //             'path' => storage_path('logs/custom.log'),
                //         ])->info($emails);
                //     }
                //     CorporateEnquiry::generateCounts();
                //     // send email here
                //     return json_encode(true);
                // }
                // return json_encode(false);
            }
            if ($request->input('form_name') == 'student_username_check') {
                $username =  $request->input('username');
                $query = User::where('username', $username)->first();
                if ($query) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'branch_code_confirm') {
                $branch_code =  $request->input('branch_code');
                $time = date('Y-m-d');
                $time .= ' 00:00:00';
                // $query = CorporateEnquiry::where([['branch_code', '=', $branch_code], ['status', '=', 'CorporateEnquiry']])->first();
                $query = CorporateEnquiry::where('branch_code',$branch_code)->where('status','activated')->first();
                // return $query;
                if ($query) {
                    return json_encode($query['institute_name']);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'registration_form') {

                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/custom.log'),
                ])->info('from student signup -form_name registration_form');
                // return json_encode($request->all());
                $userDb = new User();

                $userDb->name =  htmlspecialchars($request->input('full_name'));
                $userDb->username =  htmlspecialchars($request->input('my_username'));
                $userDb->roles =  'student';
                if ($request->input('institute_code') == '') {
                    $userDb->status =  'active';
                } else {
                    $userDb->in_franchise =  '1';
                    $userDb->franchise_code =  htmlspecialchars($request->input('institute_code'));
                }
                $userDb->email =  filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? $request->input('email') : null;
                $userDb->mobile =  filter_var($request->input('mobile_number'), FILTER_SANITIZE_NUMBER_INT);
                $userDb->password =  Hash::make($request->input('password'));
                $mail_flag = 0;
                // return json_encode($userDb);
                // $query = $userDb->save();
                    if ($userDb->save()) {

                        Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/custom.log'),
                    ])->info('from student signup -form_name registration_form user saved ' . $request->input('email') . ' ' . $request->input('full_name') . ' ' . $request->input('institute_code'));
                    if ($request->input('email')) {
                        $details = [
                            'fullname' => $request->input('full_name'),
                            'typeMessage' => 'Account updated.',
                            'message' => 'You are succesfully registered.'
                        ];

                        try {
                            //code to send institute student signup notification email
                            if ($request->input('institute_code') != '') {
                                $inst = FranchiseDetails::where('branch_code', $request->input('institute_code'))->get()->first();
                                $inst_email = User::where('id', $inst->user_id)->get(['email'])->first();
                                Log::build([
                                    'driver' => 'single',
                                    'path' => storage_path('logs/custom.log'),
                                ])->info('Institute email fro student signup ' . $request->input('institute_code') . ' ' . $inst);
                                Log::build([
                                    'driver' => 'single',
                                    'path' => storage_path('logs/custom.log'),
                                ])->info('Institute email fro student signup ' . $request->input('institute_code') . ' ' . $inst_email);
                                $inst_details = [
                                    'inst_name' => $inst->institute_name,
                                    'email_id' => $request->input('email'),
                                    'institute_code' => $request->input('institute_code'),
                                    'fullname' => $request->input('full_name')
                                ];
                                $instMailToSend = new NotifyFranchiseStudentSignup($inst_details);
                                $sendMail = Mail::to($inst_email->email)->send($instMailToSend);
                                Log::build([
                                    'driver' => 'single',
                                    'path' => storage_path('logs/custom.log'),
                                ])->info('Institute email to ' . $inst_email->email . ' for student ' . $request->input('email') . ' signup' . ' ' . count(Mail::failures()));
                            }
                            //code to send superadmin student signup notification email
                            $admin_details = [
                                'fullname' => $request->input('full_name'),
                                'email_id' => $request->input('email'),
                                'institute_code' => $request->input('institute_code')
                            ];

                            $super_admins = User::where('roles', 'superadmin')->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                            $emails = [];
                            foreach ($super_admins as $super_admin) {
                                array_push($emails, $super_admin['email']);
                            }
                            $superAdminMailToSend = new NotifyAdminStudentSignup($admin_details);
                            $sendAdminMail = Mail::to($emails)->send($superAdminMailToSend);
                            Log::build([
                                'driver' => 'single',
                                'path' => storage_path('logs/custom.log'),
                            ])->info('student signup super admin ' . implode(" ", $emails) . ' ' . count(Mail::failures()));

                            $mailToSend = new SendFranchiseEmail($details);
                            $sendMail = Mail::to($request->input('email'))->send($mailToSend);
                            Log::build([
                                'driver' => 'single',
                                'path' => storage_path('logs/custom.log'),
                            ])->info('student signup -student ' . $request->input('email') . ' ' . count(Mail::failures()));
                        } catch (\Throwable $th) {

                            $mail_flag = 1;
                            Log::build([
                                'driver' => 'single',
                                'path' => storage_path('logs/custom.log'),
                            ])->info('problem in email sending' . $th);
                        }
                    }

                    $userDetailsDb = new UserDetails();
                    $userDetailsDb->user_id =  $userDb->id;
                    $userDetailsDb->state_id       = 0;
                    $userDetailsDb->district_id    = 0;
                    $userDetailsDb->block_id       = 0;
                    $userDetailsDb->pincode        = 0;
                    $userDetailsDb->is_assigned    = 1; // Mark as assigned for students

                    if ($request->input('institute_code')) {
                        $userDetailsDb->institute_code =  htmlspecialchars($request->input('institute_code'));
                    }
                    if ($file = $request->file('user_logo')) {
                        $name = $file->hashName();
                        $userDetailsDb->photo_url = $request->file('user_logo')->storeAs('student_uploads/' . $userDb->id, $name);
                    }

                    $userDetailsDb->days = '7';
                    $userDetailsDb->education_type =  filter_var($request->input('education_type_id'), FILTER_SANITIZE_NUMBER_INT);
                    $userDetailsDb->class =  filter_var($request->input('class_group_exam_id'), FILTER_SANITIZE_NUMBER_INT);
                    $userDetailsDb->save();
                    // send email here
                    User::generateCounts();
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'login_form') {
                //return "test";
                $input = request()->all();
                $fieldType = 'username';
                if (filter_var($input['username'], FILTER_VALIDATE_EMAIL)) {
                    $fieldType = 'email';
                }
                if (filter_var($input['username'], FILTER_SANITIZE_NUMBER_INT) && strlen(filter_var($input['username'], FILTER_SANITIZE_NUMBER_INT)) == 10) {
                    $fieldType = 'mobile';
                }
                $data = [
                    $fieldType => $input['username'],
                    'password' => $input['password'],
                    'status' => 'active'
                ];
                // return json_encode(Auth::attempt($data));
                if (Auth::attempt($data)) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'branch_code_check') {
                $branch_code =  $request->input('branch_code');
                $query = CorporateEnquiry::where('branch_code', $branch_code)->where('status', 'approved')->first();
                if ($query) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'branch_mobile_check') {
                $branch_mobile =  $request->input('branch_mobile');
                $query = CorporateEnquiry::where('mobile', $branch_mobile)->where('status', 'approved')->first();
                if ($query) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'branch_email_check') {
                $branch_email =  $request->input('branch_email');
                $query = CorporateEnquiry::where('email', $branch_email)->where('status', 'approved')->first();
                if ($query) {
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'corporate_signup') {

                // dd($request->all());
                $branch_code    =  $request->input('school_code');
                $branch_mobile  =  $request->input('mobile_no');
                $branch_email   =  $request->input('verify_email');
                $password       =  $request->input('password_corporegis');

                $enquiry    = CorporateEnquiry::where('branch_code', $branch_code)->where('email', $branch_email)->where('mobile', $branch_mobile)->first();
                // return json_encode($enquiry);
                $userDb     = new User();
                $roles      = json_encode(["franchise", "franchise_creator", "franchise_publisher", "franchise_manager"]);

                $userDb->name           =  $enquiry->name;
                $userDb->username       =  $branch_email;
                $userDb->roles          = $roles;
                $userDb->is_franchise   =  1;
                $userDb->email          =  filter_var($branch_email, FILTER_VALIDATE_EMAIL);
                $userDb->mobile         =  filter_var($branch_mobile, FILTER_SANITIZE_NUMBER_INT);
                $userDb->password       =  Hash::make($password);

                // return json_encode($corporateDb);
                if ($userDb->save()) {
                    $userDetailsDb                      = new FranchiseDetails();
                    $userDetailsDb->user_id             =  $userDb->id;
                    $userDetailsDb->branch_code         =  $enquiry->branch_code;
                    $userDetailsDb->institute_name      =  $enquiry->institute_name;
                    $userDetailsDb->interested_for      =  $enquiry->interested_for;
                    $userDetailsDb->established_year    =  $enquiry->established_year;
                    $userDetailsDb->address             =  $enquiry->address;
                    $userDetailsDb->city_id             =  $enquiry->city_id;
                    $userDetailsDb->state_id            =  $enquiry->state_id;
                    $userDetailsDb->pincode             =  $enquiry->pincode;
                    // $userDetailsDb->logo =  $enquiry->id;
                    $userDetailsDb->enquiry_id          =  $enquiry->id;
                    $userDetailsDb->type_of_institution =  $enquiry->type_of_institution;
                    // $userDetailsDb->franchise_types =  $enquiry['id'];
                    $userDetailsDb->is_assigned         =  0; 
                    $userDetailsDb->save();

                    $enquiry->status = 'converted';
                    $enquiry->save();

                    try {
                        //send email to super admin 
                        $admin_datails = [
                            'fullname' => $enquiry->name,
                            'email_id' => $branch_email,
                            'institute_code' => $enquiry->branch_code
                        ];

                        $super_admins = User::where('roles', 'superadmin')->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                        $emails = [];
                        foreach ($super_admins as $super_admin) {
                            array_push($emails, $super_admin['email']);
                        }
                        $superAdminMailToSend = new NotifyAdminInstituteSignup($admin_datails);
                        $sendAdminMail = Mail::to($emails)->send($superAdminMailToSend);
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('Institute signup' . implode(" ", $emails) . ' ' . count(Mail::failures()));
                    } catch (\Throwable $th) {
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('problem in email sending' . $th);
                    }
                    CorporateEnquiry::generateCounts();
                    User::generateCounts();
                    // send email here
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'submit_question') {
                // return json_encode($request->all());
                $questionBankDb     = new QuestionBankModel();
                if ($request->input('id')) {
                    $questionBankDb = QuestionBankModel::find($request->input('id'));
                }
                $questionBankDb->education_type_id          = $request->input('education_type_id') ? filter_var($request->input('education_type_id'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->class_group_exam_id        = $request->input('class_group_exam_id') ? filter_var($request->input('class_group_exam_id'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->board_agency_state_id      = $request->input('board_agency_state_id') ? filter_var($request->input('board_agency_state_id'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->subject                    = $request->input('subject') ? filter_var($request->input('subject'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->subject_part               = $request->input('subject_part') ? filter_var($request->input('subject_part'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->subject_lesson_chapter     = $request->input('subject_lesson_chapter') ? filter_var($request->input('subject_lesson_chapter'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->question_type              = $request->input('question_type') ? filter_var($request->input('question_type'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->mcq_answer                 = $request->input('mcq_answer') ? htmlspecialchars($request->input('mcq_answer')) : NULL;
                $questionBankDb->mcq_options                = $request->input('mcq_options') ? filter_var($request->input('mcq_options'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->question                   = $request->input('question') ? htmlspecialchars($request->input('question')) : NULL;
                $questionBankDb->solution                   = $request->input('solution') ? htmlspecialchars($request->input('solution')) : NULL;
                $questionBankDb->explanation                = $request->input('explanation') ? htmlspecialchars($request->input('explanation')) : NULL;
                $questionBankDb->ans_1                      = $request->input('ans_1') ? htmlspecialchars($request->input('ans_1')) : NULL;
                $questionBankDb->ans_2                      = $request->input('ans_2') ? htmlspecialchars($request->input('ans_2')) : NULL;
                $questionBankDb->ans_3                      = $request->input('ans_3') ? htmlspecialchars($request->input('ans_3')) : NULL;
                $questionBankDb->ans_4                      = $request->input('ans_4') ? htmlspecialchars($request->input('ans_4')) : NULL;
                $questionBankDb->ans_5                      = $request->input('ans_5') ? htmlspecialchars($request->input('ans_5')) : NULL;
                $questionBankDb->alloted_for_check_id       = $request->input('alloted_for_check_id') ? filter_var($request->input('alloted_for_check_id'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                $questionBankDb->creator_id                 = $request->input('creator_id') ? filter_var($request->input('creator_id'), FILTER_SANITIZE_NUMBER_INT) : NULL;
                if ($questionBankDb->save()) {
                    if (!isset($request->form_type)) {
                        if ($request->input('section_id')) {
                            $testQuestionDb = new TestQuestions();
                            $testQuestionDb->section_id     = $request->input('section_id');
                            $testQuestionDb->creator_id     = $request->input('creator_id');
                            $testQuestionDb->test_id        = $request->input('test_id');
                            $testQuestionDb->question_id    = $questionBankDb->id;
                            $testQuestionDb->save();
                        }
                    }
                    return json_encode(true);
                }
                return json_encode(false);
            }
            if ($request->input('form_name') == 'student_forget') {
                $input = request()->all();
                // return json_encode($input);
                $student = User::where('email', $input['forget_email'])->where('roles', 'student')->first();
                if ($student) {
                    $passwordResetModel = new PasswordResetModel();
                    $passwordResetModel->user_id        = $student['id'];
                    $passwordResetModel->user_type      = 'student';
                    $passwordResetModel->verify_type    = 'email';
                    $code = mt_rand(100000, 999999);
                    $passwordResetModel->code = $code;

                    $savePasswordRequest = $passwordResetModel->save();

                    $verifyLink = route('student_recover_password', [$student->id, $passwordResetModel->id]);
                    $details = [
                        'name'          => $student['name'],
                        'verifyLink'    => $verifyLink,
                        'resetCode'     => $code
                    ];
                    $mailToSend = new StudentResetCode($details);
                    Mail::to($input['forget_email'])->send($mailToSend);

                    $mailErrors = count(Mail::failures());

                    if ($savePasswordRequest && !$mailErrors) {
                        return json_encode('true');
                    } else {
                        return json_encode(Mail::failures());
                    }
                } else {
                    return json_encode('NA');
                }
                return json_encode('error');
            }
            if ($request->input('form_name') == 'student_reset_form') {
                $input = request()->all();
                // return json_encode($input);

                $resetData = PasswordResetModel::find($input['reset_id']);
                $studentData = User::where('email', $input['student_email'])->where('roles', 'student')->first();

                if ($resetData && $studentData) {
                    $resetData->status = 1;
                    $resetData->completed_at = date('Y-m-d H:s:i', time());
                    $resetSave = $resetData->save();
                    $studentData->password = Hash::make($input['student_password']);
                    $studentSave = $studentData->save();

                    if ($resetSave && $studentSave) {
                        return json_encode('true');
                    }
                    return json_encode('error');
                } else {
                    return json_encode('NA');
                }
                return json_encode('error');
            }
            if ($request->input('form_name') == 'credentials_check') {
                $inputs = $request->all();
                unset($input['form_name']);

                $alreadyHave = false;

                if ($request->input('username')) {
                    $data = User::select('id')->where('username', $request->input('username'))->first();
                    if ($data) {
                        $alreadyHave = true;
                    }
                } else {
                    $data = User::select('id')->where($inputs)->first();
                    if ($data) {
                        $alreadyHave = true;
                    }
                    $data = CorporateEnquiry::select('id')->where($inputs)->first();
                    if ($data) {
                        $alreadyHave = true;
                    }
                }

                return json_encode($alreadyHave);
            }

            // only app functions
            if ($request->input('form_name') == 'verify_token') {
                $input = request()->all();
                if (User::where(['username' => $input['username'], 'remember_token' => $input['token']])->first()) {
                    $this->returnResponse['success'] = true;
                } else {
                    $this->returnResponse['message'] = 'Token Expired.';
                }
                return json_encode($this->returnResponse);
            }
            if ($request->input('form_name') == 'app_user_registration') {
                // return json_encode($request->all());

                // $emailCheck = User::where('email', $request->input('email'))->first();
                // return json_encode($emailCheck);
                // first check email, mobile & username validation
                $emailCheck = null;
                if ($request->input('email') != "") {
                    $emailCheck = User::where('email', $request->input('email'))->first();
                }
                $mobileCheck = User::where('mobile', $request->input('mobile'))->first();
                // return json_encode($mobileCheck);
                if ($emailCheck != null || $mobileCheck != null) {
                    if ($emailCheck) {
                        $this->returnResponse['type'] = 'email';
                    }
                    if ($mobileCheck) {
                        $this->returnResponse['type'] = 'mobile';
                    }
                    if ($emailCheck != null && $mobileCheck != null) {
                        $this->returnResponse['type'] = 'both';
                    }
                    return json_encode($this->returnResponse);
                }
                $userDb = new User();

                $userDb->name =  htmlspecialchars($request->input('name'), FILTER_DEFAULT);
                // $userDb->username =  htmlspecialchars($request->input('my_username'), FILTER_DEFAULT);
                $userDb->roles =  'student';
                $userDb->device_type =  htmlspecialchars($request->input('device_type'), FILTER_DEFAULT);
                $userDb->device_id =  htmlspecialchars($request->input('device_id'), FILTER_DEFAULT);
                if ($request->input('institute_code') == '') {
                    $userDb->status =  'active';
                } else {
                    $userDb->in_franchise =  1;
                    $userDb->franchise_code =  htmlspecialchars($request->input('institute_code'));
                }
                if ($request->input('email') != "") {
                    $userDb->email =  htmlspecialchars($request->input('email'), FILTER_VALIDATE_EMAIL);
                }
                $userDb->mobile =  htmlspecialchars($request->input('mobile'), FILTER_SANITIZE_NUMBER_INT);
                $userDb->password =  Hash::make($request->input('password'));

                // return json_encode($userDb);
                // $query = $userDb->save();
                if ($userDb->save()) {
                    $userDetailsDb = new UserDetails();
                    $userDetailsDb->user_id =  $userDb->id;
                    if ($request->input('institute_code')) {
                        $userDetailsDb->institute_code =  htmlspecialchars($request->input('institute_code'));
                    }
                    $userDetailsDb->save();
                    $this->returnResponse['success'] = true;
                    if ($request->input('institute_code')) {
                        $this->returnResponse['message'] = 'You are successfully registered, please contact your institute for activating your account.';
                    } else {
                        $this->returnResponse['message'] = 'You are successfully registered, please login and activate your account by verifying mobile number';
                    }
                    // send email here
                    return json_encode($this->returnResponse);
                }
                return json_encode($this->returnResponse);
            }
            if ($request->input('form_name') == 'app_login') {
                $input = request()->all();
                $remember = true;
                if (htmlspecialchars($input['username'], FILTER_VALIDATE_EMAIL)) {
                    $fieldType = 'email';
                } elseif (htmlspecialchars($input['username'], FILTER_VALIDATE_INT) && strlen(htmlspecialchars($input['username'], FILTER_VALIDATE_INT)) == 10) {
                    $fieldType = 'mobile';
                } else {
                    $fieldType = 'username';
                }
                if ($thisUser = User::where([$fieldType => $input['username'], 'is_franchise' => 0, 'is_staff' => 0, 'isAdminAllowed' => 0])->first()) {
                    if ($thisUser['status'] == 'inactive') {
                        $returnResponse['message'] = 'Your are not active yet, please contact institute for your account activation.';
                    } elseif ($thisUser['status'] == 'banned') {
                        $returnResponse['message'] = 'Your are banned, please contact institute for your account activation.';
                    } elseif ($thisUser['status'] == 'expired') {
                        $returnResponse['message'] = 'Your account is expired, please contact institute for your account activation.';
                    } elseif ($thisUser['status'] == 'active') {
                        $data = request()->validate([
                            $fieldType => 'required',
                            'password' => 'required'
                        ]);
                        $data['is_franchise'] = 0;
                        $data['is_staff'] = 0;
                        $data['isAdminAllowed'] = 0;
                        $data['status'] = 'active';
                        // return json_encode(Auth::attempt($data, $remember));
                        if (Auth::attempt($data, $remember)) {
                            $returnResponse['success'] = true;
                            $userData = Auth::user();
                            $userData['user_details'] = UserDetails::where('user_id', $userData->id)->first();
                            $returnResponse['message'] = $userData;
                        } else {
                            $returnResponse['message'] = 'Unable to login, please contact Admnistration.';
                        }
                    }
                } else {
                    $returnResponse['message'] = 'User not found, please check your credentials.';
                }
                return json_encode($returnResponse);
            }

            if ($request->input('form_name') == 'get_class_group_exam') {

                $class_group_exam = ClassGoupExamModel::where('education_type_id', $request->education_type_id)->get();
                if ($class_group_exam) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $class_group_exam;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_agency_board_university') {
                if (gettype($request->classes_group_exams_id) == 'array') {
                    // $agency_board_university = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id',$request->education_type_id)->where('classes_group_exams_id',$request->classes_group_exams_id)->get()->pluck('board_agency_exam_id')->toArray();
                    // if (!empty($agency_board_university)) {
                    //     $board_data = BoardAgencyStateModel::whereIn('id',$data)->get();
                    //     if ($board_data) {
                    //         $this->returnResponse['success'] = true;
                    //         $this->returnResponse['message'] = $board_data;
                    //     }
                    //     return $this->returnResponse;
                    // }
                } else {
                    $agency_board_university = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $request->education_type_id)->where('classes_group_exams_id', $request->classes_group_exams_id)->get()->pluck('board_agency_exam_id')->toArray();
                    // dd($agency_board_university);
                    if (!empty($agency_board_university)) {
                        $board_data = BoardAgencyStateModel::whereIn('id', $agency_board_university)->get();
                        if ($board_data) {
                            $this->returnResponse['success'] = true;
                            $this->returnResponse['message'] = $board_data;
                        }
                        return $this->returnResponse;
                    }
                    // $agency_board_university = BoardAgencyStateModel::where('education_type_id',$request->education_type_id)->where(['classes_group_exams_id'=>$request->classes_group_exams_id])->get();
                }

                if ($agency_board_university) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $agency_board_university;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_agency_board_qualification') {
  
                    $agency_board_university = Gn_OtherExamClassDetailModel::where('education_type_id', $request->scholarshipCat)->where('classes_group_exams_id', $request->educationType)->where('agency_board_university_id',$request->qualification)->get();
                  
                    if (!empty($agency_board_university)) {
            
                            $this->returnResponse['success'] = true;
                            $this->returnResponse['message'] = $agency_board_university;
                        
                        return $this->returnResponse;
                    }
                

                if ($agency_board_university) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $agency_board_university;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_other_exam_class_detail') {
                // dd($request->all());
                if (gettype($request->classes_group_exams_id) == 'array') {
                    $other_exam_class_detail = Gn_OtherExamClassDetailModel::where('education_type_id', $request->education_type_id)->whereIn('classes_group_exams_id', $request->classes_group_exams_id)->whereIn('agency_board_university_id', $request->class_boards_id)->get();
                } else {
                    $other_exam_class_detail = Gn_OtherExamClassDetailModel::where('education_type_id', $request->education_type_id)->where('classes_group_exams_id', $request->classes_group_exams_id)->where('agency_board_university_id', $request->class_boards_id)->get();
                }
                if ($other_exam_class_detail) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $other_exam_class_detail;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_subject_parts_chapter') {
                $subject_data = SubjectPartLesson::where('subject_id', $request->gn_lesson_subject_id)->where('subject_part_id', $request->subject_part_id)->get();
                if ($subject_data) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $subject_data;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_chapter_lession') {
                // dd($request->all());
                $lession_data = Gn_SubjectPartLessionNew::where('subject_id', $request->gn_lesson_subject_id)->where('subject_part_id', $request->subject_part_id)->where('subject_chapter_id', $request->gn_lesson_chapter_id)->get();
                // dd($lession_data);
                if ($lession_data) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $lession_data;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'gn_get_agency_board_university') {
                $data = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $request->education_type_id)->whereIn('classes_group_exams_id', $request->classes_group_exams_id)->get()->pluck('board_agency_exam_id')->toArray();
                if (!empty($data)) {
                    $board_data = BoardAgencyStateModel::whereIn('id', $data)->get();
                    if ($board_data) {
                        $this->returnResponse['success'] = true;
                        $this->returnResponse['message'] = $board_data;
                    }
                    return $this->returnResponse;
                }
                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = $data;
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteEducationType') {
                $education              = EducationType::where('id', $request->id);

                $class_education                = ClassGoupExamModel::where('education_type_id', $request->id);
                $assign_class                   = Gn_AssignClassGroupExamName::where('education_type_id', $request->id);
                $Gn_DisplayClassGroupExamName   = Gn_DisplayClassGroupExamName::where('education_type_id', $request->id);

                $board_education                        = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $request->id);
                $other_exam_education                   = Gn_OtherExamClassDetailModel::where('education_type_id', $request->id);
                $gn_DisplayExamAgencyBoardUniversity    = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', $request->id);
                $gn_ohter_exam_display                  = Gn_DisplayOtherExamClassDetail::where('education_type_id', $request->id);

                // $class_education        = ClassGoupExamModel::where('education_type_id',$request->id);
                // $board_education        = BoardAgencyStateModel::where('education_type_id',$request->id);
                // $other_exam_education   = Gn_OtherExamClassDetailModel::where('education_type_id',$request->id);

                $education->delete();

                $class_education->delete();
                $assign_class->delete();
                $Gn_DisplayClassGroupExamName->delete();

                $board_education->delete();
                $other_exam_education->delete();
                $gn_DisplayExamAgencyBoardUniversity->delete();
                $gn_ohter_exam_display->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteClass') {
                # code...
                $class_education                = ClassGoupExamModel::where('education_type_id', $request->education_id);
                $assign_class                   = Gn_AssignClassGroupExamName::where('education_type_id', $request->education_id);
                $Gn_DisplayClassGroupExamName   = Gn_DisplayClassGroupExamName::where('education_type_id', $request->education_id);

                $board_education                        = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $request->education_id);
                $other_exam_education                   = Gn_OtherExamClassDetailModel::where('education_type_id', $request->education_id);
                $gn_DisplayExamAgencyBoardUniversity    = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', $request->education_id);
                $gn_ohter_exam_display                  = Gn_DisplayOtherExamClassDetail::where('education_type_id', $request->education_id);

                // $education->delete();
                $class_education->delete();
                $assign_class->delete();
                $Gn_DisplayClassGroupExamName->delete();

                $board_education->delete();
                $other_exam_education->delete();
                $gn_DisplayExamAgencyBoardUniversity->delete();
                $gn_ohter_exam_display->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteExamAgencyBoard') {

                $board_education                        = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $request->education_id)->where('classes_group_exams_id', $request->class_id);
                $other_exam_education                   = Gn_OtherExamClassDetailModel::where('education_type_id', $request->education_id)->where('classes_group_exams_id', $request->class_id);
                $gn_DisplayExamAgencyBoardUniversity    = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', $request->education_id)->where('classes_group_exams_id', $request->class_id);
                $gn_ohter_exam_display                  = Gn_DisplayOtherExamClassDetail::where('education_type_id', $request->education_id)->where('classes_group_exams_id', $request->class_id);

                $board_education->delete();
                $other_exam_education->delete();
                $gn_DisplayExamAgencyBoardUniversity->delete();
                $gn_ohter_exam_display->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteOtherExamClass') {
                $other_exam_education   = Gn_OtherExamClassDetailModel::where('education_type_id', $request->education_type_id)->where('classes_group_exams_id', $request->classes_group_exams_id)->where('agency_board_university_id', $request->agency_board_university_id);
                $gn_ohter_exam_display  = Gn_DisplayOtherExamClassDetail::where('education_type_id', $request->education_type_id)->where('classes_group_exams_id', $request->classes_group_exams_id)->where('agency_board_university_id', $request->agency_board_university_id);
                $other_exam_education->delete();
                $gn_ohter_exam_display->delete();
                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }
            if ($request->input('form_name') == 'deleteSubject') {

                $Gn_DisplayClassSubject =     Gn_DisplayClassSubject::where('id', $request->id);
                // $subject                    = Subject::where('id', $request->id);
                // $subjectPart                = SubjectPart::where('subject_id', $request->subject_id);
                // $subjectPartChapter         = SubjectPartLesson::where('subject_id', $request->subject_id);
                // $subjectPartLession         = Gn_SubjectPartLessionNew::where('subject_id', $request->subject_id);
                // $gn_subject_part_display    = Gn_DisplaySubjectPart::where('subject_id', $request->subject_id);
                // $gn_subject_chapter_display = Gn_DisplaySubjectPartChapter::where('subject_id', $request->subject_id);
                
                // return $request->subject_id;

                $Gn_DisplayClassSubject->delete();
                // $subjectPart->delete();
                // $subjectPartChapter->delete();
                // $subjectPartLession->delete();
                // $gn_subject_part_display->delete();
                // $gn_subject_chapter_display->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteSubjectpart') {

                $subjectPart = SubjectPart::where('subject_id', $request->subject_id);
                $subjectPartChapter = SubjectPartLesson::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id);
                $subjectPartLession = Gn_SubjectPartLessionNew::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id);

                $gn_subject_part_display    = Gn_DisplaySubjectPart::where('subject_id', $request->subject_id);
                $gn_subject_chapter_display = Gn_DisplaySubjectPartChapter::where('subject_id', $request->subject_id);

                $subjectPart->delete();
                $subjectPartChapter->delete();
                $subjectPartLession->delete();
                $gn_subject_part_display->delete();
                $gn_subject_chapter_display->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteSubjectPartChapter') {
                $subjectPartChapter = SubjectPartLesson::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id);
                $subjectPartLession = Gn_SubjectPartLessionNew::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id)->where('subject_chapter_id', $request->subject_chapter);
                $gn_subject_chapter_display = Gn_DisplaySubjectPartChapter::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id);

                $subjectPartChapter->delete();
                $subjectPartLession->delete();
                $gn_subject_chapter_display->delete();
                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'deleteSubjectPartLession') {

                $subjectPartLession = Gn_SubjectPartLessionNew::where('id', $request->subject_lession)->where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id)->where('subject_chapter_id', $request->subject_chapter);

                $subjectPartLession->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'success';
                return $this->returnResponse;
            }

            if ($request->input('form_name') == "get_subject_chapter_lession") {
                $subjectPartLession = Gn_SubjectPartLessionNew::where('subject_id', $request->subject_id)->where('subject_part_id', $request->subject_part_id)->where('subject_chapter_id', $request->subject_chapter_id)->get();
                if ($subjectPartLession) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $subjectPartLession;
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'add_questions_from_qb') {
                $test_questions = new TestQuestions();
                $test_questions->test_id        = $request->test_id;
                $test_questions->section_id     = $request->section_id;
                $test_questions->creator_id     = $request->creater_id;
                $test_questions->question_id    = $request->question_id;
                $test_questions->save();

                if ($test_questions) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = 'Question Added';
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'remove_questions_from_test') {
                $test_question = TestQuestions::where('id', $request->id);
                $test_question->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'Question deleted';
            }

            if ($request->input('form_name') == 'remove_test') {
                $test_question  = TestQuestions::where('test_id', $request->id);
                $test_section   = TestSections::where('test_id', $request->id);
                $test_modal     = TestModal::where('id', $request->id);

                $test_question->delete();
                $test_section->delete();
                $test_modal->delete();

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'Test deleted';
            }

            if ($request->input('form_name') == 'deleteresultSubjectMap') {
                $model = GnResultSubjectMapping::find($request->id);

                DB::table('student_paper_exporteds')->where('subject_mapping_id',$request->id)->delete();
                DB::table('subject_paper_details')->where('subject_mapping_id',$request->id)->delete();
                
                $model->delete(); 

                $this->returnResponse['success'] = true;
                $this->returnResponse['message'] = 'Test deleted';
            }
            if ($request->input('form_name') == 'sent_to_publisher') {
                $test_section                       = TestSections::find($request->id);
                $test_section->sent_to_publisher    = 1;
                $test_section->save();

                if ($test_section) {
                    try {
                        $test_details = [
                            'subject' => 'Test created for ' . $test_section['subject']
                        ];

                        //send email to super admin
                        $super_admins = User::where('roles', 'superadmin')->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                        $emails = [];
                        foreach ($super_admins as $super_admin) {
                            array_push($emails, $super_admin['email']);
                        }
                        $superAdminMailToSend = new NotifyTestUpdate($test_details);
                        $sendAdminMail = Mail::to($emails)->send($superAdminMailToSend);
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('sent_to_publisher super admin ' . implode(" ", $emails) . ' ' . count(Mail::failures()));
                        //send email to publisher
                        $users = User::whereIn('id', [$test_section['publisher_id']])->where('status', 'active')->where('deleted_at', null)->get(['email', 'franchise_code'])->first();
                        $mailToSend = new NotifyTestUpdate($test_details);
                        $sendMail = Mail::to($users['email'])->send($mailToSend);
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('sent_to_publisher publisher' . $users['email'] . ' ' . count(Mail::failures()));
                        //send email to institute
                        if ($users['franchise_code']) {
                            $inst = FranchiseDetails::where('branch_code', $users['franchise_code'])->get()->first();
                        } else {
                            $test = TestModal::where('id', $test_section['test_id'])->get()->first();
                            $inst = FranchiseDetails::where('user_id', $test['user_id'])->get()->first();
                        }
                        $inst_email = User::where('id', $inst->user_id)->get(['email'])->first();

                        $instMailToSend = new NotifyTestUpdate($test_details);
                        $sendMail = Mail::to($inst_email->email)->send($instMailToSend);
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('sent_to_publisher inst' . $inst_email->email . ' ' . count(Mail::failures()));
                    } catch (\Throwable $th) {
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('problem in email sending' . $th);
                    }

                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = 'Section are sent to Publisher';
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'publish_test') {
                $test_section                  = TestSections::find($request->id);
                $test_section->is_published    = 1;
                $test_section->save();

                if ($test_section) {
                    $test_details = [
                        'subject' => 'Test published for ' . $test_section['subject']
                    ];
                    //super admin
                    $super_admins = User::where('roles', 'superadmin')->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                    $emails = [];
                    foreach ($super_admins as $super_admin) {
                        array_push($emails, $super_admin['email']);
                    }
                    $superAdminMailToSend = new NotifyTestUpdate($test_details);
                    $sendAdminMail = Mail::to($emails)->send($superAdminMailToSend);
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/custom.log'),
                    ])->info('publish_test super admin ' . implode(" ", $emails) . ' ' . count(Mail::failures()));

                    //creator
                    $users = User::whereIn('id', [$test_section['creator_id']])->where('status', 'active')->where('deleted_at', null)->get(['email', 'franchise_code'])->first();
                    $mailToSend = new NotifyTestUpdate($test_details);
                    $sendMail = Mail::to($users['email'])->send($mailToSend);
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/custom.log'),
                    ])->info('publish_test creator 10 ' .$users['email'] . ' ' . count(Mail::failures()));

                    $br_code = '';
                    if ($users['franchise_code']) {
                        $inst = FranchiseDetails::where('branch_code', $users['franchise_code'])->get()->first();
                        $br_code = $users['franchise_code'];
                    } else {
                        $test = TestModal::where('id', $test_section['test_id'])->get()->first();
                        $inst = FranchiseDetails::where('user_id', $test['user_id'])->get()->first();
                        $br_code = $inst->branch_code;
                    }

                    //institute

                    $inst_email = User::where('id', $inst->user_id)->get(['email'])->first();

                    $instMailToSend = new NotifyTestUpdate($test_details);
                    $sendMail = Mail::to($inst_email->email)->send($instMailToSend);
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/custom.log'),
                    ])->info('publish_test inst 10 ' .$inst_email->email . ' ' . count(Mail::failures()));

                    //students 
                    $students = User::where('roles', 'student')->where('franchise_code', $br_code)->where('status', 'active')->where('deleted_at', null)->get(['email'])->toArray();
                    $semails = [];
                    foreach ($students as $student) {
                        array_push($semails, $student['email']);
                    }
                    if (sizeof($semails) > 0) {
                        $studentMailToSend = new NotifyTestUpdate($test_details);
                        $sendMail = Mail::to($semails)->send($studentMailToSend);
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/custom.log'),
                        ])->info('publish_test student 10 ' . implode(" ", $semails) . ' ' . count(Mail::failures()));
                    }


                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = 'Section are sent to Publisher';
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = 'Section are sent to Publisher';
                }
                return $this->returnResponse;
            }

            if ($request->input('form_name') == 'get_parts_subject') {
                $subject = Subject::select('subjects.id', 'subjects.name')
                    ->leftJoin('gn__class_subjects', 'gn__class_subjects.subject_id', 'subjects.id')
                    ->where('gn__class_subjects.classes_group_exams_id', '=', $request->class_id)
                    ->get();

                if ($subject) {
                    $this->returnResponse['success'] = true;
                    $this->returnResponse['message'] = $subject;
                }
                return $this->returnResponse;
            }

        }
    }
    public function getStates()
    {
        return json_encode(getStates());
    }
    
}
