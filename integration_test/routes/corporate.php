<?php

use App\Http\Controllers\CorporateController;
use App\Livewire\Auth\Corporate\InstituteSignUpForm;
use App\Livewire\Corporate\AddTestimonial;
use App\Livewire\Corporate\CouponList;
use App\Livewire\Corporate\CouponRequest;
use App\Livewire\Corporate\Profile;
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

Route::match(['get', 'post'], 'login', [CorporateController::class, 'login'])->name('corporatelogin');
// Route::match(['get', 'post'], '/signup/{branch_code?}', [CorporateController::class, 'signup'])->name('corporateSignup');
Route::any('/signup/{branch_code?}', InstituteSignUpForm::class)->name('corporateSignup');

// Route::match(['get', 'post'], '/forgotpassword', [CorporateController::class, 'forgotpassword'])->name('corporate.forgotpassword');

//Route::post('/reset_forget_password', [CorporateController::class, 'resetForgotPassword'])->name('corporate.resetforgetPassword');

Route::group(['middleware' => ['corporate']], function () {
    Route::get('/corporate_logout', [CorporateController::class, 'logout'])->name('corporate.logout');
    Route::get('/', [CorporateController::class, 'index'])->name('corporateDashboard');

    Route::post('/corporate_otp_verification_mobile_no_change', [CorporateController::class, 'CorporateSendVerificationOtpMobileNoChange']);

    Route::get('/student_list/{student?}', [CorporateController::class, 'studentList'])->name('corporateStudent');

    Route::post('/update-admitcard-status', [CorporateController::class, 'updateAdmitCardStatus'])->name('corporate.update.admitcard.status');
    // Route::any('/corporate_coupon_list/{corporate?}', [CorporateController::class, 'corporateCouponlist'])->name('corporate.couponlist');
    Route::any('/corporate_coupon_list/{corporate?}', CouponList::class)->name('corporate.couponlist');

    // Route::any('/say_about_us', [CorporateController::class, 'sayAboutUs'])->name('corporate.sayAboutUs');
    Route::any('/say_about_us', AddTestimonial::class)->name('corporate.sayAboutUs');

    Route::any('/change_password', [CorporateController::class, 'changePassword'])->name('corporate.changePassword');
    // Route::any('/profile', [CorporateController::class, 'profilePage'])->name('corporate.profilePage');
    Route::any('/profile', Profile::class)->name('corporate.profilePage');
    Route::post('/upload-photo',  [CorporateController::class, 'uploadPhoto'])->name('corporate.upload.photo');

    Route::any('/coupon-request', CouponRequest::class)->name('corporate.couponRequest');
});
