<?php

use App\Http\Controllers\CenterController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternalRequestsController;
use App\Http\Controllers\Razorpay;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use App\Livewire\Auth\CorporateForgetPassword;
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\Registration;
use App\Livewire\Pages\FreeForm;
use App\Livewire\Pages\ImportantLinksWebsitePage;
use App\Livewire\Student\AddTestimonial;
use App\Livewire\Student\ApplyForm\AdditionalDetailsForm;
use App\Livewire\Student\PaymentPage;
use App\Livewire\Student\StudentProfile;
use App\Livewire\Website\Corporate\EnquiryForm;
use App\Livewire\Website\PolicyPageFrontend;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('storage-link', function () {
    Artisan::call('storage:link');
});


Route::any('testing', [TestController::class, 'index']);


Route::get('/', [HomeController::class, 'index'])->name('website.homepage');

Route::post('/', [InternalRequestsController::class, 'index']);
Route::post('/otp_verification', [HomeController::class, 'sendVerificationOtp']);
Route::post('/corporate_otp_verification', [HomeController::class, 'CorporateSendVerificationOtp']);
Route::post('/forget_password', [HomeController::class, 'forgetPassword'])->name('student.forgetPassword');
Route::get('/student_recover_password/{id}/{reset_id}', [HomeController::class, 'studentRecoverPassword'])->name('student_recover_password');
Route::post('/reset_forget_password', [HomeController::class, 'resetForgotPassword'])->name('corporate.resetforgetPassword');


Route::get('/forget-password', ForgetPassword::class)->name('forgetPassword');
Route::get('/institute/forget-password', CorporateForgetPassword::class)->name('corporate.forgotpassword');

// Route::prefix('homepage')->group(function () {
Route::prefix('')->group(function () {
    Route::get('/slider', [HomeController::class, 'slider'])->name('home.slider');
    Route::get('/govtwebsite', [HomeController::class, 'govtwebsite'])->name('home.govtwebsite');
    Route::post('/savegovtwebsite', [HomeController::class, 'savegovtwebsite'])->name('home.savegovtwebsite');
    Route::get('/deleteGovtwebsite/{id}', [HomeController::class, 'deleteGovtwebsite'])->name('home.deleteGovtwebsite');
    Route::post('/saveSlider', [HomeController::class, 'saveSlider'])->name('home.saveSlider');
    Route::get('/deleteSlider/{id}', [HomeController::class, 'deleteSlider'])->name('home.deleteSlider');
    Route::get('/benefit', [HomeController::class, 'benefit'])->name('home.benefit');
    Route::get('/deletebenefits/{id}', [HomeController::class, 'deletebenefits'])->name('home.deletebenefits');
    Route::get('/career/{course?}', [HomeController::class, 'career'])->name('home.career');
    Route::get('/faq', [HomeController::class, 'faqList'])->name('home.faq');

    Route::post('/savebenefits', [HomeController::class, 'savebenefits'])->name('home.savebenefits');
    Route::post('/contactinsert', [HomeController::class, 'contactinsert'])->name('home.contactinsert');

    Route::post('/corporateSubmit', [HomeController::class, 'corporateSubmit'])->name('home.corporateSubmit');
    Route::post('/sendOtp', [HomeController::class, 'sendOtp'])->name('home.sendOtp');
    Route::post('/sendMail', [HomeController::class, 'sendMail'])->name('home.sendMail');
    Route::get('/aboutus', [HomeController::class, 'aboutus'])->name('home.aboutus');
    Route::get('/preparation', [HomeController::class, 'preparation'])->name('home.preparation');
    Route::get('/preparation-course/{course?}', [HomeController::class, 'career'])->name('home.preparation_course');
    Route::get('/scholarship', [HomeController::class, 'scholarship'])->name('home.scholarship');
    Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
    Route::get('/', [HomeController::class, 'index'])->name('home.front');
    Route::post('/enquirySubmit', [HomeController::class, 'enquirySubmit'])->name('home.contactPage');
    Route::get('/company', [HomeController::class, 'company'])->name('home.company');
    Route::post('/companyInsert', [HomeController::class, 'companyInsert'])->name('home.companyInsert');
    Route::get('/contactInfo', [HomeController::class, 'contactInfo'])->name('home.contactInfo');
    Route::post('/saveCompany', [HomeController::class, 'saveCompany'])->name('home.saveCompany');

    Route::get('/scholarshipList', [HomeController::class, 'scholarshipList'])->name('home.scholarshipList');
    Route::post('/savescholorship', [HomeController::class, 'savescholorship'])->name('home.savescholorship');
    Route::get('/addscholorship', [HomeController::class, 'addscholorship'])->name('home.addscholorship');

    Route::post('/usersignup', [HomeController::class, 'usersignup'])->name('home.usersignup');
    Route::post('/userLogins', [HomeController::class, 'userLoginCheck'])->name('home.userLogins');
    Route::get('/logout', [HomeController::class, 'logout'])->name('home.logout');

    // ahtesham create those routes
    Route::redirect('/free-form', '/authentication-code', 302);
    Route::get('/authentication-code', FreeForm::class)->name('freeform');
    Route::get('/important-links', ImportantLinksWebsitePage::class)->name('home.important-links');

    Route::get('registration', Registration::class)->name('registration');
    // Route::get('institute/enquiry', EnquiryForm::class)->name('corporateEnquiry');
    Route::get('collaboration-form', EnquiryForm::class)->name('corporateEnquiry');
});

Route::prefix('result')->group(function () {
    Route::get('/newresult', [ResultController::class, 'newresult'])->name('result.newresult');
    Route::get('/previousresult', [ResultController::class, 'subcategory'])->name('result.previousresult');
});

Route::prefix('centers')->group(function () {
    Route::get('/lists', [CenterController::class, 'list'])->name('centers.lists');
    Route::post('/addnew', [CenterController::class, 'addCenter'])->name('centers.addnew');
    Route::get('/manage', [CenterController::class, 'manage'])->name('centers.manage');
});

Route::prefix('enquiry')->group(function () {
    Route::get('/instituteList', [EnquiryController::class, 'instituteList'])->name('enquiry.institute');
    Route::get('/studentList', [EnquiryController::class, 'subcategory'])->name('enquiry.students');
    Route::get('/quickContact', [EnquiryController::class, 'quickContact'])->name('enquiry.quickContact');
    Route::get('/contactPage', [EnquiryController::class, 'contactPage'])->name('enquiry.contactPage');
    Route::post('/replymail', [EnquiryController::class, 'replymail'])->name('enquiry.replymail');
});

Route::get('dashboard', function () {
    return redirect()->to('/administrator');
});

Route::middleware('auth')->group(function () {
    Route::get('/scholarshipForm', [HomeController::class, 'scholarshipForm'])->name('home.scholarshipForm');
    Route::post('/scholorship_insert', [HomeController::class, 'scholorship_insert'])->name('home.scholorship_insert');
    Route::get('/home.couponcode', [HomeController::class, 'couponcode'])->name('home.couponcode');

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/result', [ProfileController::class, 'result'])->name('profile.result');
    // Route::post('/searchresult', [ProfileController::class, 'searchresult'])->name('profile.searchresult');
});

Route::post('/studentsignup', [StudentController::class, 'usersignup'])->name('studentSignup');
Route::post('/studentlogin', [StudentController::class, 'login'])->name('studentlogin');
Route::prefix('students')->group(function () {
    Route::group(['middleware' => ['student']], function () {
        Route::get('/studentDashboard', [StudentController::class, 'studentDashboard'])->name('studentDashboard');

        // apply form
        Route::get('/studentform', [StudentController::class, 'studentform'])->name('studentform');
        Route::post('/addstudents', [StudentController::class, 'addstudent'])->name('students.addstudent');
        Route::get('/addqualificationscreate', [StudentController::class, 'addQualificationsCreate'])->name('students.addQualificationsCreate');
        Route::post('/addqualifications', [StudentController::class, 'addQualifications'])->name('students.addQualifications');

        // Route::get('/additional_details_create', [StudentController::class, 'additionalDetailsCreate'])->name('students.additionalDetailCreate');
        Route::get('/additional_details_create', AdditionalDetailsForm::class)->name('students.additionalDetailCreate');

        Route::post('/additional_details_Store', [StudentController::class, 'additionalDetailStore'])->name('students.additionalDetailStore');
        Route::post('/final_submit', [StudentController::class, 'finalSubmit'])->name('students.finalSubmit');
        Route::get('/get_scholarship_category/{id?}/{type?}', [StudentController::class, 'getScholarshipCategory']);
        Route::get('/get_scholarship_opted_for/{id?}/{qualificationId?}', [StudentController::class, 'getScholarshipCategoryOptedFor']);
    });
    Route::group(['middleware' => ['StudentCommanMiddleware']], function () {
        Route::get('/student_logout', [StudentController::class, 'logout'])->name('student.logout');
        Route::get('/form_review', [StudentController::class, 'form_review'])->name('students.formReview');
        Route::get('/admin_card_find', [StudentController::class, 'admitCardSearch'])->name('students.admitCardSearch');
        Route::post('/admin_card', [StudentController::class, 'admitCard'])->name('students.admitCard');
        Route::get('/result_download', [StudentController::class, 'resultDownload'])->name('students.resultdownload');
        Route::post('/coupon_code_apply', [StudentController::class, 'applyCoupon'])->name('students.couponCodeApply');
        Route::post('/removeCoupon', [StudentController::class, 'removeCoupon'])->name('students.removeCoupon');
        Route::any('/change_password', [StudentController::class, 'changePassword'])->name('students.changePassword');
        // Route::any('/profile', [StudentController::class, 'profilePage'])->name('students.profilePage');
        Route::any('/profile', StudentProfile::class)->name('students.profilePage');
        Route::post('/upload-photo',  [StudentController::class, 'uploadPhoto'])->name('upload.photo');

        Route::any('/say_about_us', AddTestimonial::class)->name('student.sayAboutUs');
        // Route::get('/say-about-us', AddTestimonial::class);

        Route::post('razorpay-payment', [Razorpay::class, 'store'])->name('razorpay.payment.store');

        Route::any('/claim_scholarship_form', [StudentController::class, 'claimScholarshipForm'])->name('students.claimScholarshipForm');
        Route::any('/claim_scholarship_form_save', [StudentController::class, 'claimScholarshipFormSave'])->name('students.claimScholarshipFormSave');
        Route::get('/studentDashboard', [StudentController::class, 'studentDashboardsss'])->name('studentDashboard');
    });
    Route::group(['middleware' => ['IsStudentFinallySubmitted']], function () {
        // Route::get('/payment', [StudentController::class, 'student_payment'])->name('student.payment');
        Route::get('/payment', PaymentPage::class)->name('student.payment');
        Route::get('/studentDashboardpaid', [StudentController::class, 'studentDashboardAfterPaid'])->name('studentDashboardAfterPaid');
        Route::get('/razor_payment', [Razorpay::class, 'index'])->name('student.paymentCreate');
    });
});

Route::get('/categories', [CommonController::class, 'categoryAll'])->name('category');
Route::get('subcategories/{category}', [CommonController::class, 'subcategory'])->name('subcategory');
Route::get('subjects/{subcategory}', [CommonController::class, 'subject'])->name('subject');
Route::get('districts/{state}', [CommonController::class, 'districts']);

Route::get('p/{slug}', PolicyPageFrontend::class)->name('website.policy-page');

// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });
