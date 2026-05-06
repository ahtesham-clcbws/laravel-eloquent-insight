<?php
 
namespace App\Observers;
 
use App\Models\Corporate;
use App\Models\User;

class CorporateObserver
{
    /**
     * Handle the Corporate "created" event.
     */
    public function created(Corporate $corporate): void
    {
       
    }
 
    /**
     * Handle the Corporate "updated" event.
     */
    public function updated(Corporate $corporate): void
    {
        // ...
    }
 
    /**
     * Handle the Corporate "deleted" event.
     */
    public function deleted(Corporate $corporate): void
    {
        // ...
    }
 
    /**
     * Handle the Corporate "restored" event.
     */
    public function restored(Corporate $corporate): void
    {
        // ...
    }
 
    /**
     * Handle the Corporate "forceDeleted" event.
     */
    public function forceDeleted(Corporate $corporate): void
    {
        // ...
    }
}