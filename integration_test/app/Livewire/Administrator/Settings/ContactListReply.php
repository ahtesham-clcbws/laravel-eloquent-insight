<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\ContactInfo;
use App\Notifications\ContactInfoReplyMail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class ContactListReply extends Component
{
    public ContactInfo $contact;

    #[Validate('required')]
    public $message = '';

    public function mount($id)
    {
        $this->contact = ContactInfo::find($id);
    }

    public function render()
    {
        return view('livewire.administrator.settings.contact-list-reply');
    }

    public function save()
    {
        // $this->js('console.log("message: '.$this->message.'")');
        if ($this->validate()) {
            try {
                $this->contact->replyMails()->create([
                    'message' => $this->message
                ]);
                $this->contact->notify(new ContactInfoReplyMail($this->contact, $this->message));
                $this->contact->update(['status' => true]);
                $this->js('success("Reply message sent successfully.")');
                return $this->redirect('/administrator/contact-replies/'.$this->contact->id);
            } catch (\Throwable $th) {
                // throw $th;
                // $this->js('console.log(' . $th->getMessage() . ')');
                $this->js('error("Failed to send reply message. ' . $th->getMessage() . '")');
            }
        }
    }
}
