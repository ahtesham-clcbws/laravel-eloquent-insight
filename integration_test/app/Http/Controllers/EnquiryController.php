<?php

namespace App\Http\Controllers;

use App\Mail\InstituteCollaborationApproved;
use App\Mail\OTPMail;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\CouponCode;
use App\Models\QuickContactModel;
use App\Notifications\ContactInfoReplyMail;
use App\Notifications\InstitudeApprovedMail;
use App\Notifications\InstitudeRejectMail;
use App\Notifications\InstitudeReplyMail;
use App\Notifications\InstitudeSignApproveMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function instituteListNew(Request $request, $id = null)
    {
        if (!is_null($id)) {
            $corporate = Corporate::find($id);

            if (is_null($corporate->institude_code)) {
                $newInstitudeCode = institudeCodeGenerate($corporate->institute_name);
                $corporate->institude_code = $newInstitudeCode;
                $corporate->save();
            }

            return view('administrator.dashboard.institude.institude_list_new_view', ['corporate' => $corporate]);
        }
        $enq = Corporate::where('is_approved', 0)->whereNull('signup_at')->orderBy('id', 'desc')->get();

        return view('administrator.dashboard.institude.institude_list_new', ['institute' => $enq]);
    }

    public function instituteList()
    {
        $enq = Corporate::where('is_approved', 1)
            ->where('signup_approved', 1)
            ->orderBy('id', 'desc')
            ->whereNotNull('signup_at')
            ->get();

        return view('administrator.dashboard.institude.institude_list', ['institute' => $enq]);
    }

    public function instituteListSignup($id = null)
    {
        if ($id) {
            $enq = Corporate::where('is_approved', 1)
                ->where('signup_approved', 0)
                ->where('id', $id)
                ->first();
            return view('administrator.dashboard.institude.institude_list_signup_view', ['corporate' => $enq]);
        }
        $enq = Corporate::where('is_approved', 1)
            ->where('signup_approved', 0)
            ->orderBy('id', 'desc')
            ->get();

        return view('administrator.dashboard.institude.institude_list_signup', ['institute' => $enq]);
    }

    public function quickContact()
    {
        $enq = QuickContactModel::all();
        // return view('Admin.Enquiry.quickContact', ['enquiry' => $enq]);
    }

    public function instituteView(Corporate $corporate)
    {
        $coupons = CouponCode::select(
            'name',
            'prefix',
            DB::raw('COUNT(prefix) as prefix_count'),
            DB::raw('COUNT(CASE WHEN is_applied = 1 THEN 1 END) as applied_count'),
            DB::raw('COUNT(CASE WHEN is_issued = 1 THEN 1 END) as issued_count')
        )
            ->groupBy('prefix', 'name')
            ->get();

        $totalIssuedCount = CouponCode::where('corporate_id', $corporate->id)->count();

        return view('administrator.dashboard.institude.institudeView', compact('corporate', 'coupons', 'totalIssuedCount'));
    }

    public function contactPage()
    {
        $enq = ContactInfo::all();
        // return view('Admin.Enquiry.contactPage', ['enquiry' => $enq]);
    }

    public function replymail(Request $request)
    {
        $request->validate(
            [
                'emailto' => 'required|email',
                'emailsubject' => 'required',
                'message' => 'required',
            ]
        );
    }

    public function instituteStatus(Request $request)
    {
        $corporate = Corporate::where('id', $request->id)->where('phone', $request->phone)->first();

        if ($corporate && $request->type == 'approved') {
            if (is_null($corporate->institude_code)) {
                $newInstitudeCode = institudeCodeGenerate($corporate->institute_name);
                $corporate->institude_code = $newInstitudeCode;
            }

            $corporate->is_approved = true;
            $corporate->message = $request->message;
            $corporate->save();

            $data = [
                'name' => $corporate->name,
                'institute_name' => $corporate->institute_name,
                'email' => $corporate->email,
                'city' => $corporate->district?->name ? $corporate->district->name : null,
                'mobile' => $corporate->phone,
                'code' => $corporate->institude_code,
            ];
            Mail::to($corporate)->send(new InstituteCollaborationApproved($data));

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Corporate approved successfully.',
                'code' => $corporate->institude_code,
            ]);
        } else if ($corporate && $request->type == 'reply') {
            $corporate->message = $request->message;
            $corporate->save();

            $corporate->notify(new InstitudeReplyMail($corporate));
            return response()->json([
                'success' => true,
                'message' => 'Corporate mail successfully.',
                'code' => $corporate->institude_code,
            ]);
        } else if ($corporate && $request->type == 'reject') {
            $corporate->message = $request->message;
            $corporate->is_rejected = true;
            $corporate->is_approved = false;
            $corporate->save();

            $corporate->notify(new InstitudeRejectMail($corporate));

            return response()->json([
                'success' => true,
                'message' => 'Corporate Rejected successfully.',
                'code' => $corporate->institude_code,
            ]);
        } else if ($corporate && $request->type == 'signup-approve') {
            $corporate->message = $request->message;
            $corporate->signup_approved = true;
            $corporate->status = true;
            $corporate->save();

            $corporate->notify(new InstitudeSignApproveMail($corporate));

            return response()->json([
                'success' => true,
                'message' => 'Corporate SignUp Approved successfully.',
                'code' => $corporate->institude_code,
            ]);
        } else if ($corporate && $request->type == 'toggle-display') {
            $corporate->status = $request->status;
            $corporate->save();

            return response()->json([
                'success' => true,
                'message' => 'Institute display status updated successfully.',
            ]);
        } else {
            // Return a failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to approved corporate record. Either the corporate does not exist or the request type is invalid.'
            ]);
        }
    }

    public function contactEnquiry(Request $request)
    {
        $contactInfo = ContactInfo::orderBy('id', 'desc')->get();

        return view('administrator.contact_enquiry.contact_enquiry', compact('contactInfo'));
    }

    public function contactEnquiryDelete(Request $request, ContactInfo $contactInfo)
    {
        try {
            $contactInfo->delete();

            return back()->withErrors('Deleted Successfully.');
        } catch (\Throwable $th) {
            throw $th;
            return back()->withErrors('Failed to Delete.');
        }
    }

    public function contactEnquiryReplyMail(Request $request, ContactInfo $contactInfo)
    {
        try {
            $request->validate([
                'email_message' => 'required'
            ]);

            if ($contactInfo) {
                $contactInfo->replyMails()->create([
                    'message' => $request->email_message,
                ]);
                if ($contactInfo->email) {
                    $contactInfo->notify(new ContactInfoReplyMail($contactInfo, $request->email_message));
                }
            }

            return back()->with('success', 'Mail Send Successfully.');
        } catch (\Throwable $th) {
            throw $th;
            return back()->withErrors('Failed to Send Mail.');
        }
    }

    public function printNewInstituteEnquiry(Request $request)
    {
        $enq = Corporate::where('is_approved', 0)->whereNull('signup_at')->get();

        $pdf = Pdf::loadView('administrator/download/new-institute-enquiry', ['institute' => $enq]);

        return $pdf->stream('New Institute Enquiry List on ' . date('d-m-Y His A') . '.pdf');
    }

    public function printSignUpInstituteList(Request $request)
    {
        $enq = Corporate::where('is_approved', 1)
            ->where('signup_approved', 0)
            ->get();

        $pdf = Pdf::loadView('administrator/download/print-signup-institute-list', ['institute' => $enq]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Sign Up Institute List on ' . date('d-m-Y His A') . '.pdf');
    }

    public function printApproveInstituteList(Request $request)
    {
        $enq = Corporate::where('is_approved', 1)
            ->where('signup_approved', 1)
            ->whereNotNull('signup_at')
            ->get();

        $pdf = Pdf::loadView('administrator/download/print-approve-institute-list', ['institute' => $enq]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Approve Institute List on ' . date('d-m-Y His A') . '.pdf');
    }
}
