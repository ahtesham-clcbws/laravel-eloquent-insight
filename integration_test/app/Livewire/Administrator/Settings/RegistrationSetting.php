<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\RegistrationSetting as ModelsRegistrationSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class RegistrationSetting extends Component
{
    public ModelsRegistrationSetting $setting;

    #[Validate('nullable|date')]
    public $start_date;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public $end_date;

    #[Validate('required|boolean')]
    public $is_enabled;

    public function mount()
    {
        $setting = ModelsRegistrationSetting::first();
        if (!$setting) {
            $setting = ModelsRegistrationSetting::create([
                'is_enabled' => true,
            ]);
        }
        $this->setting = $setting;
        $this->start_date = $setting->start_date?->format('Y-m-d\TH:i') ?? null;
        $this->end_date = $setting->end_date?->format('Y-m-d\TH:i') ?? null;
        $this->is_enabled = $setting->is_enabled;
    }

    public function render()
    {
        return view('livewire.administrator.settings.registration-setting');
    }

    public function save()
    {
        $this->validate();

        try {
            $this->setting->update([
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'is_enabled' => $this->is_enabled,
            ]);

            $this->js("success('Registration settings updated successfully.')");
        } catch (\Throwable $th) {
            $this->js("error('Server error, please try again later.')");
        }
    }
}
