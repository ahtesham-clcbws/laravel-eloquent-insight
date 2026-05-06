<?php

namespace App\Livewire\Corporate;

use App\Models\Corporate;
use App\Models\TestimonialsModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('corporate.layouts.master')]
class AddTestimonial extends Component
{
    use WithFileUploads;

    public Corporate $corporate;

    public $name;
    public $institute_name;
    public $type_id;
    public $type = 'corporate';
    #[Validate('required', message: 'Review is required.')]
    public $message;
    #[Validate('nullable')]
    #[Validate('image', message: 'The screenshot must be an image file.')]
    #[Validate('mimes:jpeg,png', message: 'The screenshot must be a JPEG or PNG image.')]
    #[Validate('max:2048', message: 'Screenshot size must not exceed 2MB.')]
    public $image;

    public function mount()
    {
        $user = Auth::guard('corporate')->user();
        $this->corporate = Corporate::find($user->id);
        $this->name = $this->corporate->name;
        $this->institute_name = $this->corporate->institute_name;
        $this->type_id = $this->corporate->id;
    }

    public function render()
    {
        return view('livewire.corporate.add-testimonial');
    }
    public function save()
    {
        $this->validate();

        try {
            $testimonial = TestimonialsModel::where('type', 'corporate')->where('id', $this->type_id)->first() ?? new TestimonialsModel();
            $testimonial->name = $this->name;
            $testimonial->institute_name = $this->institute_name;
            $testimonial->type_id = $this->type_id;
            $testimonial->type = $this->type;

            $testimonial->message = $this->message;

            if ($this->image) {
                $testimonial->image = $this->image->store('testimonial/corporates', 'public');
            }
            $testimonial->save();
            $this->js('success("Testimonial added successfully")');
        } catch (\Throwable $th) {
            //throw $th;
            $this->js('error("' . $th->getMessage() . '")');
        }
    }
}
