<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubmissionStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $student = auth()->user()->student;

        $submissions = Submission::where('student_id', $student->id)->with(['category'])->get();
        return view('dashboard.submission-student.index', compact('submissions', 'student'));
    }

    /**
     * Show the form for student for creating a new resource.
     */
    public function create(Category $category): View
    {
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
                $filePath = $file->store('requirements');
                $submission->files()->create([
                    'requirement_id' => $category->requirements[$index]->id,
                    'file_path' => $filePath,
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

        $submissions = Submission::where('student_id', $student->id)->where('category_id', $category->id)->get();
        return view('dashboard.submission-student.show', compact('submissions', 'student', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $pengajuan_surat): RedirectResponse
    {
        $validatedData = $request->validate([
            'status' => ['required', 'in:submitted,pending,proses_kajur,proses_dekan,done,rejected,canceled,expired'],
            'note' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $pengajuan_surat->status = $validatedData['status'];
            $pengajuan_surat->note = $validatedData['note'] ?? $pengajuan_surat->note;
            $pengajuan_surat->save();

            DB::commit();
            return redirect()->route('dashboard.submission.index')->with('toast_success', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('toast_error', 'Failed to update submission. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $pengajuan_surat): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $pengajuan_surat->delete();

            foreach ($pengajuan_surat->files as $file) {
                if($file->file_path) {
                    Storage::delete($file->file_path);
                    $file->delete();
                }
            }

            if($pengajuan_surat->file_result) {
                Storage::delete($pengajuan_surat->file_result);
            }

            DB::commit();
            return redirect()->route('dashboard.submission.index')->with('toast_success', 'Pengajuan surat berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus pengajuan surat. Silakan coba lagi.');
        }
    }
}
