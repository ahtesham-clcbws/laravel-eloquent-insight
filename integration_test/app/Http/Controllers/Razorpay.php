<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Models\Student;
use App\Models\StudentCode;
use App\Models\StudentPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class Razorpay extends Controller
{
    public function index()
    {
        $student = Student::find(Auth::guard('student')->id());
        $studentCode = $student->latestStudentCode;
        $feeAmount =  $studentCode->is_coupan_code_applied ? $studentCode->fee_amount : 850;

        if ($feeAmount <= 0) {
            $studentCode->is_paid = true;
            $studentCode->save();
            return redirect()->route('student.payment')->with(['success' => 'Registration Completed successfully']);
        }
        
        return view('payment.razorpay', compact('studentFee', 'student'));
    }



    public function store(Request $request)
    {
        $input = $request->all();

        $student = Student::find(Auth::guard('student')->id());
        $studentCode = $student->latestStudentCode;

        $paymentSettings = PaymentSetting::first();

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // if($paymentSettings)
        // $api = new Api($paymentSettings->key_id, $paymentSettings->key_secret);
       

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

                $payment = Payment::create([
                    'r_payment_id' => $response['id'],
                    'method' => $response['method'],
                    'currency' => $response['currency'],
                    'user_email' => $response['email'],
                    'amount' => $response['amount'] / 100,
                    'json_response' => json_encode((array) $response)
                ]);
                $studentPayment = new StudentPayment();
                $studentPayment->student_id = auth()->guard('student')->id();
                $studentPayment->course_type = $student->scholarShipCategory?->name; 
                $studentPayment->course_id = $student->scholarship_opted_for; 
                $studentPayment->institute_id = $studentCode->corporate_id; 
                $studentPayment->payment_amount = $response['amount'] / 100; // Convert amount to currency unit (e.g., rupees)
                $studentPayment->payment_order_id = $response['id'];
                $studentPayment->payment_status = 'success';
                $studentPayment->save();

                $studentCode->is_paid = true;
                $studentCode->save();

                return redirect()->route('student.payment')->with(['success' => 'Paid successfully']);
            } catch (Exception $e) {
                logger('payment save failed:', [$e]);
                Session::put('error', $e->getMessage());
             
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
   
        Session::put('success', 'Payment Successful');
        return redirect()->back()->with('success', 'Payment Successful');
    }
}
         