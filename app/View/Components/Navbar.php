<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Submission;

class Navbar extends Component
{
    public $submissions;

    public function __construct()
    {
        if (auth()->user()->role == 'student') {
            $this->submissions = Submission::where('student_id', auth()->user()->student->id)->where('status', '!=', 'submitted')->orderBy('created_at', 'desc')->with(['category', 'student'])->take(5)->get();
        } else {
            $this->submissions = Submission::where('status', 'submitted')->orderBy('created_at', 'desc')->with(['category', 'student'])->take(5)->get();
        }
    }

    public function render()
    {
        return view('components.navbar', [
            'submissions' => $this->submissions,
            "submissionsCount" => $this->submissions->count()
        ]);
    }
}
