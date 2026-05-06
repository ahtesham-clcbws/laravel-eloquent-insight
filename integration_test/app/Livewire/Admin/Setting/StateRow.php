<?php

namespace App\Livewire\Admin\Setting;

use App\Models\State;
use Livewire\Component;

class StateRow extends Component
{
    public $index;
    public $state;
    public $isActive = false;

    public function mount(State $state, $index)
    {
        $this->index = $index;
        $this->state = $state;
        $this->isActive = strtolower($state->status) == 'active';
    }
    public function render()
    {
        return view('livewire.admin.setting.state-row');
    }
    public function changeStatus()
    {
        try {
            $state = State::withoutGlobalScope('active')->withCount('districts_all')->find($this->state->id);

            if (!$state) {
                $this->js("alert('State not found.')");
                return false;
            }

            $state->status = strtolower($state->status) == 'active' ? 'Inactive' : 'Active';
            $state->save();

            $this->state = $state;
            $this->isActive = strtolower($this->state->status) == 'active';

            $this->js("success('Status Updated successfully.')");
            return true;
        } catch (\Throwable $th) {
            $this->js("error('" . $th->getMessage() . "')");
            return false;
        }
    }
}
