<?php

namespace App\Livewire\Admin\Setting;

use App\Livewire\Forms\Admin\Setting\ScholarshipDistrictForm;
use App\Models\District;
use App\Models\DistrictScholarshipLimit;
use App\Models\StudentCode;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class ScholarshipForms extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $selectedState = null;
    public $selectedDistrict = null;
    public $selectedCategory = null;
    public $searchString = null;
    public ScholarshipDistrictForm $form;
    // public $formsData;

    public function render()
    {
        $query = DistrictScholarshipLimit::query()
            ->with([
                'EducationType:id,name',
                'District:id,name',
            ])
            ->withCount('students')
            ->withCount('roll_numbers');

        // Apply filter conditions
        if (!empty(trim($this->selectedCategory . '')) && intval($this->selectedCategory) > 0) {
            $query->where('education_type_id', intval($this->selectedCategory));
        }
        if (!empty(trim($this->selectedState . '')) && intval($this->selectedState) > 0 && !$this->selectedDistrict) {
            $districtIds = District::where('state_id', $this->selectedState)->pluck('id')->toArray();
            $query->whereIn('district_id', $districtIds);
        }
        if (!empty(trim($this->selectedDistrict . '')) && intval($this->selectedDistrict) > 0) {
            $query->where('district_id', intval($this->selectedDistrict));
        }
        // Apply search conditions
        if (!empty(trim($this->searchString . ''))) {
            $searchTerm = '%' . $this->searchString . '%';

            $query
                ->whereHas('District', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('EducationType', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm);
                });
        }
        // get final data
        $formsData = $query->orderBy('district_id', 'asc')->orderBy('education_type_id', 'asc')->paginate(10);

        return view('livewire.admin.setting.scholarship-forms', [
            'formsData' => $formsData,
            'roll_numbers' => StudentCode::whereNotNull('roll_no')->count()
        ]);
    }

    public function addForms()
    {
        $this->form->store();
    }

    public function editForms($id)
    {
        $data = DistrictScholarshipLimit::find($id);
        $this->form->setData($data);
    }

    public function deleteForm($id)
    {
        return DistrictScholarshipLimit::destroy($id);
    }
}
