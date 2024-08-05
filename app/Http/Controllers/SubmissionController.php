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

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $submissions = Submission::with(['category', 'student'])->get();
        return view('dashboard.submissions.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = Student::with('user')->get();
        $categories = Category::with('requirements')->get();
        return view('dashboard.submissions.create', compact('students', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'student_id' => ['required', 'exists:students,id'],
            'requirements' => ['required', 'array'],
            'requirements.*' => ['required', 'file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $submission = new Submission();
            $submission->category_id = $validatedData['category_id'];
            $submission->student_id = $validatedData['student_id'];
            $submission->save();

            foreach ($validatedData['requirements'] as $index => $file) {
                $category = Category::findOrFail($validatedData['category_id']);
                $requirementName = $category->requirements[$index]->name;
                $fileName = time() . '_' . str_replace(' ', '_', $category->name) . '_' . str_replace(' ', '_', $requirementName) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public/file/submissions', $fileName);

                $submission->files()->create([
                    'file_path' => Storage::url($filePath),
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.submission.index')->with('toast_success', 'Pengajuan surat berhasil diajukan');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add submission. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $pengajuan_surat): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $submission = Submission::with(['category', 'student'])->find($pengajuan_surat->id);
        $statuses = [
            'submitted' => 'Diajukan',
            'pending' => 'Wajib Menghadap',
            'proses_kajur' => 'Proses Kajur',
            'proses_dekan' => 'Proses Dekan',
            'done' => 'Selesai',
            'rejected' => 'Ditolak',
            'canceled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
        ];
        return view('dashboard.submissions.show', compact('submission', 'statuses'));
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
