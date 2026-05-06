<?php

namespace App\Observers;

use App\Models\StudentCode;

class StudentCodeObserver
{
    /**
     * Handle the StudentCodeObserver "created" event.
     */
    public function created(StudentCode $studentCode): void {}

    /**
     * Handle the StudentCodeObserver "updated" event.
     */
    public function updated(StudentCode $studentCode): void
    {
    }

    /**
     * Handle the StudentCodeObserver "deleted" event.
     */
    public function deleted(StudentCode $studentCode): void
    {
        // ...
    }

    /**
     * Handle the StudentCodeObserver "restored" event.
     */
    public function restored(StudentCode $studentCode): void
    {
        // ...
    }

    /**
     * Handle the StudentCodeObserver "forceDeleted" event.
     */
    public function forceDeleted(StudentCode $studentCode): void
    {
        // ...
    }
}
