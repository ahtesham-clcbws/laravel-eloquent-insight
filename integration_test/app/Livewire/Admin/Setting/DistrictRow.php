<?php

namespace App\Livewire\Admin\Setting;

use App\Models\District;
use Livewire\Component;

class DistrictRow extends Component
{
    public $index;
    public $district;
    public $isActive;


    public function mount(District $district, $index)
    {
        $this->index = $index;
        $this->district = $district;
        $this->isActive = $district->isActive;
    }
    public function render()
    {
        return view('livewire.admin.setting.district-row');
    }
    public function changeStatus()
    {
        try {
            $district = District::withoutGlobalScope('active')->find($this->district->id);
            if (!$district) {
                $this->js("alert('District not found.')");
                return false;
            }
            $district->isActive = !$district->isActive;
            $district->save();

            $this->district = $district;
            $this->isActive = $this->district->isActive;

            $this->js("success('Status Updated successfully.')");
            return true;
        } catch (\Throwable $th) {
            $this->js("error('" . $th->getMessage() . "')");
            return false;
        }
    }
    
}
