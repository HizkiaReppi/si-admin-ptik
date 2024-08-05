<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SubmissionStudentController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('student')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $student = auth()->user()->student;

        $submissions = Submission::where('student_id', $student->id)
            ->with(['category'])
            ->get();
        return view('dashboard.submission-student.index', compact('submissions'));
    }

    /**
     * Show the form for student for creating a new resource.
     */
    public function create(Category $category): View
    {
        $category->load('requirements');
        return view('dashboard.submission-student.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage from student.
     */
    public function store(Request $request, Category $category): RedirectResponse
    {
        $validatedData = $request->validate([
            'requirements' => ['required', 'array'],
            'requirements.*' => ['required', 'file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $submission = new Submission();
            $submission->category_id = $category->id;
            $submission->student_id = auth()->user()->student->id;
            $submission->save();

            foreach ($validatedData['requirements'] as $index => $file) {
                $requirementName = $category->requirements[$index]->name;
                $fileName = time() . '_' . str_replace(' ', '_', $category->name) . '_' . str_replace(' ', '_', $requirementName) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public/file/submissions', $fileName);

                $submission->files()->create([
                    'file_path' => Storage::url($filePath),
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.submission.student.index')->with('toast_success', 'Pengajuan surat berhasil diajukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal mengajukan surat. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $student = auth()->user()->student;

        $submissions = Submission::where('student_id', $student->id)
            ->where('category_id', $category->id)
            ->get();
        return view('dashboard.submission-student.show', compact('submissions', 'category'));
    }

    /**
     * Display the specified resource.
     */
    public function detail(Category $category, Submission $submission): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $submission = Submission::with(['files', 'student'])->find($submission->id);

        if ($submission->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        return view('dashboard.submission-student.detail', compact('submission', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, Submission $submission): View
    {
        if ($submission->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $submission->load('files');
        $category->load('requirements');
        return view('dashboard.submission-student.edit', compact('submission', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category, Submission $submission): RedirectResponse
    {
        if ($submission->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $validatedData = $request->validate([
            'requirements' => ['array'],
            'requirements.*' => ['file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            // Menghapus file lama jika ada file baru yang diupload
            foreach ($validatedData['requirements'] as $index => $file) {
                $requirement = $category->requirements[$index];
                $fileNamePattern = str_replace(' ', '_', $category->name) . '_' . str_replace(' ', '_', $requirement->name);

                $existingFile = $submission->files->first(function ($file) use ($fileNamePattern) {
                    return strpos($file->file_path, $fileNamePattern) !== false;
                });

                if ($existingFile) {
                    $fileStoragePath = str_replace('/storage', 'public', $existingFile->file_path);
                    Storage::delete($fileStoragePath);

                    $existingFile->delete();
                }

                // Mengupload file baru
                $fileName = time() . '_' . $fileNamePattern . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public/file/submissions', $fileName);

                $submission->files()->create([
                    'file_path' => Storage::url($filePath),
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.submission.student.index')->with('toast_success', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal memperbarui pengajuan surat. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $pengajuan): RedirectResponse
    {
        if ($pengajuan->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            foreach ($pengajuan->files as $file) {
                if ($file->file_path) {
                    $fileStoragePath = str_replace('/storage', 'public', $file->file_path);
                    Storage::delete($fileStoragePath);
                    $file->delete();
                }
            }

            if ($pengajuan->file_result) {
                $fileStoragePath = str_replace('/storage', 'public', $pengajuan->file_result);
                Storage::delete($fileStoragePath);
            }

            $pengajuan->delete();

            DB::commit();
            return redirect()->route('dashboard.submission.student.index')->with('toast_success', 'Pengajuan surat berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus pengajuan surat. Silakan coba lagi.');
        }
    }
}
