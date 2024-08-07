<?php

namespace App\Observers;

use App\Models\HeadOfDepartment;
use Illuminate\Support\Facades\Cache;

class HeadOfDepartmentObserver
{
    /**
     * Handle the HeadOfDepartment "created" event.
     */
    public function created(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('kajur');
    }

    /**
     * Handle the HeadOfDepartment "updated" event.
     */
    public function updated(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('kajur');
    }

    /**
     * Handle the HeadOfDepartment "deleted" event.
     */
    public function deleted(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('kajur');
    }

    /**
     * Handle the HeadOfDepartment "restored" event.
     */
    public function restored(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('kajur');
    }

    /**
     * Handle the HeadOfDepartment "force deleted" event.
     */
    public function forceDeleted(HeadOfDepartment $headOfDepartment): void
    {
        Cache::forget('kajur');
    }
}
