<?php

namespace App\Providers;

use App\Events\paymentDoneEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LogVerifiedUser;
use App\Listeners\paymentDoneListener;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\Student;
use App\Models\StudentCode;
use App\Observers\ContactInfoObserver;
use App\Observers\CorporateObserver;
use App\Observers\StudentCodeObserver;
use App\Observers\StudentObserver;
use Illuminate\Auth\Events\Verified;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
         paymentDoneEvent::class => [
             paymentDoneListener::class,
         ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Student::observe(StudentObserver::class);
        Corporate::observe(CorporateObserver::class);
        ContactInfo::observe(ContactInfoObserver::class);
        StudentCode::observe(StudentCodeObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
