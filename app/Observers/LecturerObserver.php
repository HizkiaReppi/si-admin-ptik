<?php

namespace App\Observers;

use App\Models\Lecturer;
use Illuminate\Support\Facades\Cache;

class LecturerObserver
{
    /**
     * Handle the Lecturer "created" event.
     */
    public function created(Lecturer $lecturer): void
    {
        //
    }

    /**
     * Handle the Lecturer "updated" event.
     */
    public function updated(Lecturer $lecturer): void
    {
        //
    }

    /**
     * Handle the Lecturer "deleted" event.
     */
    public function deleted(Lecturer $lecturer): void
    {
        //
    }

    /**
     * Handle the Lecturer "restored" event.
     */
    public function restored(Lecturer $lecturer): void
    {
        //
    }

    /**
     * Handle the Lecturer "force deleted" event.
     */
    public function forceDeleted(Lecturer $lecturer): void
    {
        //
    }

    private function clearCache(Lecturer $lecturer): void
    {
        Cache::forget('lecturers');
        Cache::forget('lecturers_student');
        Cache::forget('lecturers_count');
        Cache::forget('lecturer_' . $lecturer->id);
    }
}