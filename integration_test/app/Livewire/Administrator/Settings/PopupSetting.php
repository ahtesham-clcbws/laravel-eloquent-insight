<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\PopupSettings;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('administrator.layouts.master')]
class PopupSetting extends Component
{
    use WithFileUploads;

    public PopupSettings $popup;

    public $image = '/700x500.png';
    // #[Validate('image', message: 'Please provide an image.')]
    // #[Validate('required', message: 'Image is required.')]
    #[Validate('max:512', message: 'The photo field must not be greater than 512KB')]
    public $photo;
    #[Validate('required', message: 'Status is required.')]
    public int $status;

    public function mount()
    {
        $popup = PopupSettings::firstOrCreate();
        $this->popup = $popup;
        $this->status = $popup->status;
        if ($popup->image) {
            $this->image = '/storage/' . $popup->image;
        }
    }

    public function render()
    {
        return view('livewire.administrator.settings.popup-setting');
    }

    public function save()
    {
        try {
            $this->validate();
            $data = [
                'status' => $this->status
            ];

            if ($this->photo) {
                $path = $this->photo->storePublicly('pupup-images', 'public');
                $data['image'] = $path;
            }

            $this->popup->update($data);
            $this->js("success('Popup settings updated successfully.')");
        } catch (\Throwable $th) {
            $this->js('console.log(' . $th->getMessage() . ')');
            $this->js("error('Server error, please try again later.')");
        }
    }
}
