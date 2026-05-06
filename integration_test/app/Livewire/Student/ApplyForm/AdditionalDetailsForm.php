<?php

namespace App\Livewire\Student\ApplyForm;

use App\Models\Student;
use App\Models\TermsCondition;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('student.layouts.master')]
class AdditionalDetailsForm extends Component
{

    use WithFileUploads;

    public Student $student;

    #[Validate('required')]

    public $is_gov_exam_participated = 'no';
    #[Validate('required')]

    public $is_apply_career_without_barrier = 'no';

    // #[Validate('required')]
    #[Validate('accepted', message: 'Please accept the terms and conditions.')]
    public $terms_conditions = true;

    // 'govt_exams_1' => "$examRequired|string",

    #[Validate('requiredif:is_gov_exam_participated,yes', message: 'Govt. exam is required.')]
    public $govt_exams_1;


    public $govt_exams_2;
    // 'year' => "$careerRequired|string",

    #[Validate('requiredif:is_apply_career_without_barrier,yes', message: 'Required.')]
    public $year;

    public $roll_no;

    public $family_income;


    public $father_occupation;

    public $mother_occupation;

    // 'photograph' => "$photoReq|file|mimes:jpeg,png,jpg",
    // #[Validate('required', message: 'Please select student image.')]
    #[Validate('image', message: 'This must be an image.')]
    #[Validate('mimes:jpeg,png', message: 'Image must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'Image size must not exceed 2MB.')]
    public $photo;
    // 'signature' => "$signReq|file|mimes:jpeg,png,jpg",
    // #[Validate('required', message: 'Please select signature image.')]
    #[Validate('image', message: 'This must be an image.')]
    #[Validate('mimes:jpeg,png', message: 'Image must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'Image size must not exceed 2MB.')]
    public $sign;


    public $exam_one_year;

    public $exam_one_result;

    public $exam_two_year;

    public $exam_two_result;
    #[Validate('requiredif:is_apply_career_without_barrier,yes', message: 'Career without Barrier scholarship exam is required.')]
    public $career_exams_1;
    public $career_one_result;
    public $career_exams_2;
    public $career_two_year;
    public $career_two_result;

    public function mount()
    {
        $student = Auth::guard('student')->user();
        $this->student = Student::find($student->id);
        $this->setupData($student);
    }

    public function render()
    {
        // $student = Auth::guard('student')->user();
        // $this->setupData($student);

        $termsCondition = TermsCondition::where('status', 1)->where('type', 'student')->orderBy('created_at')->first();
        return view('livewire.student.apply-form.additional-details-form', [
            // 'student' => $student,
            'termsCondition' => $termsCondition
        ]);
    }

    protected function setupData(Student $user)
    {
        $this->is_gov_exam_participated = $user->is_gov_exam_participated ?? 'no';
        $this->is_apply_career_without_barrier = $user->is_apply_career_without_barrier ?? 'no';
        $this->terms_conditions = $user->terms_conditions;
        $this->govt_exams_1 = $user->govt_exams_1;
        $this->govt_exams_2 = $user->govt_exams_2;
        $this->year = $user->year;
        $this->roll_no = $user->roll_no;
        $this->family_income = $user->family_income;
        $this->father_occupation = $user->father_occupation;
        $this->mother_occupation = $user->mother_occupation;
        $this->exam_one_year = $user->exam_one_year;
        $this->exam_one_result = $user->exam_one_result;
        $this->exam_two_year = $user->exam_two_year;
        $this->exam_two_result = $user->exam_two_result;
        $this->career_exams_1 = $user->career_exams_1;
        $this->career_one_result = $user->career_one_result;
        $this->career_exams_2 = $user->career_exams_2;
        $this->career_two_year = $user->career_two_year;
        $this->career_two_result = $user->career_two_result;

        // $this->photo = $user->photograph;
        // $this->sign = $user->signature;

        // $this->photograph = $user->photograph;
        // $this->signature = $user->signature;
    }


    public function saveAndReview()
    {
        $validatedData = $this->validate();
        $this->js('alert("' . json_encode($validatedData) . '")');
        try {
            // if ($this->validate()) {
            //     $this->js('alert("data validated")');
            // } else {
            //     $this->js('alert("data not validated")');
            // }


            if (!$this->student->photograph) {
                $this->validate([
                    'photo' => 'required|image|mimes:jpeg,png|max:2048'
                ]);
            }
            if (!$this->student->signature) {
                $this->validate([
                    'sign' => 'required|image|mimes:jpeg,png|max:2048'
                ]);
            }


            $this->student->is_gov_exam_participated = $this->is_gov_exam_participated ?? 'no';
            $this->student->is_apply_career_without_barrier = $this->is_apply_career_without_barrier ?? 'no';
            $this->student->terms_conditions = $this->terms_conditions;
            $this->student->govt_exams_1 = $this->govt_exams_1;
            $this->student->govt_exams_2 = $this->govt_exams_2;
            $this->student->year = $this->year;
            $this->student->roll_no = $this->roll_no;
            $this->student->family_income = $this->family_income;
            $this->student->father_occupation = $this->father_occupation;
            $this->student->mother_occupation = $this->mother_occupation;
            $this->student->exam_one_year = $this->exam_one_year;
            $this->student->exam_one_result = $this->exam_one_result;
            $this->student->exam_two_year = $this->exam_two_year;
            $this->student->exam_two_result = $this->exam_two_result;
            $this->student->career_exams_1 = $this->career_exams_1;
            $this->student->career_one_result = $this->career_one_result;
            $this->student->career_exams_2 = $this->career_exams_2;
            $this->student->career_two_year = $this->career_two_year;
            $this->student->career_two_result = $this->career_two_result;

            if ($this->photo) {
                $this->student->photograph = $this->photo->store('student/' . $this->student->id, 'public');
            }
            if ($this->sign) {
                $this->student->signature = $this->sign->store('student/' . $this->student->id, 'public');
            }

            $this->student->form_step = 3;
            $this->student->save();

            return redirect()->route('students.formReview')->with(['success' => 'Student record created successfully.']);

        } catch (\Throwable $th) {
            // throw $th;
            logger('validation-error', [$th->getMessage()]);
            $this->js('toastr.error("' . $th->getMessage() . '")');
        }

        // try {
        //     $student = Student::find(Auth::guard('student')->id());

        //     $examRequired = $request->is_gov_exam_participated == 'yes' ? 'required' : 'nullable';
        //     $careerRequired = $request->is_apply_career_without_barrier == 'yes' ? 'required' : 'nullable';

        //     $photoReq = $student->photograph ? 'nullable' : 'required';
        //     $signReq = $student->signature ? 'nullable' : 'required';

        //     $validatedData = $request->validate(
        //         [
        //             'is_gov_exam_participated' => 'required',
        //             'is_apply_career_without_barrier' => 'required',
        //             'terms_conditions' => 'required',
        //             'govt_exams_1' => "$examRequired|string",
        //             'govt_exams_2' => 'nullable|string',
        //             'year' => "$careerRequired|string",
        //             'roll_no' => "nullable|string",
        //             'family_income' => 'nullable|string',
        //             'father_occupation' => 'nullable|string',
        //             'mother_occupation' => 'nullable|string',
        //             'photograph' => "$photoReq|file|mimes:jpeg,png,jpg",
        //             'signature' => "$signReq|file|mimes:jpeg,png,jpg",
        //             'exam_one_year' => "nullable|string",
        //             'exam_one_result' => "nullable|string",
        //             'exam_two_year' => 'nullable|string',
        //             'exam_two_result' => 'nullable|string',
        //             'career_exams_1' => 'nullable',
        //             'career_one_result' => 'nullable',
        //             'career_exams_2' => 'nullable',
        //             'career_two_year' => 'nullable',
        //             'career_two_result' => 'nullable',

        //         ],
        //         [
        //             'is_gov_exam_participated.required' => 'The government exam participation field is required.',
        //             'is_apply_career_without_barrier.required' => 'The career application without barrier field is required.',
        //             'govt_exams_1.required' => 'The first government exam field is required.',
        //             'year.required' => 'The year field is required.',
        //             'terms_conditions.required' => 'The terms_conditions field is required.',
        //             'roll_no.required' => 'The roll number field is required.',
        //             'photograph.required' => 'The photograph field is required.',
        //             'photograph.file' => 'The photograph must be a file.',
        //             'photograph.mimes' => 'The photograph must be a file of type: jpeg, png.',
        //             'signature.required' => 'The signature field is required.',
        //             'signature.file' => 'The signature must be a file.',
        //             'signature.mimes' => 'The signature must be a file of type: jpeg, png.',
        //             'signature.max' => 'The signature may not be greater than 2048 kilobytes.'
        //         ]
        //     );
        //     if ($request->hasFile('photograph')) {
        //         $validatedData['photograph'] = moveFile('upload/student', $request->photograph);
        //     }

        //     if ($request->hasFile('signature')) {
        //         $validatedData['signature'] = moveFile('upload/student', $request->signature);
        //     }
        //     $student->forceFill($validatedData);

        //     if ($student->form_step == 2)
        //         $student->form_step = 3;
        //     $student->save();
        // } catch (\Throwable $th) {
        //     return back()->withErrors('Failed to save');
        // }

        // // Redirect back or return a response
        // return redirect()->route('students.formReview')->with(['success' => 'Student record created successfully.']);
    }
}
