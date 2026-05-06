<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpVerifications;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\Msg91Service;

class AuthController extends Controller
{
    /**
     * Verifies the provided mobile and email to ensure they are not already in use.
     * If not in use, generates and saves an OTP for the mobile number.
     *
     * @param Request $request The request object containing the email and mobile number to be verified.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with success status, message, and optionally, error details.
     * @throws \Throwable If an error occurs during the process.
     */
    public function verifyMobileEmail(Request $request)
    {
        try {
            $messages = [];
            $success = true;

            // Check if email is in use
            $checkEmail = Student::where('email', $request->email)->first();
            if ($checkEmail) {
                $messages[] = 'Email already in use.';
                $success = false;
            }

            // Check if mobile is in use
            $checkMobile = Student::where('mobile', $request->mobile)->first();
            if ($checkMobile) {
                $messages[] = 'Mobile number already in use.';
                $success = false;
            }

            // If either email or mobile is already in use
            if (!$success) {
                return response()->json(['success' => $success, 'message' => $messages], 200);
            }

            // Generate OTP
            $otp = mt_rand(100000, 999999);
 
            // Send SMS via MSG91 (This also saves to OtpVerifications database centrally)
            app(Msg91Service::class)->sendSms($request->mobile, $otp);

            // Return success response
            return response()->json(['success' => true, 'message' => 'OTP Sent Successfully'], 200);
        } catch (\Throwable $th) {
            // Return error response
            return response()->json(['success' => false, 'message' => $th->getMessage()], $th->getCode());
        }
    }

    public function verifyUserOTP($otp, $mobile)
    {
        if (verifyOtp($otp, $mobile)) {
            $user = User::where('mobile', $mobile)->first();
            Auth::login($user);
            $accessToken = Auth::user()->createToken('authToken')->accessToken;
            return response()->json(['status' => true, 'message' => 'Login Successfully', 'user' => Auth::user(), 'access_token' => $accessToken]);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid OTP'], 401);
        }
    }


    public function rgisterUser(Request $request)
    {
        // return response()->json($request->all());

        try {
            $uniqueMobile = "unique:" . Student::class;
            $uniqueEmail = "unique:" . Student::class;

            $request->validate([
                'name' => 'required|string',
                'email' => "required|string|lowercase|email|$uniqueEmail",
                'mobile' => "required|digits:10|$uniqueMobile",
                'gender' => 'required',
                'disability' => 'required',
                'password' => 'required',
                'confirmpassword' => 'required|same:password',
                'otp' => 'required|digits:6'
            ]);
            if ($this->verifyUserOTP($request->otp, $request->mobile)) {
                $student = new Student();

                $student->name = $request->name;
                $student->email = $request->email;
                $student->mobile = $request->mobile;
                $student->gender = $request->gender;
                $student->disability = $request->disability;

                $student->password = Hash::make($request->password);
                $student->login_password = $request->password;

                // Handle the profile image upload
                if (!empty(trim($request->input('image')))) {
                    $student->photograph = static::saveImage($request->input('image'), 'profile');
                }
                // return response()->json($student);

                $student->save();

                $student = getStudentById($student->id);
                $token = $student->createToken($student->mobile . '-' . $student->email);
                $student->token = $token->plainTextToken;

                return response()->json(['success' => true, 'message' => 'User registered successfully.', 'user' => $student]);
            }
            return response()->json(['success' => false, 'message' => 'OTP is incorrect']);

        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'mobile' => "required|digits:10",
            'password' => 'required',
        ]);

        $student = Student::select('mobile', 'id', 'password', 'email')->where('mobile', $request->mobile)->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.']);
        }

        if (Hash::check($request->password, $student->password)) {
            $student = getStudentById($student->id);
            $token = $student->createToken($student->mobile . '-' . $student->email);
            $student->token = $token->plainTextToken;

            return response()->json(['success' => true, 'message' => 'Student logged in successfully.', 'user' => $student]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid password.']);
        }
    }


    protected function saveImage($supervisor_signature, $imageType = 'profile')
    {
        try {
            // Decode the base64 image
            $signatureData = $supervisor_signature;
            $signatureData = str_replace('data:image/jpg;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signatureContent = base64_decode($signatureData);

            // Create a unique file name
            $fileName = uniqid($imageType.'_', true) . '.jpg';
            $filePath = 'student/' . $imageType . date('/Y/M/') . $fileName;

            // Save the image to the storage
            Storage::disk('public')->put($filePath, $signatureContent);

            return $filePath;
        } catch (\Exception $e) {
            // throw new \Exception($e, 1);
            return null;
        }
    }

}
