<?php

namespace App\Observers;

use App\Models\Submission;
use Illuminate\Support\Facades\Cache;

class SubmissionObserver
{
    /**
     * Handle the Submission "created" event.
     */
    public function created(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "updated" event.
     */
    public function updated(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "deleted" event.
     */
    public function deleted(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "restored" event.
     */
    public function restored(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    /**
     * Handle the Submission "force deleted" event.
     */
    public function forceDeleted(Submission $submission): void
    {
        $this->clearCache($submission);
    }

    private function clearCache(Submission $submission): void
    {
        Cache::forget('submissions');
        Cache::forget('admin_submissions_count');
        Cache::forget('admin_submissions');
        Cache::forget('admin_submissions');
        Cache::forget('admin_submission_' . $submission->id);
        Cache::forget('student_submissions_' . $submission->student_id);
        Cache::forget('submissions_student_category_' . $submission->student_id . '_' . $submission->category_id);
        Cache::forget('submission_detail_' . $submission->id);
    }
}
