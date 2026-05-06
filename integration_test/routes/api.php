<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;
use App\Models\DistrictScholarshipLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('test', function(){
    $data = DistrictScholarshipLimit::with('EducationType:id,name')->with('District:id,name')->orderBy('district_id', 'asc')->orderBy('education_type_id', 'asc')->get();
    return print_r($data->toArray());
});

// auth API
Route::post('check-credentials', [AuthController::class, 'verifyMobileEmail']);
Route::post('verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('register', [AuthController::class, 'rgisterUser']);
Route::post('login', [AuthController::class, 'loginUser']);
Route::get('apply-form-data', [ApplicationController::class, 'index']);
Route::get('scholarships-by-qualification/{id}', [ApplicationController::class, 'onSelectQualification_ScholarshipCategory']);
Route::get('scholarships-by-scholarship/{id}/{qualificationId}', [ApplicationController::class, 'getScholarshipCategoryOptedFor']);


// authenticated api only
Route::post('application-submit', [ApplicationController::class, 'applicationSubmition'])->middleware('auth:sanctum');
Route::post('coupon/apply', [ApplicationController::class, 'applyCoupon'])->middleware('auth:sanctum');
Route::post('coupon/remove', [ApplicationController::class, 'removeCoupon'])->middleware('auth:sanctum');
Route::post('pay-now', [ApplicationController::class, 'studentPaymentCallback'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

