<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdministratorObserver
{
    /**
     * Handle the Administrator "created" event.
     */
    public function created(User $administrator): void
    {
        Cache::forget('administrators');
    }

    /**
     * Handle the Administrator "updated" event.
     */
    public function updated(User $administrator): void
    {
        Cache::forget('administrators');
    }

    /**
     * Handle the Administrator "deleted" event.
     */
    public function deleted(User $administrator): void
    {
        Cache::forget('administrators');
    }

    /**
     * Handle the Administrator "restored" event.
     */
    public function restored(User $administrator): void
    {
        Cache::forget('administrators');
    }

    /**
     * Handle the Administrator "force deleted" event.
     */
    public function forceDeleted(User $administrator): void
    {
        Cache::forget('administrators');
    }
}
