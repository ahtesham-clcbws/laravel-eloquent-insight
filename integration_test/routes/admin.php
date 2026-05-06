<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CouponCodeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NewsController;
use App\Livewire\Admin\Setting\Districts;
use App\Livewire\Admin\Setting\ImportantLinkSettings;
use App\Livewire\Admin\Setting\PolicyPages\PolicyPageAddUpdate;
use App\Livewire\Admin\Setting\PolicyPages\PolicyPagesList;
use App\Livewire\Admin\Setting\ScholarshipForms;
use App\Livewire\Admin\Setting\States;
use App\Livewire\Administrator\Corporate\AdminCouponRequestsList;
use App\Livewire\Administrator\Corporate\InstituteCouponlist;
use App\Livewire\Administrator\Dashboard\CouponList;
use App\Livewire\Administrator\Dashboard\StudentRollList;
use App\Livewire\Administrator\Settings\ContactList;
use App\Livewire\Administrator\Settings\ContactListReply;
use App\Livewire\Administrator\Settings\ContactRepliesList;
use App\Livewire\Administrator\Settings\PopupSetting;
use App\Livewire\Administrator\Settings\RegistrationSetting;
use App\Livewire\Administrator\Settings\ResetPortal;
use App\Livewire\Administrator\Dashboard\CreateCoupon;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes([
    'register' => false, // Registration Routes...
    // 'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

use Illuminate\Http\Request;

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout',  [LoginController::class, 'logout'])->name('admin.logout');

    Route::get('/', [AdminController::class, 'index'])->name('admin.home');

    Route::get('/instituteListNew/{id?}', [EnquiryController::class, 'instituteListNew'])->name('institute.list.new');
    Route::get('/instituteListsignup/{id?}', [EnquiryController::class, 'instituteListSignup'])->name('institute.list.signup');
    Route::get('/instituteList', [EnquiryController::class, 'instituteList'])->name('institute.list');
    Route::get('/institute/{corporate?}', [EnquiryController::class, 'instituteView'])->name('institute.view');
    Route::post('/institute_status', [EnquiryController::class, 'instituteStatus'])->name('institute.institute_status');
    // Route::any('/institute/couponlist/{corporate?}', [AdminController::class, 'CoprporateCouponlists'])->name('institute.CoprporateCouponlists');
    Route::any('/institute/couponlist/{corporateId}', InstituteCouponlist::class)->name('institute.CoprporateCouponlists');
    Route::get('/corporate/coupon-requests', AdminCouponRequestsList::class)->name('institute.couponRequests');

    //print section 
    Route::get('/print-new-institute-enquiry', [EnquiryController::class, 'printNewInstituteEnquiry'])->name('print.new.institute.enquiry');
    Route::get('/print-signup-institute-list', [EnquiryController::class, 'printSignUpInstituteList'])->name('print.signup.institute.list');
    Route::get('/print-approve-institute-list', [EnquiryController::class, 'printApproveInstituteList'])->name('print.approve.institute.list');

    // Route::get('/contact_enquiry', [EnquiryController::class, 'contactEnquiry'])->name('admin.contactEnquiry');
    Route::get('/contact_enquiry', ContactList::class)->name('admin.contactEnquiry');
    Route::get('/contact_enquiry/{id}', ContactListReply::class)->name('admin.contactEnquiryReply');
    Route::get('/contact-replies/{id}', ContactRepliesList::class)->name('admin.contactRelpiesList');

    Route::get('/contact_enquiry_delete/{contactInfo}', [EnquiryController::class, 'contactEnquiryDelete'])->name('admin.contactEnquiryDelete');
    Route::post('/contact_enquiry_reply_mail/{contactInfo}', [EnquiryController::class, 'contactEnquiryReplyMail'])->name('admin.conatctEnqueryEeplyMail');

    Route::any('/student_list', [AdminController::class, 'studentList'])->name('admin.studentList');
    Route::any('/registered-students', [AdminController::class, 'studentListRegistered'])->name('admin.studentListRegistered');
    Route::any('/claimed-students', [AdminController::class, 'studentClaimList'])->name('admin.studentClaimList');

    Route::any('/studentRollList', [AdminController::class, 'studentRollList'])->name('admin.studentRollList');
    Route::any('/student-roll-list', StudentRollList::class)->name('admin.student-roll-list');

    Route::any('/student_generate_roll_no', [AdminController::class, 'studentGenerateRollNo'])->name('admin.studentGenerateRollNo');
    Route::get('/student_list/{student}', [AdminController::class, 'studentView'])->name('admin.student');
    Route::any('/get_scholarship_category', [AdminController::class, 'getClassByScholarshipType'])->name('admin.getClassByScholarshipType');
    Route::post('/update_coupons', [AdminController::class, 'updateCoupons'])->name('apply_coupon_corporate');
    Route::any('/create_center/{id?}', [AdminController::class, 'createCenter'])->name('admin.createCenter');
    Route::post('/save_center', [AdminController::class, 'saveCenter'])->name('admin.saveCenter');
    Route::any('/list_center', [AdminController::class, 'centerList'])->name('admin.listCenter');
    Route::any('/exam_center_allotment', [AdminController::class, 'examCenterAllotment'])->name('admin.studentExamCenter');

    Route::any('/exam_center_allot', [AdminController::class, 'examCenterAllot'])->name('admin.studentExamCenterAllotment');
    Route::any('/exam_center_allot_to_all/{exam_center}', [AdminController::class, 'examCenterAllottoAll'])->name('admin.studentExamCenterAllotmenttoAll');


    Route::post('/update-admitcard-status', [AdminController::class, 'updateAdmitCardStatus'])->name('update.admitcard.status');
    Route::get('/testimonial_list', [AdminController::class, 'testimonialList'])->name('admin.testimonialList');
    Route::get('/delete-testimonial/{id}', [AdminController::class, 'testimonialDelete'])->name('admin.testimonialDelete');
    Route::get('/export_markfill_excel/{id}', [AdminController::class, 'exportMarkFillExcel'])->name('admin.exportMarkFillExcel');
    Route::any('/profile', [AdminController::class, 'profile'])->name('admin.profile');

    Route::post('/generate-scholarship-eligible-students', [AdminExamController::class, 'generateScholarshipEligibleStudents'])->name('admin.generateScholarshipEligibleStudents');

    Route::any('/student_result', [AdminController::class, 'studentResult'])->name('admin.student.result');
    Route::any('/student_result_detail/{student}', [AdminController::class, 'studentResultDetail'])->name('admin.student.result.detail');
    Route::post('/student_result_claim_scholarship', [AdminController::class, 'studentResultClaimScholarship'])->name('admin.student.result.allow_claim');
    Route::any('/student_result_admin_card/{student}', [AdminController::class, 'studentAdminCard'])->name('admin.student.adminCard');
    Route::any('/student_claim_form/{student}', [AdminController::class, 'studentClaimScholarship'])->name('admin.student.claim_form');
    Route::post('/student_claim_status_update/{claimForm}', [AdminController::class, 'studentClaimStatusUpdate'])->name('admin.student.claim_status_update');

    Route::get('/get_admin_scholarship_category/{id?}/{type?}', [AdminController::class, 'getScholarshipCategory']);
    Route::get('/get_admin_scholarship_opted_for/{id?}/{qualificationId?}', [AdminController::class, 'getScholarshipCategoryOptedFor']);


    Route::any('/print-student-list', [AdminController::class, 'printStudentList'])->name('admin.print.studentList');
    Route::any('/print-student-view/{student}', [AdminController::class, 'printstudentView'])->name('admin.print.studentView');

    Route::any('/student_rank_refresh', [AdminController::class, 'refreshStudentRank'])->name('admin.refreshStudentRank');

    Route::prefix('home')->group(function () {
        Route::get('/slider', [HomeController::class, 'slider'])->name('admin.home.slider');
        Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('admin.home.testimonials');
        Route::post('/testimonialSubmit', [HomeController::class, 'testimonialSubmit'])->name('admin.home.testimonialSubmit');
        Route::get('/deleteTestimonials/{id}', [HomeController::class, 'deleteTestimonials'])->name('admin.home.deleteTestimonials');
        Route::get('/govtwebsite', [HomeController::class, 'govtwebsite'])->name('admin.home.govtwebsite');
        Route::post('/savegovtwebsite', [HomeController::class, 'savegovtwebsite'])->name('admin.home.savegovtwebsite');
        Route::get('/deleteGovtwebsite/{id}', [HomeController::class, 'deleteGovtwebsite'])->name('admin.home.deleteGovtwebsite');
        Route::post('/saveSlider', [HomeController::class, 'saveSlider'])->name('admin.home.saveSlider');
        Route::get('/deleteSlider/{id}', [HomeController::class, 'deleteSlider'])->name('admin.home.deleteSlider');
        Route::any('/course/{courseDetailsModel?}', [CourseController::class, 'coursesubmit'])->name('admin.home.course');
        Route::any('/course_list', [CourseController::class, 'courseList'])->name('admin.home.courseList');
        Route::any('/course_delete/{courseDetailsModel?}', [CourseController::class, 'courseDelete'])->name('admin.home.courseDelete');
        Route::post('/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('toggle.featured');
        Route::post('/toggle-status', [CourseController::class, 'toggleStatus'])->name('toggle.status');

        Route::get('/faq', [HomeController::class, 'faq'])->name('admin.home.faq');
        Route::post('/faqSave', [HomeController::class, 'faqSave'])->name('admin.home.faqSave');
        Route::get('/faqDelete/{id}', [HomeController::class, 'faqDelete'])->name('admin.home.faqDelete');

        Route::get('/eprospectus', [HomeController::class, 'prospectus'])->name('admin.home.eprospectus');
        Route::post('/eprospectusSave', [HomeController::class, 'prospectusSave'])->name('admin.home.eprospectusSave');
        Route::get('/eprospectusDelete/{id}', [HomeController::class, 'prospectusDelete'])->name('admin.home.eprospectusDelete');
        Route::post('/prospect_toggle-status', [CourseController::class, 'pospectToggleStatus'])->name('prospect_toggle.status');
        Route::any('/our_journey', [HomeController::class, 'ourJourney'])->name('admin.home.ourJourney');
        Route::any('/our_journey_delete/{id}', [HomeController::class, 'ourJourneyDelete'])->name('admin.home.ourJourneyDelete');

        Route::any('/our_contributor', [HomeController::class, 'ourContributor'])->name('admin.home.ourContributor');
        Route::any('/our_contributor_delete/{id}', [HomeController::class, 'ourContributorDelete'])->name('admin.home.ourContributorDelete');

        Route::any('/benefit', [HomeController::class, 'benefits'])->name('admin.home.benefit');
        Route::get('/deletebenefits/{id}', [HomeController::class, 'deletebenefits'])->name('admin.home.deletebenefits');

        Route::post('/testimonial-toggle-status', [AdminController::class, 'testimonialToggleStatus'])->name('testimonial.toggle.status');

        Route::any('/about_us', [AdminController::class, 'aboutUs'])->name('admin.aboutus');
        Route::any('/about_us_delete/{form_type}/{id}', [AdminController::class, 'aboutUsDelete'])->name('admin.home.aboutUsDelete');
        Route::post('/about_us_status_toggle', [AdminController::class, 'aboutUsStatusToggle'])->name('about_us.toggleStatus');

        Route::any('/scholarship', [AdminController::class, 'scholarship'])->name('admin.scholarship');
        Route::any('/scholarship_delete/{form_type}/{id}', [AdminController::class, 'scholarshipDelete'])->name('admin.home.scholarshipDelete');
        Route::post('/scholarship_status_toggle', [AdminController::class, 'scholarshipStatusToggle'])->name('scholarship.toggleStatus');

        Route::post('student-papers_import', [AdminController::class, 'studentPaperImport'])->name('student_papers.import');

        Route::post('student-papers_subject_details', [AdminController::class, 'subjectPaperDetailsAdd'])->name('admin.subjectPaperDetailsAdd');

        Route::get('/terms_condition', [AdminController::class, 'termsCondition'])->name('admin.terms_condition');
        Route::post('/terms_conditionSave', [AdminController::class, 'termsConditionSave'])->name('admin.terms_conditionSave');
        Route::get('/terms_conditionDelete/{id}', [AdminController::class, 'termsConditionDelete'])->name('admin.terms_conditionDelete');
        Route::post('/terms_condition_toggle_status', [AdminController::class, 'termsConditionToggleStatus'])->name('terms_condition_toggle.status');


        Route::get('/privacy_policy', [AdminController::class, 'privacyPolicy'])->name('admin.privacy_policy');
        Route::post('/privacyPolicySave', [AdminController::class, 'privacyPolicySave'])->name('admin.privacyPolicySave');
        Route::get('/privacyPolicyDelete/{id}', [AdminController::class, 'privacyPolicyDelete'])->name('admin.privacyPolicyDelete');
        Route::post('/privacyPolicyToggleStatus', [AdminController::class, 'privacyPolicyToggleStatus'])->name('privacyPolicyToggleStatus.status');



        Route::match(['get', 'post'], 'payment-settings', [AdminController::class, 'paymentSettings'])->name('payment-settings.store');
        Route::any('/mobile_number', [AdminController::class, 'mobileNumber'])->name('admin.mobile_number');
        Route::any('/mobile_toggle-status', [AdminController::class, 'toggleMobileStatus'])->name('mobile.number.statusToggle');
        Route::any('/mobile_toggle-delete/{mobileNumber}', [AdminController::class, 'mobileNumberDelete'])->name('status_mobile_number.delete');

        Route::any('/districts', Districts::class)->name('admin.district-settings');
        Route::any('/states', States::class)->name('admin.state-settings');
        Route::get('/important-links', ImportantLinkSettings::class)->name('admin.important-links');
        Route::any('/scholarship-forms', ScholarshipForms::class)->name('admin.scholarship-forms');

        Route::any('/policy-pages', PolicyPagesList::class)->name('admin.policy-pages');
        Route::any('/policy-pages/{page}', PolicyPageAddUpdate::class)->name('admin.policy-page-update');
    });

    Route::prefix('news')->group(function () {
        Route::get('/notification', [NewsController::class, 'notification'])->name('news.notification');
        Route::post('/notificationSave', [NewsController::class, 'notificationSave'])->name('news.notificationSave');
        Route::get('/notificationDelete/{id}', [NewsController::class, 'notificationDelete'])->name('news.notificationDelete');

        Route::get('/blognews/{id?}', [NewsController::class, 'blognews'])->name('news.blognews');
        Route::post('/blogSave', [NewsController::class, 'blogSave'])->name('news.blogSave');
        Route::get('/blogDelete/{id}', [NewsController::class, 'blogDelete'])->name('news.blogDelete');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/popup', PopupSetting::class)->name('settings.popup');
        Route::get('/registration', RegistrationSetting::class)->name('settings.registration');
        Route::get('/reset-portal', ResetPortal::class)->name('settings.resetPortal');
    });

    Route::any('/file/upload', [ImageController::class, 'upload'])->name('file.upload');
    Route::any('/file_upload/{id}', [ImageController::class, 'uploadDelete'])->name('image_upload.delete');
});

Route::prefix('coupon')->group(function () {
    Route::group(['middleware' => ['auth']], function () {
        // Route::any('/lists', [CouponCodeController::class, 'lists'])->name('coupon.lists');
        Route::any('/lists', CouponList::class)->name('coupon.lists');
        Route::any('/filter', [CouponCodeController::class, 'filter'])->name('coupon.filter');
        Route::get('/manage', [CouponCodeController::class, 'manage'])->name('coupon.manage');
        Route::any('/createCoupon', CreateCoupon::class)->name('coupon.createCoupon');
    });

    Route::any('/change_password', [AdminController::class, 'changePassword'])->name('admin.changePassword');
});

Route::prefix('course')->group(function () {
    // Route::get('/category/{category?}', [CourseController::class, 'category'])->name('course.category');
    // Route::get('/subcategoryall', [CourseController::class, 'subcategoryall'])->name('course.subcategory');
    // Route::get('/subcategorybyId/{id}', [CourseController::class, 'subcategorybyId'])->name('courses.subcategorybyid');
    // Route::get('/deleteCategory/{id}', [CourseController::class, 'deleteCategory'])->name('courses.deleteCategory');
    // Route::post('/savecategory/{category?}', [CourseController::class, 'savecategory'])->name('course.savecategory');
    // Route::post('/savesubcategory', [CourseController::class, 'savesubcategory'])->name('course.savesubcategory');

    // Route::get('/subjects', [CourseController::class, 'subjects'])->name('course.subjects');
    // Route::get('/courses', [CourseController::class, 'courses'])->name('course.courses');
    // Route::get('/courseslist', [CourseController::class, 'courseslist'])->name('course.courseslist');
    // Route::get('/courses', [CourseController::class, 'courses'])->name('course.courses');
    // Route::post('/coursesubmit', [CourseController::class, 'coursesubmit'])->name('course.coursesubmit');

    // Route::post('/savesubjects', [CourseController::class, 'savesubjects'])->name('course.savesubjects');
    // Route::get('/subjectssubcategory', [CourseController::class, 'subjectssubcategory'])->name('course.subjectssubcategory');
    // Route::post('/savesubjectsubcategory', [CourseController::class, 'savesubjectsubcategory'])->name('course.savesubjectsubcategory');

    // Route::post('/savesubject', [CourseController::class, 'savesubject'])->name('course.savesubject');
    // Route::get('/deleteSubject/{id}', [CourseController::class, 'deleteSubject'])->name('courses.deleteSubject');

    // Route::get('/classList', [CourseController::class, 'classList'])->name('course.classList');


    Route::any('education-type', [ExamsController::class, 'eductaion_type'])->name('dashboard_eductaion_type');
    Route::any('subjects', [ExamsController::class, 'subjects'])->name('dashboard_subjects');
});

// require __DIR__ . '/auth.php';
