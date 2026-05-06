<?php

namespace App\Livewire;

use Livewire\Component;

class MultiSelectDropdown extends Component
{
    // This enables { wire:model="someVar" } support
    public $model;
    public $options = [];
    public $id = 'dropdown';

    public function mount($options = [], $model = [], $id = 'dropdown')
    {
        $this->options = $options;
        $this->model = $model ?? [];
        $this->id = $id;
    }

    // Updates the wire:model in the parent automatically
    public function updatedModel($value)
    {
        // Optional: Emit events if you need more event-driven behavior
        $this->emitUp('multiSelectChanged', $value);
    }

    public function render()
    {
        return view('livewire.multi-select-dropdown');
    }
}
