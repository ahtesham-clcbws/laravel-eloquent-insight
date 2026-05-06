<?php

namespace App\Livewire\Student;

use App\Models\Student;
use App\Models\StudentClaimForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClaimScholarship extends Component
{
    use WithFileUploads;

    public $student;
    public $claimForm;
    public $cities;

    // Fields for Choice 1
    public $institude_name1, $institude_director1, $institude_mobile1, $whatsapp_no1, $institude_email1, $state1, $city_id1, $institude_address1, $desired_course_detail1, $course_fee1, $course_duration1, $institude_prospectus1;
    
    // Fields for Choice 2
    public $institude_name2, $institude_director2, $institude_mobile2, $whatsapp_no2, $institude_email2, $state2, $city_id2, $institude_address2, $desired_course_detail2, $course_fee2, $course_duration2, $institude_prospectus2;
    
    // Fields for Choice 3
    public $institude_name3, $institude_director3, $institude_mobile3, $whatsapp_no3, $institude_email3, $state3, $city_id3, $institude_address3, $desired_course_detail3, $course_fee3, $course_duration3, $institude_prospectus3;
    
    // Fields for Choice 4
    public $institude_name4, $institude_director4, $institude_mobile4, $whatsapp_no4, $institude_email4, $state4, $city_id4, $institude_address4, $desired_course_detail4, $course_fee4, $course_duration4, $institude_prospectus4;

    public $showMore = false;

    public function mount()
    {
        $this->student = Auth::guard('student')->user();
        $this->cities = $this->student->state?->districts ?? collect();
        $this->claimForm = StudentClaimForm::where('student_id', $this->student->id)->first();

        if ($this->claimForm) {
            foreach ($this->claimForm->getAttributes() as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            if ($this->institude_name3 || $this->institude_name4) {
                $this->showMore = true;
            }
        } else {
            $this->state1 = $this->student->state?->name;
            $this->state2 = $this->student->state?->name;
            $this->state3 = $this->student->state?->name;
            $this->state4 = $this->student->state?->name;
        }
    }

    public function toggleMore()
    {
        $this->showMore = !$this->showMore;
    }

    protected function rules()
    {
        $rules = [
            'institude_name1' => 'required',
            'institude_mobile1' => 'required',
            'city_id1' => 'required',
            'institude_address1' => 'required',
            'desired_course_detail1' => 'required',
            'course_fee1' => 'required',
            'course_duration1' => 'required',

            'institude_name2' => 'required',
            'institude_mobile2' => 'required',
            'city_id2' => 'required',
            'institude_address2' => 'required',
            'desired_course_detail2' => 'required',
            'course_fee2' => 'required',
            'course_duration2' => 'required',
        ];

        // Prospectus validation: only validate if it's a new upload (not a string)
        for ($i = 1; $i <= 4; $i++) {
            $prop = "institude_prospectus$i";
            if ($this->$prop && !is_string($this->$prop)) {
                $rules[$prop] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048';
            } else {
                $rules[$prop] = 'nullable';
            }
        }

        // Choice 3 & 4 validation: required only if the institute name is filled
        for ($i = 3; $i <= 4; $i++) {
            if ($this->{"institude_name$i"}) {
                $rules["institude_name$i"] = 'required';
                $rules["institude_mobile$i"] = 'required';
                $rules["city_id$i"] = 'required';
                $rules["institude_address$i"] = 'required';
                $rules["desired_course_detail$i"] = 'required';
                $rules["course_fee$i"] = 'required';
                $rules["course_duration$i"] = 'required';
            }
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'student_id' => $this->student->id,
            'institude_name1' => $this->institude_name1,
            'institude_director1' => $this->institude_director1,
            'institude_mobile1' => $this->institude_mobile1,
            'whatsapp_no1' => $this->whatsapp_no1,
            'institude_email1' => $this->institude_email1,
            'state1' => $this->state1,
            'city_id1' => $this->city_id1,
            'institude_address1' => $this->institude_address1,
            'desired_course_detail1' => $this->desired_course_detail1,
            'course_fee1' => $this->course_fee1,
            'course_duration1' => $this->course_duration1,

            'institude_name2' => $this->institude_name2,
            'institude_director2' => $this->institude_director2,
            'institude_mobile2' => $this->institude_mobile2,
            'whatsapp_no2' => $this->whatsapp_no2,
            'institude_email2' => $this->institude_email2,
            'state2' => $this->state2,
            'city_id2' => $this->city_id2,
            'institude_address2' => $this->institude_address2,
            'desired_course_detail2' => $this->desired_course_detail2,
            'course_fee2' => $this->course_fee2,
            'course_duration2' => $this->course_duration2,

            'institude_name3' => $this->institude_name3,
            'institude_director3' => $this->institude_director3,
            'institude_mobile3' => $this->institude_mobile3,
            'whatsapp_no3' => $this->whatsapp_no3,
            'institude_email3' => $this->institude_email3,
            'state3' => $this->state3,
            'city_id3' => $this->city_id3,
            'institude_address3' => $this->institude_address3,
            'desired_course_detail3' => $this->desired_course_detail3,
            'course_fee3' => $this->course_fee3,
            'course_duration3' => $this->course_duration3,

            'institude_name4' => $this->institude_name4,
            'institude_director4' => $this->institude_director4,
            'institude_mobile4' => $this->institude_mobile4,
            'whatsapp_no4' => $this->whatsapp_no4,
            'institude_email4' => $this->institude_email4,
            'state4' => $this->state4,
            'city_id4' => $this->city_id4,
            'institude_address4' => $this->institude_address4,
            'desired_course_detail4' => $this->desired_course_detail4,
            'course_fee4' => $this->course_fee4,
            'course_duration4' => $this->course_duration4,
            'status' => 'pending-processing',
        ];

        for ($i = 1; $i <= 4; $i++) {
            $prop = "institude_prospectus$i";
            if ($this->$prop && !is_string($this->$prop)) {
                $data[$prop] = moveFile('upload/', $this->$prop);
            }
        }

        StudentClaimForm::updateOrCreate(
            ['student_id' => $this->student->id],
            $data
        );

        session()->flash('success', 'Scholarship Claim Form saved successfully.');
        return redirect()->route('studentDashboard');
    }

    #[Layout('student.layouts.master')]
    public function render()
    {
        return view('livewire.student.claim-scholarship');
    }
}
