<?php

namespace App\Observers;

use App\Models\ContactInfo;
use App\Models\User;
use App\Notifications\Admin\ContactEnquiryMail;

class ContactInfoObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(ContactInfo $contactInfo): void
    {
        $adminUser = User::where('email', 'sqscwb@gmail.com')->first();
        if ($adminUser)
            $adminUser->notify(new ContactEnquiryMail($contactInfo));
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(ContactInfo $contactInfo): void
    {
        //    $studApp = $contactInfo->studentCode->first();

        //    if($contactInfo->wasChanged('is_paid')){
        //     $adminUser = User::find('1');
        //    }
    }
}
