<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Submission;
use Illuminate\Support\Facades\Cache;

class Navbar extends Component
{
    public $submissions;
    public int $submissionsCount;

    public function __construct()
    {
        $user = auth()->user();

        if ($user->role == 'student') {
            $this->submissions = Cache::remember('student_submissions_' . $user->student->id, now()->addMinutes(60), function () use ($user) {
                return Submission::with(['category', 'student'])
                    ->where('student_id', $user->student->id)
                    ->where('status', '!=', 'submitted')
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();
            });
        } else {
            $this->submissions = Cache::remember('admin_submissions', now()->addMinutes(5), function () {
                return Submission::with(['category', 'student'])
                    ->where('status', 'submitted')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            });
        }

        $this->submissionsCount = $this->submissions->count();
    }

    public function render()
    {
        return view('components.navbar', [
            'submissions' => $this->submissions,
            'submissionsCount' => $this->submissionsCount
        ]);
    }
}
