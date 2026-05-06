<?php

namespace App\Livewire\Admin\Setting;

use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('administrator.layouts.master')]
class Districts extends Component
{
    public $allDisctricts;

    public function mount()
    {
        $this->allDisctricts = District::withoutGlobalScope('active')->whereHas('state') // Ensure the state is active by using the global scope on State
            ->orderBy('state_name', 'asc')->orderBy('isActive', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.admin.setting.districts');
    }
}
