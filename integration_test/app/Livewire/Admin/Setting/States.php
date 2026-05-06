<?php

namespace App\Livewire\Admin\Setting;

use App\Models\State;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('administrator.layouts.master')]
class States extends Component
{
    public $allStates;

    public function mount()
    {
        $this->allStates = State::withoutGlobalScope('active')->select('id', 'name', 'status')->withCount('districts_all')->orderBy('name', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.admin.setting.states');
    }
}
