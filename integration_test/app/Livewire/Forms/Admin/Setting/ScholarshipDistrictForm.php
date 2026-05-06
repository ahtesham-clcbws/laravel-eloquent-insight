<?php

namespace App\Livewire\Forms\Admin\Setting;

use App\Models\DistrictScholarshipLimit;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ScholarshipDistrictForm extends Form
{
    public ?DistrictScholarshipLimit $formsData;

    #[Validate('required')]
    public $selectedState = null;

    #[Validate('required')]
    public $education_type_id = null;
    #[Validate('required')]
    public $district_id = null;
    #[Validate('required|integer')]
    public $max_registration_limit = null;


    public function store()
    {
        $this->validate();
        if (isset($this->formsData, $this->formsData->id) && $this->formsData) {
            $this->formsData->update($this->only(['district_id', 'education_type_id', 'max_registration_limit']));
        } else {
            DistrictScholarshipLimit::create($this->only(['district_id', 'education_type_id', 'max_registration_limit']));
        }
        $this->reset();
    }

    public function setData(DistrictScholarshipLimit $data)
    {
        $this->formsData = $data;

        $this->education_type_id = $data->education_type_id;

        $this->selectedState = $data->District->state_id;

        $this->district_id = $data->district_id;

        $this->max_registration_limit = $data->max_registration_limit;
    }
}
