<?php

namespace App\Http\Controllers;

use App\Http\Requests\LecturerStoreRequest;
use App\Http\Requests\LecturerUpdateRequest;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LecturerController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        if ($request->ajax()) {
            $model = Lecturer::with('user');

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    return $row->fullname;
                })
                ->addColumn('nidn', function ($row) {
                    return $row->nidn;
                })
                ->addColumn('nip', function ($row) {
                    return $row->formattedNIP;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.lecturer.show', $row->id) .
                        '">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.lecturer.edit', $row->id) .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.lecturer.destroy', $row->id) .
                        '"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>';
                    return $btn;
                })
                ->make(true);
        }

        return view('dashboard.lecturer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.lecturer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LecturerStoreRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->email = $validatedData['email'];
            $user->username = $validatedData['nidn'];
            $user->password = Hash::make($validatedData['nidn']);
            $user->role = 'lecturer';

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = time() . '_dosen_' . $user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $user->photo = $fileName;
            }

            $user->save();

            $lecturer = new Lecturer();
            $lecturer->user_id = $user->id;
            $lecturer->nip = $validatedData['nip'];
            $lecturer->nidn = $validatedData['nidn'];
            $lecturer->front_degree = $validatedData['gelar-depan'];
            $lecturer->back_degree = $validatedData['gelar-belakang'];
            $lecturer->position = $validatedData['jabatan'];
            $lecturer->rank = $validatedData['pangkat'];
            $lecturer->type = $validatedData['golongan'];
            $lecturer->phone_number = $validatedData['no-hp'];
            $lecturer->save();

            DB::commit();

            return redirect()->route('dashboard.lecturer.index')->with('toast_success', 'Lecturer added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to add Lecturer. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecturer $dosen): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $students = Cache::remember('lecturer_' . $dosen->id, now()->addMinutes(60), function () use ($dosen) {
            return Student::where('lecturer_id_1', $dosen->id)
                ->orWhere('lecturer_id_2', $dosen->id)
                ->with('user')
                ->get();
        });

        return view('dashboard.lecturer.show', compact('dosen', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $dosen): View
    {
        return view('dashboard.lecturer.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LecturerUpdateRequest $request, Lecturer $dosen)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($validatedData['nidn'])) {
                $dosen->user->username = $validatedData['nidn'];
                $dosen->nidn = $validatedData['nidn'];
                $dosen->user->password = Hash::make($validatedData['nidn']);
            }

            if (isset($validatedData['email'])) {
                $dosen->user->email = $validatedData['email'];
            }

            if (isset($validatedData['nip'])) {
                $dosen->nip = $validatedData['nip'];
            }

            $dosen->user->name = $validatedData['fullname'];
            $dosen->user->save();

            $dosen->front_degree = $validatedData['gelar-depan'];
            $dosen->back_degree = $validatedData['gelar-belakang'];
            $dosen->position = $validatedData['jabatan'];
            $dosen->rank = $validatedData['pangkat'];
            $dosen->type = $validatedData['golongan'];
            $dosen->phone_number = $validatedData['no-hp'];

            if ($request->hasFile('foto')) {
                $oldImagePath = 'public/images/profile-photo/' . $dosen->user->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('foto');
                $fileName = time() . '_dosen_' . $dosen->user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $dosen->user->photo = $fileName;
            }

            $dosen->save();

            DB::commit();

            return redirect()->route('dashboard.lecturer.index')->with('toast_success', 'Lecturer updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to update Lecturer. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $dosen)
    {
        DB::beginTransaction();

        try {
            $dosen->delete();
            $dosen->user->delete();

            DB::commit();

            $oldImagePath = 'public/images/profile-photo/' . $dosen->user->foto;
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            return redirect()->route('dashboard.lecturer.index')->with('toast_success', 'Lecturer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete Lecturer. Please try again.');
        }
    }
}
