<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submissions = Submission::with(['category', 'student'])->get();
        return view('dashboard.submissions.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $categories = Category::with('requirements')->get();
        return view('dashboard.submissions.create', compact('students', 'categories'));
    }

    /**
     * Show the form for student for creating a new resource.
     */
    public function create_student(Category $category)
    {
        return view('frontend.submissions.create', compact('category'));
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
                $filePath = $file->storeAs('file/submissions', $fileName);

                $submission->files()->create([
                    'file_path' => $filePath,
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
     * Store a newly created resource in storage from student.
     */
    public function store_student(Request $request, Category $category): RedirectResponse
    {
        $validatedData = $request->validate([
            'requirements' => ['required', 'array'],
            'requirements.*' => ['required', 'file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $submission = new Submission();
            $submission->category_id = $category->id;
            $submission->student_id = auth()->student->id();
            $submission->save();

            foreach ($validatedData['requirements'] as $index => $file) {
                $filePath = $file->store('requirements');
                $submission->files()->create([
                    'requirement_id' => $category->requirements[$index]->id,
                    'file_path' => $filePath,
                ]);
            }

            DB::commit();
            return redirect()->route('frontend.submissions.index')->with('toast_success', 'Pengajuan surat berhasil diajukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal mengajukan surat. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $pengajuan_surat)
    {
        $submission = Submission::with(['category', 'student'])->find($pengajuan_surat->id);
        return view('dashboard.submissions.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Submission $pengajuan_surat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $pengajuan_surat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $pengajuan_surat)
    {
        //
    }
}
