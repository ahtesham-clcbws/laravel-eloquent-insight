<?php

namespace App\Livewire\Student;

use App\Models\Student;
use App\Models\TestimonialsModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('student.layouts.master')]
class AddTestimonial extends Component
{
    use WithFileUploads;

    public Student $student;

    public $name;
    public $type_id;
    public $type = 'student';
    #[Validate('nullable')]
    public $message;
    #[Validate('required', message: 'The screenshot is required.')]
    #[Validate('image', message: 'The screenshot must be an image file.')]
    #[Validate('mimes:jpeg,png', message: 'The screenshot must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'Screenshot size must not exceed 2MB.')]
    public $image;

    public function mount()
    {
        $studentUser = Auth::guard('student')->user();
        $this->student = Student::find($studentUser->id);
        $this->name = $this->student->name;
        $this->type_id = $this->student->id;
    }

    public function render()
    {
        return view('livewire.student.add-testimonial');
    }

    public function save()
    {
        $this->validate();

        try {
            $testimonial = TestimonialsModel::where('type', 'student')->where('id', $this->type_id)->first() ?? new TestimonialsModel();
            $testimonial->name = $this->name;
            $testimonial->type_id = $this->type_id;
            $testimonial->type = $this->type;

            $testimonial->message = $this->message ?? 'Student review screenshot';

            if ($this->image) {
                $testimonial->image = $this->image->store('testimonial/students', 'public');
            }
            $testimonial->save();
            $this->js('success("Testimonial added successfully")');
        } catch (\Throwable $th) {
            //throw $th;
            $this->js('error("' . $th->getMessage() . '")');
        }

        // if ($request->isMethod('POST') && is_null($testimonial)) {
        //     $request->validate([
        //         'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif',
        //         'testimonials_msg' => 'required',
        //     ]);

        //     $message = $request->testimonials_msg;

        //     $testimonials = $request->id ? TestimonialsModel::where('type', 'student')->where('id', $request->id)->first() : new TestimonialsModel();
        //     $testimonials->name = $student->name;
        //     $testimonials->type_id = $student->id;
        //     $testimonials->type = 'student';

        //     $formattedMessage = $message . '<br><div class="student-testimonial text-right"><br> <b>Student Name: ' . ucfirst($student->name) . '</b><br><b> City: ' . $student->district?->name . '</b></div>';
        //     $testimonials->message = $formattedMessage;

        //     if ($request->hasFile('profile_image')) {
        //         $imagePath = moveFile('home', $request->file('profile_image'));
        //         $testimonials->image = $imagePath;
        //     }

        //     $testimonials->save();

        //     return redirect()->back()->with('success', 'Testimonial added successfully!');
        // }
    }
}
