<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
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
    } elseif (auth()->user()->role == 'admin' || auth()->user()->role == 'super-admin' || auth()->user()->role == 'HoD'){
        return redirect()->route('dashboard');
    } elseif (auth()->user()->role == 'student'){
        return redirect()->route('dashboard.submission.student.index');
    } else {
        abort(403);
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/administrator', [AdminController::class, 'index'])->middleware('password.confirm')->name('dashboard.administrator.index');
    Route::resource('/administrator', AdminController::class)->names('dashboard.administrator')->except('index');
    Route::resource('/dosen', LecturerController::class)->names('dashboard.lecturer');
    Route::resource('/mahasiswa', StudentController::class)->names('dashboard.student');

    Route::get('/pimpinan-jurusan/create/ketua-jurusan', [HeadOfDepartmentController::class, 'create'])->name('dashboard.pimpinan-jurusan.kajur.create');
    Route::get('/pimpinan-jurusan/create/sekretaris-jurusan', [HeadOfDepartmentController::class, 'create'])->name('dashboard.pimpinan-jurusan.sekjur.create');
    Route::resource('/pimpinan-jurusan', HeadOfDepartmentController::class)->names('dashboard.pimpinan-jurusan')->except('create');

    Route::resource('/pengumuman', AnnouncementController::class)->names('dashboard.announcements');
    Route::resource('/kategori', CategoryController::class)->names('dashboard.category');
    Route::resource('/pengajuan-surat', SubmissionController::class)->names('dashboard.submission')->except('edit');
    Route::get('/pengajuan-surat/view/{id}', [SubmissionController::class, 'view'])->name('dashboard.submission.view');
    Route::resource('/pengajuan', SubmissionStudentController::class)->names('dashboard.submission.student')->except('create', 'store', 'show', 'edit', 'update');
    Route::get('/pengajuan/{category}', [SubmissionStudentController::class, 'show'])->name('dashboard.submission.student.show');
    Route::get('/pengajuan/{category}/detail/{submission}', [SubmissionStudentController::class, 'detail'])->name('dashboard.submission.student.detail');
    Route::get('/pengajuan/{category}/edit/{submission}', [SubmissionStudentController::class, 'edit'])->name('dashboard.submission.student.edit');
    Route::patch('/pengajuan/{category}/update/{submission}', [SubmissionStudentController::class, 'update'])->name('dashboard.submission.student.update');
    Route::get('/pengajuan/create/{category}', [SubmissionStudentController::class, 'create'])->name('dashboard.submission.student.create');
    Route::post('/pengajuan/create/{category}', [SubmissionStudentController::class, 'store'])->name('dashboard.submission.student.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
