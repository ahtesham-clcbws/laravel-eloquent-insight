<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\BoardAgencyStateModel;
use App\Models\ClassGoupExamModel;
use App\Models\Corporate;
use App\Models\CorporateCouponRequest;
use App\Models\CouponCode;
use App\Models\Gn_DisplayExamAgencyBoardUniversity;
use App\Models\Gn_DisplayOtherExamClassDetail;
use App\Models\Gn_EducationClassExamAgencyBoardUniversity;
use App\Models\Gn_OtherExamClassDetailModel;
use App\Models\MobileNumber;
use App\Models\OtpVerifications;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentClaimForm;
use App\Models\StudentCode;
use App\Models\StudentPaperExported;
use App\Models\StudentPayment;
use App\Models\TestimonialsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class ResetPortal extends Component
{
    public function render()
    {
        return view('livewire.administrator.settings.reset-portal');
    }

    public function resetPortal()
    {
        try {
            // DB::beginTransaction();
            TestimonialsModel::truncate();
            StudentCode::truncate();
            CouponCode::truncate();
            StudentClaimForm::truncate();
            StudentPaperExported::truncate();
            StudentPayment::truncate();
            MobileNumber::truncate();
            OtpVerifications::truncate();
            Payment::truncate();
            CorporateCouponRequest::truncate();
            Corporate::truncate();
            Student::truncate();
            // DB::commit();
            $this->js('window.location.href = "/administrator"');
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
        }
    }
}
