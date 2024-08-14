<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionAdminUpdateRequest;
use App\Mail\SubmissionUpdated;
use App\Models\Category;
use App\Models\Student;
use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        if ($request->ajax()) {
            $model = Submission::with(['category', 'student', 'student.user'])->orderBy('created_at', 'desc');

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('nim', function ($row) {
                    return $row->student->formattedNIM;
                })
                ->addColumn('name', function ($row) {
                    return $row->student->fullname;
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->diffForHumans();
                })
                ->addColumn('status', function ($row) {
                    $content = '<span class="badge text-bg-' . $row->parseSubmissionBadgeClassNameStatus . '">
                                ' . $row->parseSubmissionStatus . '
                            </span>';
                    return $content;
                })
                ->addColumn('action', function ($row) {
                    $statuses = ['rejected', 'canceled', 'expired'];
                    $isStatusInArray = in_array($row->status, $statuses);
                    $isStatusDoneAndOld = $row->status == 'done' && $row->updated_at->lt(now()->subDays(7));

                    $btn = null;
                    if ($isStatusInArray || $isStatusDoneAndOld) {
                        $btn = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="' . route('dashboard.submission.show', $row->id) . '">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="' . route('dashboard.submission.destroy', $row->id) . '"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>';
                    } else {
                        $btn = '<a class="dropdown-item"
                                    href="' . route('dashboard.submission.show', $row->id) . '">
                                    <i class="bx bxs-user-detail me-1"></i> Detail
                                </a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('dashboard.submissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $students = Cache::remember('students_submission', now()->addMinutes(60), function () {
            return Student::with('user')->get();
        });

        $categories = Cache::remember('categories_submission', now()->addMinutes(60), function () {
            return Category::with('requirements')->get();
        });
        return view('dashboard.submissions.create', compact('students', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

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
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add submission. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $pengajuan_surat): View
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $submission = Cache::remember('admin_submission_' . $pengajuan_surat->id, now()->addMinutes(10), function () use ($pengajuan_surat) {
            return Submission::with(['files', 'category', 'student', 'student.user'])->find($pengajuan_surat->id);
        });
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

    public function view($id): View
    {
        $type = request()->query('type');

        if ($type == 'file-result') {
            $submission = Submission::with(['files', 'category', 'student', 'student.user'])->where('id', $id)->first();
            $filePath = str_replace('/storage', 'public', $submission->file_result);
            $filePath = storage_path("app/" . $filePath);
            $mimeType = mime_content_type($filePath);

            $submission_file = new SubmissionFile();
            $submission_file->file_path = $submission->file_result;
        } else {
            $submission_file = SubmissionFile::where('id', $id)->first();
            $submission = Submission::with(['category', 'student', 'student.user'])->where('id', $submission_file->submission_id)->first();

            $filePath = str_replace('/storage', 'public', $submission_file->file_path);
            $filePath = storage_path("app/" . $filePath);
            $mimeType = mime_content_type($filePath);
        }

        return view('dashboard.submissions.view', compact('submission_file', 'submission', 'mimeType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubmissionAdminUpdateRequest $request, Submission $pengajuan_surat): RedirectResponse
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $pengajuan_surat->status = $validatedData['status'];
            $pengajuan_surat->note = $validatedData['note'] ?? $pengajuan_surat->note;

            if ($request->hasFile('file_result')) {
                if ($pengajuan_surat->file_result) {
                    $fileStoragePath = str_replace('/storage', 'public', $pengajuan_surat->file_result);
                    Storage::delete($fileStoragePath);
                }

                $fileName = time() . '_' . str_replace(' ', '_', $pengajuan_surat->category->name) . '_' . str_replace(' ', '_', $pengajuan_surat->student->fullname) . '.' . $validatedData['file_result']->getClientOriginalExtension();
                $filePath = $validatedData['file_result']->storeAs('public/file/submissions-result', $fileName);

                $pengajuan_surat->file_result = Storage::url($filePath);
            }

            $pengajuan_surat->save();

            DB::commit();

            Mail::to($pengajuan_surat->student->user->email)->send(new SubmissionUpdated($pengajuan_surat));

            return redirect()->route('dashboard.submission.index')->with('toast_success', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to update submission. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $pengajuan_surat): RedirectResponse
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        $isStatusValid = in_array($pengajuan_surat->status, ['rejected', 'canceled', 'expired']);
        $isStatusDoneAndOld = $pengajuan_surat->status == 'done' && $pengajuan_surat->updated_at->lt(now()->subDays(7));

        if (!$isStatusValid && !$isStatusDoneAndOld) {
            return redirect()->back()->with('toast_error', 'Gagal menghapus pengajuan surat. Status pengajuan surat tidak sesuai.');
        }

        DB::beginTransaction();

        try {
            $pengajuan_surat->delete();

            foreach ($pengajuan_surat->files as $file) {
                if ($file->file_path) {
                    $fileStoragePath = str_replace('/storage', 'public', $file->file_path);
                    Storage::delete($fileStoragePath);
                    $file->delete();
                }
            }

            if ($pengajuan_surat->file_result) {
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
