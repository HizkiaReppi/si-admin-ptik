<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeadOfDepartmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(!auth()->check()) {
        return view('auth.login');
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
    Route::resource('/kategori', CategoryController::class)->names('dashboard.category');
    Route::resource('/pengajuan-surat', SubmissionController::class)->names('dashboard.submission');
    Route::get('/pengajuan-surat/create/{category}', [SubmissionController::class, 'create_student'])->name('dashboard.submission.student.create');
    Route::post('/pengajuan-surat/create/{category}', [SubmissionController::class, 'store_student'])->name('dashboard.submission.student.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
