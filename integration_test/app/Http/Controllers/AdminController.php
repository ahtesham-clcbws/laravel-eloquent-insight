<?php

namespace App\Http\Controllers;

use App\Exports\StudentsSubjectMarkFillExport;
use App\Imports\StudentPapersImport;
use App\Mail\InstituteDiscountVoucherRequestCompleted;
use App\Models\AboutUs;
use App\Models\AboutUsFounder;
use App\Models\AboutUsSectionFive;
use App\Models\AboutUsSectionFour;
use App\Models\AboutUsSectionOne;
use App\Models\AboutUsSectionSix;
use App\Models\AboutUsSectionThree;
use App\Models\BlognewsModel;
use App\Models\BoardAgencyStateModel;
use App\Models\Center;
use App\Models\ClassGoupExamModel;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\CorporateCouponRequest;
use App\Models\CouponCode;
use App\Models\District;
use App\Models\EducationType;
use App\Models\EProspectusModel;
use App\Models\FaqModel;
use App\Models\Gn_DisplayExamAgencyBoardUniversity;
use App\Models\Gn_EducationClassExamAgencyBoardUniversity;
use App\Models\Gn_OtherExamClassDetailModel;
use App\Models\GnResultSubjectMapping;
use App\Models\GovtwebsiteModel;
use App\Models\MobileNumber;
use App\Models\NotificationModel;
use App\Models\Image;
use App\Models\OurContributor;
use App\Models\OurJourney;
use App\Models\PasswordResetModel;
use App\Models\PaymentSetting;
use App\Models\PrivacyPolicy;
use App\Models\RollNumber;
use App\Models\ScholarshipHome;
use App\Models\ScholarshipHomeTwo;
use App\Models\SliderModel;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentClaimForm;
use App\Models\StudentCode;
use App\Models\StudentPaperExported;
use App\Models\Subject;
use App\Models\SubjectPaperDetail;
use App\Models\TermsCondition;
use App\Models\TestimonialsModel;
use App\Models\User;
use App\Services\StudentRankService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    protected $studentRankService;

    public function index()
    {
        $appliedCount = CouponCode::select('id')->where('is_applied', 1)->count();

        $data = [
            'newStudents' => Student::where('isNew', true)->count(),
            'totalStudents' => Student::count(),
            'newInstituteInquiry' => Corporate::where('is_approved', 0)->whereNull('signup_at')->select('id')->count(),
            'newInstituteSignup' => Corporate::where('is_approved', 1)->where('signup_approved', 0)->count(),
            'approvedInsititutes' => Corporate::where('is_approved', 1)->where('signup_approved', 1)->whereNotNull('signup_at')->count(),
            'newTestimonials' => TestimonialsModel::where('isNew', true)->count(),
            'newContactEnquiries' => ContactInfo::where('isNew', true)->count(),
            'newCouponRequests' => CorporateCouponRequest::where('status', 'pending')->count(),
            'claimedStudents' => StudentClaimForm::count(),
        ];
        return view('administrator.dashboard.home')->with($data);
    }

    public function studentList(Request $request)
    {
        $scholarshipTypes = EducationType::get();

        $cities = District::get();

        // $query = Student::query()->with('choiceCenterA');

        $query = Student::query()->with([
            'choiceCenterA',
            'latestStudentCode.corporate',
            'studentPayment',
            'district',
            'qualifications',
            'scholarShipCategory',
            'scholarShipOptedFor'
        ]);
        $classes = collect();

        $query->where('is_final_submitted', 1);

        if ($request->isMethod('POST')) {
            if (!empty($request->district_id)) {
                $query->whereIn('district_id', $request->district_id);
            }

            if (!empty($request->gender)) {
                $query->whereIn('gender', $request->gender);
            }

            if (!empty($request->class)) {
                $query->whereIn('qualification', $request->class);
                $classes = BoardAgencyStateModel::whereIn('id', $request->class)->select('id', 'name')->get();
            }

            $students = $query->with('latestStudentCode')->get();
        }

        $students = $query->orderBy('id', 'desc')->get();

        // return print_r($students->toArray());

        return view('administrator.dashboard.studentlist', [
            'students' => $students,
            'cities' => $cities,
            'scholarshipTypes' => $scholarshipTypes,
            'classes' => $classes
        ]);
    }

    public function studentClaimList(Request $request)
    {
        $scholarshipTypes = EducationType::get();
        $cities = District::get();
        
        $query = Student::query()->whereHas('studentClaimForm')->with([
            'choiceCenterA',
            'latestStudentCode.corporate',
            'studentPayment',
            'district',
            'qualifications',
            'scholarShipCategory',
            'scholarShipOptedFor',
            'studentClaimForm'
        ]);

        $classes = collect();

        if (!empty($request->district_id)) {
            $query->whereIn('district_id', $request->district_id);
        }

        if (!empty($request->gender)) {
            $query->whereIn('gender', $request->gender);
        }

        if (!empty($request->class)) {
            $query->whereIn('qualification', $request->class);
            $classes = BoardAgencyStateModel::whereIn('id', $request->class)->select('id', 'name')->get();
        }

        $students = $query->orderBy('id', 'desc')->get();

        return view('administrator.dashboard.student_claim_list', [
            'students' => $students,
            'cities' => $cities,
            'scholarshipTypes' => $scholarshipTypes,
            'classes' => $classes
        ]);
    }

    public function studentListRegistered(Request $request)
    {
        Student::where('isNew', true)->update(['isNew' => false]);
        $query = Student::query();

        $query->where('is_final_submitted', 0);

        $students = $query->with('latestStudentCode')->orderBy('id', 'desc')->get();

        foreach ($students as $key => $student) {
            $student->index = $key + 1;
            $students[$key] = $student;
        }

        return view('administrator.dashboard.students-registered', compact('students'));
    }

    // start roll number generation & list
    public function studentRollList(Request $request)
    {
        $scholarshipTypes = EducationType::get();

        $cities = District::get();

        $query = Student::query()->with([
            'choiceCenterA',
            'latestStudentCode.corporate',
            'studentPayment',
            'district',
            'qualifications',
            'scholarShipCategory',
            'scholarShipOptedFor'
        ]);

        $classes = collect();

        $query->where('is_final_submitted', 1);

        // if ($request->isMethod('POST')) {

        if (!empty($request->district_id)) {
            $query->whereIn('district_id', $request->district_id);
        }

        if (!empty($request->gender)) {
            $query->whereIn('gender', $request->gender);
        }

        if (!empty($request->class)) {
            $query->whereIn('qualification', $request->class);
            $classes = BoardAgencyStateModel::whereIn('id', $request->class)->select('id', 'name')->get();
        }

        // $students = $query->with('latestStudentCode')->get();
        // }
        $students = $query->orderBy('id', 'desc')->get();

        return view('administrator.dashboard.studentRolelist', compact('students', 'cities', 'scholarshipTypes', 'classes'));
    }

    public function studentGenerateRollNo(Request $request)
    {
        // return response()->json($request->all());
        // Fetch students based on final submission and include district relation
        $students = Student::where('is_final_submitted', 1)
            ->whereIn('district_id', $request->district_id)  // Fetch students from selected districts
            ->with('district');

        // Apply filters based on scholarship, class, and gender
        if (!empty($request->scholarship_type) && $request->scholarship_type[0] != null) {
            $scholarshipIds = array_map('intval', $request->scholarship_type);
            $students = $students->whereHas('scholarShipCategory', function ($subQuery) use ($scholarshipIds) {
                $subQuery->whereIn('id', $scholarshipIds);
            });
        }

        if (!empty($request->class) && $request->class[0] != null) {
            $classIds = array_map('intval', $request->class);
            $students = $students->whereIn('qualification', $classIds);
        }

        if (!empty($request->gender) && $request->gender[0] != null) {
            $genderIds = array_map('intval', $request->gender);
            $students = $students->whereIn('gender', $genderIds);
        }

        // Get the students after applying filters
        $students = $students->get();

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Data Available for applied Filter.'
            ]);
            return back()->with('error', 'No Data Available for applied Filter.');
        }

        // Group students by district and gender
        $groupedStudents = $this->groupStudentsByDistrictAndGender($students);

        // Get the current highest roll number (defaults to 10000000)
        $lastRollNumber = StudentCode::max('roll_no') ?? 10000000;

        // Generate roll numbers for all district and gender groups
        $generatedRollNumbers = $this->generateRollNumbersForDistricts($groupedStudents, $lastRollNumber);

        if ($generatedRollNumbers > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Roll Numbers generated successfully (' . $generatedRollNumbers . ')'
            ]);
            return redirect()->back()->with('success', 'Roll Numbers generated successfully (' . $generatedRollNumbers . ')');
        }

        return response()->json([
            'success' => false,
            'message' => '"No roll numbers generated.'
        ]);
        return back()->with('error', 'No roll numbers generated.');
    }

    private function groupStudentsByDistrictAndGender($students)
    {
        $grouped = [];

        foreach ($students as $student) {
            $districtId = $student->district->id;
            $gender = strtolower($student->gender);

            // Initialize the district and gender groups
            if (!isset($grouped[$districtId])) {
                $grouped[$districtId] = [
                    'male' => [],
                    'female' => [],
                    'transgender' => []
                ];
            }

            // Add student to the correct gender group within the district
            if (isset($grouped[$districtId][$gender])) {
                $grouped[$districtId][$gender][] = $student;
            }
        }

        return $grouped;
    }

    private function generateRollNumbersForDistricts($groupedStudents, &$lastRollNumber)
    {
        $totalGenerated = 0;

        // Loop through each district group
        foreach ($groupedStudents as $districtId => $genderGroup) {
            // Generate roll numbers for each gender group within the district
            foreach (['male', 'female', 'transgender'] as $gender) {
                if (!empty($genderGroup[$gender])) {
                    $totalGenerated += $this->generateRollNumbersForGroup($genderGroup[$gender], $lastRollNumber);
                    // Increment the roll number by the number of generated students
                    $lastRollNumber += count($genderGroup[$gender]);
                }
            }
        }

        return $totalGenerated;
    }

    private function generateRollNumbersForGroup($students, &$lastRollNumber)
    {
        $generatedCount = 0;

        foreach ($students as $student) {
            // Generate roll number for each student in the group
            $result = $this->rollNumberGenerate($student, $lastRollNumber);
            if ($result['generated']) {
                $generatedCount++;
            }
        }

        return $generatedCount;
    }

    public function rollNumberGenerate($student, &$lastRollNumber)
    {
        $studentCode = $student->latestStudentCode;

        // Assign roll number only if the student does not already have one
        if (!$studentCode->roll_no) {
            $lastRollNumber++;  // Increment the last roll number
            $studentCode->roll_no = sprintf('%08d', $lastRollNumber);  // Format roll number as 8 digits
            $studentCode->save();

            return ['generated' => true];
        }

        return ['generated' => false];
    }

    // end roll number generation & list

    public function studentView(Student $student)
    {
        $states = State::all();
        $scholarshipTypes = EducationType::get();
        $cities = District::get();
        $classexam = ClassGoupExamModel::all();
        $qualifications = BoardAgencyStateModel::all();
        return view('administrator.dashboard.student_view', compact('student', 'states', 'scholarshipTypes', 'cities', 'classexam', 'qualifications'));
    }

    public function updateCoupons(Request $request)
    {
        // Retrieve data from request
        $requestData = json_decode($request->getContent(), true);
        $prefix = $requestData['prefix'];
        $corporateId = $requestData['corporateId'];
        $enteredValue = $requestData['enteredValue'];

        // Validate input
        if (empty($enteredValue)) {
            return response()->json(['message' => 'Entered value cannot be empty.'], 400);
        }

        $corporate = Corporate::find($corporateId);

        if ($corporate) {
            $affectedRows = CouponCode::where('prefix', $prefix)
                ->where('is_issued', false)
                ->where('is_applied', false)
                ->take($enteredValue)  // Take the given number of rows
                ->update(['is_issued' => true, 'corporate_id' => $corporate->id]);

            $couponRequests = CorporateCouponRequest::where('corporate_id', $corporate->id)
                ->where('status', 'pending')
                ->update(['status' => 'completed']);

            $emailData = [
                'name' => $corporate->name,
                'institute_name' => $corporate->institute_name,
                'email' => $corporate->email,
                'city' => $corporate->district?->name ? $corporate->district->name : null
            ];
            Mail::to($corporate)->send(new InstituteDiscountVoucherRequestCompleted($emailData));

            if ($affectedRows === 0) {
                return response()->json(['message' => 'Coupon code not found Code, all Coupon code are issued.'], 404);
            } else {
                return response()->json(['message' => 'Coupons Code Issued successfully.'], 200);
            }
        }
        return response()->json(['message' => 'Corporate not find.'], 400);
    }

    public function CoprporateCouponlists(Request $request, Corporate $corporate)
    {
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

            $codeValueAmountPercent = $codeType == 'amount' ? 'Rs.' : '%';
            $codeValue = $codeValue ? $codeValue . '  ' . $codeValueAmountPercent : '';

            return response()->json(['issuedCount' => $issuedCount, 'coupons' => $filteredCoupons, 'counts' => $counts, 'appliedCount' => $appliedCount, 'codeValue' => $codeValue, 'prefix' => $prefix]);
        }
        $counts = $coupons->count();

        $appliedCount = $coupons->where('is_applied', 1)->count();

        return view('administrator.dashboard.corporate_coupon_list', compact('issuedCount', 'coupons', 'counts', 'appliedCount', 'prefix', 'codeValue', 'corporate'));
    }

    public function getClassByScholarshipType(Request $request)
    {
        if (!empty($request->ids)) {
            $ids = array_map(function ($id) {
                return intval($id);
            }, $request->ids);
            $classGroupId = Gn_EducationClassExamAgencyBoardUniversity::whereIn('education_type_id', $ids)->get()->pluck('board_agency_exam_id');
            if (!empty($classGroupId)) {
                $classes = BoardAgencyStateModel::whereIn('id', $classGroupId)->select('id', 'name')->get();
                return response()->json(['status' => true, 'data' => $classes]);
            } else {
                return response()->json(['status' => false, 'message' => 'Select Scholship category']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Select Scholship category']);
        }
    }

    public function centerList()
    {
        $centers = Center::all();

        return view('administrator.center.list_center', compact('centers'));
    }

    public function createCenter(Request $request, $id = null)
    {
        $center = null;

        if ($id) {
            $center = Center::find($id);
        }

        $states = State::all();
        return view('administrator.center.add_center', compact('center', 'states'));
    }

    public function saveCenter(Request $request)
    {
        $rules = [
            'state_id' => 'required',
            'city_id' => 'required',
        ];

        $rules['center_namea'] = 'required';
        $rules['addressa'] = 'required';
        $rules['landmarka'] = 'nullable';
        $rules['pincodea'] = 'required';

        // Conditional validation rules for optional sets (b, c)
        $optionalSets = ['b', 'c'];
        foreach ($optionalSets as $set) {
            if (!empty($request->input("center_name$set"))) {
                $rules["center_name$set"] = 'required';
                $rules["address$set"] = 'required';
                $rules["landmark$set"] = 'nullable';
                $rules["pincode$set"] = 'required';
            }
        }

        $validated = $request->validate($rules);

        $sets = ['a', 'b', 'c'];
        foreach ($sets as $set) {
            if (empty($validated["center_name$set"])) {
                continue;
            }

            // Create a new Center instance
            $center = new Center();

            // Check if adding new center and city already has 3 centers
            $centerCount = Center::where('city_id', $validated['city_id'])->count();
            if (is_null($request->id) && $centerCount >= 3) {
                return back()->withErrors('A city can have a maximum of three centers.');
            }

            // Fill and save the center details
            $center->center_name = $validated["center_name$set"];
            $center->address = $validated["address$set"];
            $center->landmark = $validated["landmark$set"];
            $center->pincode = $validated["pincode$set"];
            $center->city_id = $validated['city_id'];
            $center->state_id = $validated['state_id'];
            $center->save();
        }

        // Determine the success message
        $message = $request->id ? 'Successfully Updated center' : 'Successfully Added center';

        return back()->with('success', $message);
    }

    public function examCenterAllotment(Request $request)
    {
        $filters = [];

        $scholarshipTypes = EducationType::get();

        $cities = District::orderBy('name', 'asc')->get();

        $examCenters = Center::get();

        $preloadedClasses = [];

        $query = Student::query();
        $query = $query->where('is_final_submitted', 1)->with('studentCode');

        if (!empty($request->district_id) && $request->district_id[0] != null) {
            $districtIds = array_map(function ($id) {
                return intval($id);
            }, $request->district_id);
            $query = $query->whereIn('district_id', $districtIds);
            $filters['district'] = District::whereIn('id', $districtIds)->pluck('name');
        }
        if (!empty($request->scholarship) && $request->scholarship[0] != null) {
            // $student->scholarShipCategory->name
            $scholarshipIds = array_map(function ($id) {
                return intval($id);
            }, $request->scholarship);

            // Join the scholarShipCategory relationship and filter by scholarship IDs
            $query = $query->whereHas('scholarShipCategory', function ($subQuery) use ($scholarshipIds) {
                $subQuery->whereIn('id', $scholarshipIds);
            });

            // cget all classes

            $classGroupId = Gn_EducationClassExamAgencyBoardUniversity::whereIn('education_type_id', $scholarshipIds)->get()->pluck('board_agency_exam_id');
            $preloadedClasses = BoardAgencyStateModel::whereIn('id', $classGroupId)->select('id', 'name')->get();

            $filters['scholarship'] = EducationType::whereIn('id', $scholarshipIds)->pluck('name');
        }
        if (!empty($request->class) && $request->class[0] != null) {
            $classIds = array_map(function ($id) {
                return intval($id);
            }, $request->class);
            $query = $query->whereIn('qualification', $classIds);
            // $preloadedClasses = BoardAgencyStateModel::whereIn('id', $request->class)->select('id', 'name')->get();
            $filters['class'] = BoardAgencyStateModel::whereIn('id', $classIds)->pluck('name');
        }
        if (!empty($request->gender) && $request->gender[0] != null) {
            $genderIds = array_map(function ($id) {
                return intval($id);
            }, $request->gender);
            $query = $query->whereIn('gender', $genderIds);
            $filters['gender'] = $request->gender;
        }

        // // Join the studentCode relationship for checking if student has roll_no alloted or not
        $query = $query->whereHas('studentCode', function ($subQuery) {
            $subQuery->where('roll_no', '!=', null);
        });

        if ($request->isMethod('POST')) {
            if ($request->student_number) {
                $query->limit(intval($request->student_number));
            }

            $students = $query->with('studentCode')->get();
        } else {
            $students = $query->with('studentCode')->get();
        }

        return view('administrator.dashboard.student_exam_center_allot', compact('preloadedClasses', 'examCenters', 'students', 'cities', 'scholarshipTypes', 'filters'));
    }

    public function examCenterAllot(Request $request)
    {
        $examCenter = $request->exam_center;
        $studentNumber = intval($request->student_number ?? 0);

        // Check if all required parameters are present
        if (is_null($examCenter) || is_null($request->exam_mins) || is_null($request->exam_date_time) || $studentNumber <= 0) {
            $message = is_null($examCenter) ? 'Please select Exam center' : (
                is_null($request->exam_mins) ? 'Please select Exam Duration Mins' : (
                    is_null($request->exam_date_time) ? 'Please select Exam Date and time.' : 'Please provide the number of students.'
                )
            );
            return response()->json(['status' => false, 'message' => $message]);
        }

        // Start the query
        $query = Student::where('is_final_submitted', 1)->with('studentCode');

        // Apply district filter if provided
        if (!empty($request->district_id) && $request->district_id[0] != null) {
            $districtIds = array_map('intval', $request->district_id);
            $query = $query->whereIn('district_id', $districtIds);
        }

        // Apply scholarship filter if provided
        if (!empty($request->scholarship) && $request->scholarship[0] != null) {
            $scholarshipIds = array_map('intval', $request->scholarship);
            $query = $query->whereHas('scholarShipCategory', function ($subQuery) use ($scholarshipIds) {
                $subQuery->whereIn('id', $scholarshipIds);
            });
        }

        // Apply class filter if provided
        if (!empty($request->class) && $request->class[0] != null) {
            $classIds = array_map('intval', $request->class);
            $query = $query->whereIn('qualification', $classIds);
        }

        // Apply gender filter if provided
        if (!empty($request->gender) && $request->gender[0] != null) {
            $genderIds = array_map('intval', $request->gender);
            $query = $query->whereIn('gender', $genderIds);
        }

        // Only students with roll numbers should be selected
        $query = $query->whereHas('studentCode', function ($subQuery) {
            $subQuery->where('roll_no', '!=', null);
        });

        // Limit the number of students if provided
        if ($studentNumber > 0) {
            $query = $query->limit($studentNumber);
        }

        // Get the students who match the query
        $students = $query->get();

        // Check if any students match the criteria
        if ($students->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No Data Available for applied Filter.']);
        }

        // Loop through the students and assign the exam center
        foreach ($students as $student) {
            // Get the latest student code
            $studentCode = $student->studentCode->sortByDesc('created_at')->first();

            // Assign exam center and details if not already assigned
            if ($studentCode && !$studentCode->exam_center) {
                $studentCode->exam_center = $examCenter;
                $studentCode->exam_at = $request->exam_date_time;
                $studentCode->exam_mins = $request->exam_mins;
                $studentCode->admitcard_before = $request->admitcard_before;

                // Issue admit card if the corporate stop is not applied
                if (!$studentCode->corporate_stop_admitcard) {
                    $studentCode->issued_admitcard = 1;
                }

                // Save the updated student code
                $studentCode->save();
            }
        }

        return response()->json(['status' => true, 'message' => 'Exam center allotted successfully.']);
    }

    public function examCenterAllottoAll($exam_center, Request $request)
    {
        $query = Student::where('is_final_submitted', 1);
        if ($request->student_number && intval($request->student_number)) {
            $query->limit(intval($request->student_number));
        }
        $students = $query->get();

        $nullCountStudent = 0;
        if ($students->isNotEmpty()) {
            $students->load(['district', 'qualifications', 'studentCode']);

            foreach ($students as $student) {
                $studentCode = $student->studentCode->sortBy('created_at')->last();
                if (!is_null($studentCode) && is_null($studentCode->exam_center)) {
                    $studentCode->exam_center = $exam_center;
                    $studentCode->exam_at = $request->exam_date_time;
                    $studentCode->exam_mins = $request->exam_mins;
                    $studentCode->admitcard_before = $request->admitcard_before;

                    if (!$studentCode->corporate_stop_admitcard) {
                        $studentCode->issued_admitcard = 1;
                    }

                    $studentCode->save();
                }
                if (is_null($studentCode)) {
                    $nullCountStudent++;
                }
            }
            $msg = '';
            if ($nullCountStudent > 0) {
                $msg = 'And ' . $nullCountStudent . ' Student Application Code is not generated.';
            }
            return redirect()->back()->with('success', "Exam center alloted Successfully. $msg");
        } else {
            return back()->withErrors('No Data Available.');
        }
    }

    public function updateAdmitCardStatus(Request $request)
    {
        $studCodeIds = $request->input('studcode_ids');
        $status = $request->input('status');

        if (is_array($studCodeIds)) {
            $studentCodes = StudentCode::whereIn('id', $studCodeIds)->get();
            foreach ($studentCodes as $studCode) {
                if ($studCode->corporate_stop_admitcard == 0) {
                    $studCode->issued_admitcard = $status;
                    $studCode->save();
                }
            }
        } else {
            $studentCode = StudentCode::find($studCodeIds);
            if ($studentCode) {
                if ($studentCode->corporate_stop_admitcard == 0) {
                    $studentCode->issued_admitcard = $status;
                }
                $studentCode->save();
            }
        }

        return response()->json(['status' => true, 'message' => 'Updated AdmitCard status Successfully.']);
    }

    public function testimonialList()
    {
        $testimonials = TestimonialsModel::orderByDesc('id')->get();

        return view('administrator.dashboard.testimonials', compact('testimonials'));
    }

    public function testimonialDelete($id)
    {
        try {
            $testimonial = TestimonialsModel::find($id);
            $testimonial->delete();
            return back()->with('success', 'Succesfully deleted testimonial');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function testimonialToggleStatus(Request $request)
    {
        $course = TestimonialsModel::findOrFail($request->id);
        $course->status = $request->status;
        $course->save();

        return response()->json(['status' => $request->status == 1 ? true : false, 'message' => 'Testimonial Status updated successfully.']);
    }

    public function exportMarkFillExcel($id)
    {
        try {
            $subjectMapping = GnResultSubjectMapping::find(decodeId($id));

            $filename = 'Student-Mark-fill-' . time() . '.xlsx';
            return Excel::download(new StudentsSubjectMarkFillExport($subjectMapping), $filename);
        } catch (\Throwable $th) {
            logger('Failed to export excel:', [$th]);
            return back()->withErrors('Failed to Export: ' . json_encode($th->getMessage()));
        }
    }

    public function aboutUs(Request $request)
    {
        $data = [];

        if ($request->isMethod('POST')) {
            $homeAboutFolder = 'home/aboutus';
            if ($request->form_type == 'about_banner') {
                $validated = $request->validate([
                    'title' => 'required',
                    'banner' => ($request->banner_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $aboutUs = $request->banner_id ? AboutUs::find($request->banner_id) : new AboutUs();

                if ($request->hasFile('banner')) {
                    $validated['banner'] = moveFile($homeAboutFolder, $request->banner);
                } else if ($request->banner_id) {
                    $validated['banner'] = $aboutUs->banner;
                }

                $aboutUs->fill($validated);
                $aboutUs->save();

                return redirect()->back()->with('success', 'About Data saved successfully!');
            }

            if ($request->form_type == 'about_section2') {
                $validated = $request->validate([
                    'title' => 'required| string',
                    'banner' => ($request->section_two_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'required|string |min:20',
                    'service_a' => 'required | string',
                    'service_a_image' => ($request->section_two_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'service_a_description' => 'required| string|min:20',
                    'service_b' => 'required |string',
                    'service_b_image' => ($request->section_two_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'service_b_description' => 'required|string  |min:20',
                    'service_c' => 'required |string',
                    'service_c_image' => ($request->section_two_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'service_c_description' => 'required|string|min:20',
                    'service_d' => 'required  |string',
                    'service_d_image' => ($request->section_two_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'service_d_description' => 'required| string| min:20'
                ]);

                $aboutUsSectionOne = $request->section_two_id ? AboutUsSectionOne::find($request->section_two_id) : new AboutUsSectionOne();

                if ($request->hasFile('banner')) $validated['banner'] = moveFile($homeAboutFolder, $request->file('banner'));
                else if ($request->section_two_id) $validated['banner'] = $aboutUsSectionOne->banner;

                if ($request->hasFile('service_a_image')) $validated['service_a_image'] = moveFile($homeAboutFolder, $request->file('service_a_image'));
                else if ($request->section_two_id) $validated['service_a_image'] = $aboutUsSectionOne->service_a_image;

                if ($request->hasFile('service_b_image')) $validated['service_b_image'] = moveFile($homeAboutFolder, $request->file('service_b_image'));
                else if ($request->section_two_id) $validated['service_b_image'] = $aboutUsSectionOne->service_b_image;

                if ($request->hasFile('service_c_image')) $validated['service_c_image'] = moveFile($homeAboutFolder, $request->file('service_c_image'));
                else if ($request->section_two_id) $validated['service_c_image'] = $aboutUsSectionOne->service_c_image;

                if ($request->hasFile('service_d_image')) $validated['service_d_image'] = moveFile($homeAboutFolder, $request->file('service_d_image'));
                else if ($request->section_two_id) $validated['service_d_image'] = $aboutUsSectionOne->service_d_image;

                $aboutUsSectionOne->fill($validated);
                $aboutUsSectionOne->save();

                $message = $request->section_two_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "Data $message successfully!");
            }

            if ($request->form_type == 'about_section3') {
                $validated = $request->validate([
                    'section_title' => 'nullable |string',
                    'section_remarks' => 'nullable',
                    'title' => 'required  |  string',
                    'image' => ($request->section_three_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'required|   string'
                ]);

                $modelSectionThree = $request->section_three_id ? AboutUsSectionThree::find($request->section_three_id) : new AboutUsSectionThree();

                if ($request->hasFile('image')) {
                    $validated['image'] = moveFile($homeAboutFolder, $request->image);
                } else if ($request->section_three_id) {
                    $validated['image'] = $modelSectionThree->image;
                }

                $modelSectionThree->fill($validated);
                $modelSectionThree->save();

                $message = $request->section_three_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "About section 3 Data $message successfully!");
            }

            if ($request->form_type == 'about_section4') {
                $validated = $request->validate([
                    'section_title' => 'nullable|string',
                    'section_remarks' => 'nullable',
                    'title' => 'required|string',
                    'image' => ($request->about_section4_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'required|string'
                ]);

                $modelSectionFour = $request->about_section4_id ? AboutUsSectionFour::find($request->about_section4_id) : new AboutUsSectionFour();

                if ($request->hasFile('image')) {
                    $validated['image'] = moveFile($homeAboutFolder, $request->image);
                } else if ($request->about_section4_id) {
                    $validated['image'] = $modelSectionFour->image;
                }

                $modelSectionFour->fill($validated);
                $modelSectionFour->save();

                $message = $request->about_section4_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "About section 4 Data $message successfully!");
            }

            if ($request->form_type == 'about_section5') {
                $validated = $request->validate([
                    'section_title' => 'nullable|string',
                    'section_remarks' => 'nullable',
                    'title' => 'required|string',
                    'image' => ($request->about_section5_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'required|string'
                ]);

                $modelSectionFive = $request->about_section5_id ? AboutUsSectionFive::find($request->about_section5_id) : new AboutUsSectionFive();

                if ($request->hasFile('image')) {
                    $validated['image'] = moveFile($homeAboutFolder, $request->image);
                } else if ($request->about_section5_id) {
                    $validated['image'] = $modelSectionFive->image;
                }

                $modelSectionFive->fill($validated);
                $modelSectionFive->save();

                $message = $request->about_section5_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "About section 5 Data $message successfully!");
            }

            if ($request->form_type == 'about_section6') {
                $validated = $request->validate([
                    'section_title' => 'nullable|string',
                    'section_remarks' => 'nullable',
                    'title' => 'required|string',
                    'image' => ($request->about_section6_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'required|string'
                ]);

                $modelSectionSix = $request->about_section6_id ? AboutUsSectionSix::find($request->about_section6_id) : new AboutUsSectionSix();

                if ($request->hasFile('image')) {
                    $validated['image'] = moveFile($homeAboutFolder, $request->image);
                } else if ($request->about_section6_id) {
                    $validated['image'] = $modelSectionSix->image;
                }

                $modelSectionSix->fill($validated);
                $modelSectionSix->save();

                $message = $request->about_section6_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "About section 6 Data $message successfully!");
            }

            if ($request->form_type == 'about_founder') {
                $validated = $request->validate([
                    'title' => 'required|string',
                    'icon' => ($request->founder_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'picture' => ($request->founder_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'name' => 'required|string',
                    'message' => 'required|string',
                ]);

                $aboutUsFounder = $request->founder_id ? AboutUsFounder::find($request->founder_id) : new AboutUsFounder();

                if ($request->hasFile('picture')) $validated['picture'] = moveFile($homeAboutFolder, $request->picture);
                else if ($request->founder_id) $validated['picture'] = $aboutUsFounder->picture;

                if ($request->hasFile('icon')) $validated['icon'] = moveFile($homeAboutFolder, $request->icon);
                else if ($request->founder_id) $validated['icon'] = $aboutUsFounder->icon;

                $aboutUsFounder->fill($validated);
                $aboutUsFounder->save();

                $message = $request->founder_id ? 'Updated' : 'Saved';
                return redirect()->back()->with('success', "Data $message successfully!");
            }
        }
        $data['banners'] = AboutUs::all();
        $data['sectionTwo'] = AboutUsSectionOne::all();
        $data['bannerSectionThrees'] = AboutUsSectionThree::all();
        $data['bannerSectionFours'] = AboutUsSectionFour::all();
        $data['bannerSectionFives'] = AboutUsSectionFive::all();
        $data['bannerSectionSixs'] = AboutUsSectionSix::all();
        $data['founders'] = AboutUsFounder::all();

        return view('administrator.Home.aboutUs.about_us', compact('data'));
    }

    public function aboutUsDelete($form_type, $id)
    {
        $message = null;
        try {
            if ($form_type == 'about_banner') {
                $aboutUs = AboutUs::find($id);
                $aboutUs->delete();

                $message = 'About Us Banner';
            }

            if ($form_type == 'about_sectionTwo') {
                $abboutUs = AboutUsSectionOne::find($id);
                $abboutUs->delete();

                $message = 'About Us Section Two';
            }

            if ($form_type == 'about_sectionThree') {
                $abboutUs = AboutUsSectionThree::find($id);
                $abboutUs->delete();

                $message = 'About Us Section Three';
            }

            if ($form_type == 'about_sectionFour') {
                $abboutUs = AboutUsSectionFour::find($id);
                $abboutUs->delete();

                $message = 'About Us Section Four';
            }
            if ($form_type == 'about_sectionFive') {
                $abboutUs = AboutUsSectionFive::find($id);
                $abboutUs->delete();

                $message = 'About Us Section Five';
            }
            if ($form_type == 'about_sectionSix') {
                $abboutUs = AboutUsSectionSix::find($id);
                $abboutUs->delete();

                $message = 'About Us Section Six';
            }

            if ($form_type == 'about_founder') {
                $abboutUs = AboutUsFounder::find($id);
                $abboutUs->delete();

                $message = 'About Us Founder';
            }
            if ($message) {
                return back()->with('error', "$message Data Deleted successfully!");
            }

            return back()->with('success', 'Section Record not found.');
        } catch (\Throwable $th) {
            logger('Failed to delete:', [$th]);
            return back()->withErrors('Failed to delete Something wrong.');
        }
    }

    public function aboutUsStatusToggle(Request $request)
    {
        $mesage = null;

        if ($request->form_type == 'about_banner') {
            $abboutUs = AboutUs::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Banner';
        }

        if ($request->form_type == 'about_sectionTwo') {
            $abboutUs = AboutUsSectionOne::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Section Two';
        }

        if ($request->form_type == 'about_sectionThree') {
            $abboutUs = AboutUsSectionThree::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Section Three';
        }

        if ($request->form_type == 'about_sectionFour') {
            $abboutUs = AboutUsSectionFour::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Section Four';
        }
        if ($request->form_type == 'about_sectionFive') {
            $abboutUs = AboutUsSectionFive::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Section Five';
        }
        if ($request->form_type == 'about_sectionSix') {
            $abboutUs = AboutUsSectionSix::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Section Six';
        }
        if ($request->form_type == 'about_founder') {
            $abboutUs = AboutUsFounder::findOrFail($request->id);
            $abboutUs->status = $request->status;
            $abboutUs->save();

            $mesage = 'About Us Founder';
        }

        if ($request->form_type == 'home_slider') {
            $home = SliderModel::findOrFail($request->id);
            $home->status = $request->status;
            $home->save();

            $mesage = 'Home Slider';
        }

        if ($request->form_type == 'gov_website') {
            $homeGov = GovtwebsiteModel::findOrFail($request->id);
            $homeGov->status = $request->status;
            $homeGov->save();

            $mesage = 'Home Gov Website ';
        }

        if ($request->form_type == 'home_faqs') {
            $homeGov = FaqModel::findOrFail($request->id);
            $homeGov->status = $request->status;
            $homeGov->save();

            $mesage = 'Faqs list ';
        }

        if ($request->form_type == 'blog_news') {
            $homeGov = BlognewsModel::findOrFail($request->id);
            $homeGov->status = $request->status;
            $homeGov->save();

            $mesage = 'Blogs ';
        }

        if ($request->form_type == 'home_notification') {
            $homeGov = NotificationModel::findOrFail($request->id);
            $homeGov->status = $request->status;
            $homeGov->save();

            $mesage = 'Notification ';
        }

        if ($request->form_type == 'e_prospectus') {
            $homeGov = EProspectusModel::findOrFail($request->id);
            $homeGov->status = $request->status;
            $homeGov->save();

            $mesage = 'Notification ';
        }

        if ($mesage) {
            return response()->json(['status' => $request->status == 1 ? true : false, 'message' => "$mesage Status updated successfully."]);
        }

        return response()->json(['status' => false, 'message' => 'Status Record not found.']);
    }

    public function scholarship(Request $request)
    {
        $data = [];

        $homeAboutFolder = 'home/aboutus';
        $homeEprospectusFolder = 'home/eprospectus';

        if ($request->isMethod('POST')) {
            if ($request->form_type == 'scholarship_form') {
                $validated = $request->validate([
                    'icon' => $request->scholarship_id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'education_type_id' => 'required',
                    'subtitle' => 'nullable|string',
                    'remark' => 'required|string',
                    'url' => 'nullable|string',
                    'picture' => $request->scholarship_id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ], [
                    'icon.required' => 'The icon image is required.',
                    'icon.image' => 'The icon must be an image.',
                    'icon.mimes' => 'The icon must be a file of type: jpeg, png, jpg, gif, svg.',
                    'icon.max' => 'The icon image must not be larger than 2048 kilobytes.',
                    'education_type_id.required' => 'The scholarship category is required.',
                    'remark.required' => 'The remark is required.',
                    'remark.string' => 'The remark must be a string.',
                    'picture.required' => 'The picture is required.',
                    'picture.image' => 'The picture must be an image.',
                    'picture.mimes' => 'The picture must be a file of type: jpeg, png, jpg, gif, svg.',
                    'picture.max' => 'The picture must not be larger than 2048 kilobytes.',
                ]);

                $aboutUs = $request->scholarship_id ? ScholarshipHome::find($request->scholarship_id) : new ScholarshipHome();

                if ($request->hasFile('icon')) {
                    $validated['icon'] = moveFile($homeAboutFolder, $request->icon);
                } else if ($request->scholarship_id) {
                    $validated['icon'] = $aboutUs->icon;
                }

                if ($request->hasFile('picture')) {
                    $validated['picture'] = moveFile($homeAboutFolder, $request->picture);
                } else if ($request->scholarship_id) {
                    $validated['picture'] = $aboutUs->picture;
                }

                $aboutUs->fill($validated);
                $aboutUs->save();

                return redirect()->back()->with('success', 'Data saved successfully!');
            }

            if ($request->form_type == 'scholarship_secondForm') {
                $validated = $request->validate([
                    'scholarship_course' => 'required',
                    'prospectus' => $request->scholarshipTwo_id ? 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:2048' : 'required|mimes:pdf,jpeg,png,jpg,gif|max:2048',
                    'guideline' => $request->scholarshipTwo_id ? 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:2048' : 'required|mimes:pdf,jpeg,png,jpg,gif|max:2048',
                    'overview' => 'required',
                ]);

                $aboutUsSectionOne = $request->scholarshipTwo_id ? ScholarshipHomeTwo::find($request->scholarshipTwo_id) : new ScholarshipHomeTwo();

                if ($request->hasFile('prospectus')) {
                    $file = $request->file('prospectus');
                    $hasUrl = hash_file('sha256', $file->getRealPath());
                    $image = Image::where('has_url', $hasUrl)->first();
                    $name = $image?->image_name;
                    if (is_null($image)) {
                        $name = rand(10000, 99999) . '-' . date('His') . preg_replace('/[^\w\-\.]/', '', $file->getClientOriginalName());
                        \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($homeEprospectusFolder, $file, $name);
                        $imageUrl = asset('storage/' . $homeEprospectusFolder . '/' . $name);
                        $image = new Image();
                        $image->url = $imageUrl;
                        $image->has_url = $hasUrl;
                        $image->image_name = $name;
                        $image->image_path = $homeEprospectusFolder;
                        $image->save();
                        $image->url = $imageUrl . '?' . $image->id;
                        $image->save();
                    }
                    $validated['prospectus'] = $name;
                } else if ($request->scholarshipTwo_id) {
                    $validated['prospectus'] = $aboutUsSectionOne->prospectus;
                }

                if ($request->hasFile('guideline')) {
                    $file = $request->file('guideline');
                    $hasUrl = hash_file('sha256', $file->getRealPath());
                    $image = Image::where('has_url', $hasUrl)->first();
                    $name = $image?->image_name;
                    if (is_null($image)) {
                        $name = rand(10000, 99999) . '-' . date('His') . preg_replace('/[^\w\-\.]/', '', $file->getClientOriginalName());
                        \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($homeEprospectusFolder, $file, $name);
                        $imageUrl = asset('storage/' . $homeEprospectusFolder . '/' . $name);
                        $image = new Image();
                        $image->url = $imageUrl;
                        $image->has_url = $hasUrl;
                        $image->image_name = $name;
                        $image->image_path = $homeEprospectusFolder;
                        $image->save();
                        $image->url = $imageUrl . '?' . $image->id;
                        $image->save();
                    }
                    $validated['guideline'] = $name;
                } else if ($request->scholarshipTwo_id) {
                    $validated['guideline'] = $aboutUsSectionOne->guideline;
                }

                $aboutUsSectionOne->fill(collect($validated)->except(['scholarship_course', 'prospectus', 'guideline'])->toArray());
                $aboutUsSectionOne->prospectus = $validated['prospectus'];
                $aboutUsSectionOne->guideline = $validated['guideline'];
                $aboutUsSectionOne->scholarship_course_id = $request->scholarship_course;
                $aboutUsSectionOne->save();

                $message = $request->scholarshipTwo_id ? 'Updated' : 'Saved';

                return redirect()->back()->with('success', "Data $message successfully!");
            }
        }
        $scholarshipTwo = null;

        $data['educations'] = EducationType::where('is_featured', 1)->get();
        $data['scholarships'] = ScholarshipHome::with('educationType')->get();
        $data['scholarshipTwos'] = ScholarshipHomeTwo::with('scholarshipType')->get();
        $data['scholarshipCourses'] = ScholarshipHome::with('educationType')->get();

        return view('administrator.Home.scholarship', compact('data', 'scholarshipTwo'));
    }

    public function scholarshipDelete($form_type, $id)
    {
        $message = null;
        try {
            if ($form_type == 'scholarship') {
                $aboutUs = ScholarshipHome::find($id);
                $aboutUs->delete();

                $message = 'scholarship';
            }

            if ($form_type == 'scholarship_secondForm') {
                $abboutUs = ScholarshipHomeTwo::find($id);
                $abboutUs->delete();

                $message = 'Scholarship Overview ';
            }

            if ($message) {
                return back()->with('error', "$message Data Deleted successfully!");
            }

            return back()->with('success', 'Scholarship Record not found.');
        } catch (\Throwable $th) {
            logger('Failed to delete:', [$th]);
            return back()->withErrors('Failed to delete Something wrong.');
        }
    }

    public function scholarshipStatusToggle(Request $request)
    {
        $mesage = null;

        if ($request->form_type == 'scholarship') {
            $abboutUs = ScholarshipHome::findOrFail($request->id);
            $abboutUs->is_featured = $request->status;
            $abboutUs->save();

            $mesage = 'Scholarship';
        }

        if ($request->form_type == 'scholarship_secondForm') {
            $abboutUs = ScholarshipHomeTwo::findOrFail($request->id);
            $abboutUs->is_featured = $request->status;
            $abboutUs->save();

            $mesage = 'ScholarShip Overview ';
        }

        if ($mesage) {
            return response()->json(['status' => $request->status == 1 ? true : false, 'message' => "$mesage Status updated successfully."]);
        }

        return response()->json(['status' => false, 'message' => 'Scholarship status not found.']);
    }

    public function studentPaperImport(Request $request)
    {
        $request->validate([
            'importFile' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new StudentPapersImport, $request->file('importFile'));
        } catch (\Throwable $th) {
            logger('failed', [$th]);
            return redirect()->back()->with('error', 'Student papers imported successfully.' . $th->getMessage());
        }

        return redirect()->back()->with('success', 'Student papers imported.');
    }

    public function subjectPaperDetailsAdd(Request $request)
    {
        $subjectPaperDetail = SubjectPaperDetail::where('subject_mapping_id', $request->subjectMapping_id)
            ->where('subject_id', $request->subject_id)
            ->first();
        if ($subjectPaperDetail) {
            $subjectPaperDetail->max_marks = $request->input('max_marks');
            $subjectPaperDetail->total_questions = $request->input('total_questions');
            $subjectPaperDetail->negative_marks_wrong = $request->input('negative_marks_wrong');
            $subjectPaperDetail->negative_marks_skipped = $request->input('negative_marks_skipped');
            $subjectPaperDetail->save();
        } else {
            return response()->json(['status' => false, 'message' => 'Data saved successfully']);
        }

        return response()->json(['status' => true, 'message' => 'Data saved successfully']);
    }

    public function termsCondition()
    {
        $termsCondition = TermsCondition::orderBy('created_at')->get();

        return view('administrator.dashboard.terms_condition', compact('termsCondition'));
    }

    public function termsConditionSave(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'terms_condition_pdf' => 'required',
            'type' => 'required',
            'page_name' => 'required'
        ]);
        try {
            // chevck data already exist or not
            $pdfexist = DB::table('terms_conditions')->where('type', $request->type)->where('page_name', $request->page_name)->first();
            if (isset($pdfexist) && !empty($pdfexist)) {
                return redirect()->back()->with('error', 'PDF Data already exist! Please delete previous data and try to re ipload again');
            }
            $terms_condition_pdf = null;
            if ($request->hasFile('terms_condition_pdf')) {
                $terms_condition_pdf = moveFile('home', $request->file('terms_condition_pdf'));
            }

            $termsCondition = new TermsCondition();
            $termsCondition->title = $request->title;
            $termsCondition->type = $request->type;
            $termsCondition->page_name = $request->page_name;
            $termsCondition->terms_condition_pdf = $terms_condition_pdf;
            $termsCondition->save();
            return redirect()->back()->with('success', 'PDF Data added successfully!');
        } catch (\Throwable $th) {
            logger('failed:', [$th]);
            return redirect()->back()->with('error', 'PDF Data failed to add!');
        }
    }

    public function termsConditionDelete($id)
    {
        $termsCondition = TermsCondition::find($id);
        if (!$termsCondition) {
            return redirect()->back()->with('error', 'PDF Data not found');
        } else {
            $termsCondition->delete();
            return redirect()->back()->with('success', 'PDF Data Deleted');
        }
    }

    public function termsConditionToggleStatus(Request $request)
    {
        $terms = TermsCondition::findOrFail($request->id);
        $terms->status = $request->status;
        $terms->save();

        return response()->json(['status' => $request->status == 1 ? true : false, 'message' => 'PDF Data Status updated successfully.']);
    }

    public function paymentSettings(Request $request)
    {
        $message = 'Created';

        if ($request->isMethod('POST')) {
            $request->validate([
                'key_id' => 'required|string',
                'key_secret' => 'required|string',
            ]);

            $settings = PaymentSetting::first();
            if ($settings) {
                $settings->update($request->all());
            } else {
                PaymentSetting::create($request->all());
            }

            if ($settings) {
                $message = 'Updated';
            }

            return redirect()->back()->with('success', "Payment settings  $message successfully.");
        }
        $settings = PaymentSetting::first();

        return view('administrator.dashboard.payment_settings', compact('settings'));
    }

    public function profile(Request $request)
    {
        $user = User::find((Auth::id()));

        if ($request->isMethod('POST')) {
            if ($user->photograph) {
                $photographRequired = 'nullable';
            } else {
                $photographRequired = 'required';
            }

            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|lowercase|email|unique:users,email,' . $user->id,
                'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
                'gender' => 'required',
                'photograph' => "$photographRequired",
            ]);

            if ($request->hasFile('photograph')) {
                $validatedData['photograph'] = moveFile('upload', $request->file('photograph'));
            }

            try {
                $user->forceFill($validatedData);
                $user->save();

                return redirect()->back()->with(['success' => 'Updated successfully']);
            } catch (\Throwable $th) {
                logger('message failed:', [$th]);
                return back()->withErrors('Failed to updated');
            }
        }
        return view('administrator.settings.profile', compact('user'));
    }

    public function mobileNumber(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'mobile' => 'required|digits:10'
            ]);
            $mobileNumber = new MobileNumber;
            $mobileNumber->mobile = $request->mobile;
            $mobileNumber->save();

            return back()->with('success', 'Save Mobile Successfully');
        }

        $mobileNumbers = MobileNumber::all();
        return view('administrator.settings.mobile_number', compact('mobileNumbers'));
    }

    public function toggleMobileStatus(Request $request)
    {
        $mobileNumber = MobileNumber::find($request->id);

        $mobileNumber->isOtpRequired = $request->status;
        $mobileNumber->save();

        return response()->json(['status' => true, 'message' => 'Status Changed Successfully.']);
    }

    public function mobileNumberDelete(MobileNumber $mobileNumber)
    {
        $mobileNumber->delete();

        return back()->withErrors('Deleted Successfully');
    }

    public function changePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if ($request->isMethod('POST')) {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

            if (!Hash::check($request->old_password, $user->password) || Hash::check($request->new_password, $user->password)) {
                $message = !Hash::check($request->old_password, $user->password) ? 'The old password incorrect.' : 'The New Password can not be the same as the Current Password.';
                return back()->with('error', $message);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return redirect()->route('home');
        }
        return view('administrator.settings.change_password', compact('user'));
    }

    public function studentResult(Request $request)
    {
        $scholarshipTypes = EducationType::get();

        $query = Student::query();

        $query->where('is_final_submitted', 1);

        $classes = collect();

        $query
            ->select('students.*', 's.percentage')
            ->leftJoin('student_codes as s', 'students.id', '=', 's.stud_id')
            ->leftJoin('student_paper_exporteds as sp', 'students.id', '=', 'sp.student_id')
            ->where('is_final_submitted', 1)
            // ->whereNotNull('sp.obtained_marks')
            ->whereNotNull('s.percentage');

        if ($request->percentage) {
            $query->where('s.percentage', '>=', $request->percentage);
        }

        if ($request->education_name) {
            $query->where('scholarship_category', '=', $request->education_name);
        }

        if ($request->limit) {
            $query->limit($request->limit);
        }

        $students = $query
            ->orderByDesc('s.percentage')
            ->distinct('students.id')
            ->get();

        return view('administrator.dashboard.student_result', compact('students', 'scholarshipTypes', 'classes'));
    }

    public function studentResultDetail(Student $student)
    {
        $appCode = $student->latestStudentCode;

        $studentPaperDetails = StudentPaperExported::with('subjectPaperDetail')->where('app_code', $appCode?->application_code)->where('student_id', $student->id)->get();

        return view('administrator.dashboard.student.result_download', compact('student', 'appCode', 'studentPaperDetails'));
    }

    public function studentResultClaimScholarship(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'student_select' => 'required',
        ], [
            'student_select.required' => 'Select Student',
        ]);

        $students = Student::with(['latestStudentCode'])->whereIn('id', $request->student_id)->get();
        foreach ($students as $student) {
            $appCode = $student->latestStudentCode;

            if ($appCode) {
                $appCode->allow_to_claim_scholarship = 1;
                $appCode->save();
            }
        }

        return back()->with('success', 'Updated Successfully');
    }

    public function studentAdminCard(Student $student)
    {
        $appCode = $student->latestStudentCode;

        if ($appCode && $appCode->exam_center) {
            $appCode->load(['examCenter.state', 'examCenter.city']);
        }

        return view('administrator.dashboard.student.admitCard', compact('student', 'appCode'));
    }

    public function studentClaimScholarship(Student $student)
    {
        $cities = $student->state?->districts;
        $claimForm = StudentClaimForm::where('student_id', $student->id)->first();

        return view('administrator.dashboard.student.claim_scholarship', compact('cities', 'student', 'claimForm'));
    }

    public function studentClaimStatusUpdate(Request $request, StudentClaimForm $claimForm)
    {
        $request->validate([
            'status' => 'required|in:pending-processing,rejected,confirmed',
        ]);

        $claimForm->update(['status' => $request->status]);

        return back()->with('success', 'Claim Status Updated Successfully.');
    }


    public function refreshStudentRank()
    {
        $students = Student::with(['latestStudentCode'])->get();

        $this->studentRankService = new StudentRankService;
        $studentCount = $this->studentRankService->recalculateAllRanks();

        return back()->with('success', "$studentCount Students Rank Updated Successfully.");
    }

    public function privacyPolicy()
    {
        $termsCondition = PrivacyPolicy::orderBy('created_at')->get();

        return view('administrator.dashboard.privacy_policy', compact('termsCondition'));
    }

    public function privacyPolicySave(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'terms_condition_pdf' => 'required',
            'type' => 'required',
        ]);
        try {
            $termsCondition = new PrivacyPolicy();

            if ($request->hasFile('terms_condition_pdf')) {
                $termsCondition->terms_condition_pdf = moveFile('home/', $request->terms_condition_pdf);
            } else {
                return redirect()->back()->with('error', 'privacy policies  pdf not added!');
            }
            $termsCondition->title = $request->title;
            $termsCondition->type = $request->type;

            $termsCondition->save();
            return redirect()->back()->with('success', 'privacy policies  pdf added successfully!');
        } catch (\Throwable $th) {
            logger('failed:', [$th]);
            return redirect()->back()->with('error', 'privacy policies pdf failed to add!');
        }
    }

    public function privacyPolicyDelete($id)
    {
        $termsCondition = PrivacyPolicy::find($id);
        if (!$termsCondition) {
            return redirect()->back()->with('error', 'privacy policies not found');
        } else {
            $termsCondition->delete();
            return redirect()->back()->with('success', 'privacy policies Deleted');
        }
    }

    public function privacyPolicyToggleStatus(Request $request)
    {
        $terms = PrivacyPolicy::findOrFail($request->id);
        $terms->status = $request->status;
        $terms->save();

        return response()->json(['status' => $request->status == 1 ? true : false, 'message' => 'Privacy Policies Status updated successfully.']);
    }

    /*
     * ADddition PRINt PDF
     */

    public function printStudentList(Request $request)
    {
        $scholarshipTypes = EducationType::get();

        $cities = District::get();

        $query = Student::query()->with([
            'studentCode',
            'latestStudentCode',
            'studentPayment',
            'district',
            'qualifications',
            'scholarShipCategory',
            'scholarShipOptedFor'
        ]);

        $classes = collect();

        $query->where('is_final_submitted', 1);

        if ($request->isMethod('POST')) {
            if (!empty($request->district_id)) {
                $query->whereIn('district_id', $request->district_id);
            }

            if (!empty($request->gender)) {
                $query->whereIn('gender', $request->gender);
            }

            if (!empty($request->class)) {
                $query->whereIn('qualification', $request->class);
                $classes = BoardAgencyStateModel::whereIn('id', $request->class)->select('id', 'name')->get();
            }

            $students = $query->with('latestStudentCode')->get();
        } else {
            $students = $query->with('latestStudentCode')->get();
        }
        // 'students', 'cities', 'scholarshipTypes', 'classes'
        $pdf = Pdf::loadView('administrator/download/print-student-list', ['students' => $students, 'cities' => $cities, 'scholarshipTypes' => $scholarshipTypes, 'classes' => $classes]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Generate Student List on ' . date('d-m-Y His A') . '.pdf');
    }

    public function printstudentView(Student $student)
    {
        $states = State::all();
        $scholarshipTypes = EducationType::get();  // 'student', 'states','scholarshipTypes'
        $pdf = Pdf::loadView('administrator/download/print-student-details', ['student' => $student, 'states' => $states, 'scholarshipTypes' => $scholarshipTypes]);
        $pdf->setPaper('A4');
        return $pdf->stream('Registration Details of ' . $student->name . '.pdf');
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
}
