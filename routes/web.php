<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\GuidanceActivityController;
use App\Http\Controllers\GuidanceController;
use App\Http\Controllers\GuidedStudentController;
use App\Http\Controllers\HeadOfDepartmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\PrintExamApprovalController;
use App\Http\Controllers\PrintGuidanceHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestExamResultController;
use App\Http\Controllers\SetGuidanceController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(!auth()->check()) {
        return view('auth.login');
    } elseif (auth()->user()->role == 'student') {
        return redirect()->route('dashboard.bimbingan.index');
    } elseif (auth()->user()->role == 'lecturer') {
        return redirect()->route('dashboard.atur-jadwal-bimbingan.index');
    } elseif (auth()->user()->role == 'HoD') {
        return redirect()->route('dashboard.aktivitas-bimbingan.index');
    } elseif (auth()->user()->role == 'admin'){
        return redirect()->route('dashboard');
    } else {
        abort(403);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/dosen', LecturerController::class)->names('dashboard.lecturer');
    Route::resource('/mahasiswa', StudentController::class)->names('dashboard.student');
    Route::resource('/ketua-jurusan', HeadOfDepartmentController::class)->names('dashboard.kajur');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
