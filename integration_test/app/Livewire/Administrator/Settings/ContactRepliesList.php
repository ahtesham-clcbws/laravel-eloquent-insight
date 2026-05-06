<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\ContactInfo;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class ContactRepliesList extends Component
{
    public ContactInfo $contact;

    public function mount($id)
    {
        $this->contact = ContactInfo::find($id);
    }
    public function render()
    {
        return view('livewire.administrator.settings.contact-replies-list');
    }
}
