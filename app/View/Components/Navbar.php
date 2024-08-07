<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Submission;

class Navbar extends Component
{
    public $submissions;
    public int $submissionsCount;

    public function __construct()
    {
        $user = auth()->user();

        if ($user->role == 'student') {
            $this->submissions = Submission::with(['category', 'student'])
                ->where('student_id', $user->student->id)
                ->where('status', '!=', 'submitted')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $this->submissions = Submission::with(['category', 'student'])
                ->where('status', 'submitted')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
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
