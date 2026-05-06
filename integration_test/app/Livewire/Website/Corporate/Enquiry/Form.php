<?php

namespace App\Livewire\Website\Corporate\Enquiry;

use App\Models\TermsCondition;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class Form extends Component
{
    public $institudeTermsCondition = null;

    public $state_id = null;
    public $district_id = null;

    public function mount()
    {
        $this->institudeTermsCondition = TermsCondition::where([['status', 1], ['type', 'institute'], ['page_name', 'terms-and-condition']])->first();
    }

    public function render()
    {
        return view('livewire.website.corporate.enquiry.form');
    }
}
