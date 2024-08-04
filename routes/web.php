<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeadOfDepartmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionStudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(!auth()->check()) {
        return view('auth.login');
    } elseif (auth()->user()->role == 'admin'){
        return redirect()->route('dashboard');
    } elseif (auth()->user()->role == 'student'){
        return redirect()->route('dashboard.submission.student.index');
    } else {
        abort(403);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/dosen', LecturerController::class)->names('dashboard.lecturer');
    Route::resource('/mahasiswa', StudentController::class)->names('dashboard.student');
    Route::resource('/ketua-jurusan', HeadOfDepartmentController::class)->names('dashboard.kajur');
    Route::resource('/kategori', CategoryController::class)->names('dashboard.category');
    Route::resource('/pengajuan-surat', SubmissionController::class)->names('dashboard.submission')->except('edit');
    Route::resource('/pengajuan', SubmissionStudentController::class)->names('dashboard.submission.student')->except('create', 'store', 'show');
    Route::get('/pengajuan/{category}', [SubmissionStudentController::class, 'show'])->name('dashboard.submission.student.show');
    Route::get('/pengajuan/create/{category}', [SubmissionStudentController::class, 'create'])->name('dashboard.submission.student.create');
    Route::post('/pengajuan/create/{category}', [SubmissionStudentController::class, 'store'])->name('dashboard.submission.student.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
