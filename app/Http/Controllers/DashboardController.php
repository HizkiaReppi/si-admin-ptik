<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $totalStudents = Cache::remember('students_count', now()->addMinutes(60), function () {
            return Student::count();
        });
        $totalLecturers = Cache::remember('lecturers_count', now()->addMinutes(60), function () {
            return Lecturer::count();
        });
        $totalSubmission = Cache::remember('admin_submissions_count', now()->addMinutes(10), function () {
            return Submission::count();
        });
        $totalSubmissionDone = Cache::remember('admin_submissions_done_count', now()->addMinutes(10), function () {
            return Submission::where('status', 'done')->count();
        });

        return view('dashboard.index', compact('totalStudents',  'totalLecturers', 'totalSubmission', 'totalSubmissionDone'));
    }
}
