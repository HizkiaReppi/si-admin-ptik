<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $totalStudents = Student::count();
        $totalLecturers = Lecturer::count();

        return view('dashboard.index', compact('totalStudents',  'totalLecturers'));
    }
}
