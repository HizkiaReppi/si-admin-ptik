<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeadOfDepartmentStoreRequest;
use App\Http\Requests\HeadOfDepartmentUpdateRequest;
use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class HeadOfDepartmentController extends Controller
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
    public function index(): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $headOfDepartments = Cache::rememberForever('headOfDepartment', function () {
            return HeadOfDepartment::with('user')->get();
        });
        $kajur = !empty($headOfDepartments->where('role', 'kajur')->first());
        $sekjur = !empty($headOfDepartments->where('role', 'sekjur')->first());

        return view('dashboard.pimpinan-jurusan.index', compact('headOfDepartments', 'kajur', 'sekjur'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lecturers = Cache::rememberForever('lecturers', function () {
            return Lecturer::with('user')->get();
        });
        return view('dashboard.pimpinan-jurusan.create', compact('lecturers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HeadOfDepartmentStoreRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $existingHeadOfDepartment = HeadOfDepartment::where('role', $validatedData['role'])->first();

            if ($existingHeadOfDepartment) {
                $oldImagePath = 'public/images/profile-photo/' . $existingHeadOfDepartment->user->foto;

                $existingHeadOfDepartment->delete();
                $existingHeadOfDepartment->user->delete();

                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->email = $validatedData['email'];
            $user->username = rand(1, 999) . "_" . $validatedData['nidn'];
            $user->password = Hash::make($validatedData['role'] . '_' . $validatedData['nidn']);
            $user->role = 'HoD';
            $user->email_verified_at = now();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = time() . '_pimpinan-jurusan_' . $user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $user->photo = $fileName;
            }

            $user->save();

            $headOfDepartment = new HeadOfDepartment();
            $headOfDepartment->user_id = $user->id;
            $headOfDepartment->nip = $validatedData['nip'];
            $headOfDepartment->nidn = $validatedData['nidn'];
            $headOfDepartment->front_degree = $validatedData['gelar-depan'];
            $headOfDepartment->back_degree = $validatedData['gelar-belakang'];
            $headOfDepartment->position = $validatedData['jabatan'];
            $headOfDepartment->rank = $validatedData['pangkat'];
            $headOfDepartment->type = $validatedData['golongan'];
            $headOfDepartment->phone_number = $validatedData['no-hp'];
            $headOfDepartment->role = $validatedData['role'];

            $headOfDepartment->save();

            DB::commit();

            return redirect()->route('dashboard.pimpinan-jurusan.index')->with('toast_success', 'Ketua Jurusan added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to add Ketua Jurusan. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HeadOfDepartment $pimpinan_jurusan): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $pimpinan_jurusan->load('user');

        return view('dashboard.pimpinan-jurusan.show', compact('pimpinan_jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeadOfDepartment $pimpinan_jurusan): View
    {
        return view('dashboard.pimpinan-jurusan.edit', compact('pimpinan_jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HeadOfDepartmentUpdateRequest $request, HeadOfDepartment $pimpinan_jurusan): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($validatedData['nidn'])) {
                $pimpinan_jurusan->user->username = rand(1, 999) . "_" . $validatedData['nidn'];
                $pimpinan_jurusan->nidn = $validatedData['nidn'];
                $pimpinan_jurusan->user->password = Hash::make($validatedData['role'] . '_' . $validatedData['nidn']);
            }

            if (isset($validatedData['nip'])) {
                $pimpinan_jurusan->nip = $validatedData['nip'];
            }

            if (isset($validatedData['email'])) {
                $pimpinan_jurusan->user->email = $validatedData['email'];
            }

            $pimpinan_jurusan->front_degree = $validatedData['gelar-depan'];
            $pimpinan_jurusan->back_degree = $validatedData['gelar-belakang'];
            $pimpinan_jurusan->position = $validatedData['jabatan'];
            $pimpinan_jurusan->rank = $validatedData['pangkat'];
            $pimpinan_jurusan->type = $validatedData['golongan'];
            $pimpinan_jurusan->phone_number = $validatedData['no-hp'];

            if ($request->hasFile('foto')) {
                $oldImagePath = 'public/images/profile-photo/' . $pimpinan_jurusan->user->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('foto');
                $fileName = time() . '_pimpinan-jurusan_' . $pimpinan_jurusan->user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $pimpinan_jurusan->user->photo = $fileName;
            }

            $pimpinan_jurusan->user->name = $validatedData['fullname'];
            $pimpinan_jurusan->user->save();
            $pimpinan_jurusan->save();

            DB::commit();

            return redirect()->route('dashboard.pimpinan-jurusan.index')->with('toast_success', 'Ketua Jurusan updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to update Ketua Jurusan. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeadOfDepartment $pimpinan_jurusan): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $pimpinan_jurusan->delete();
            $pimpinan_jurusan->user->delete();

            DB::commit();

            $oldImagePath = 'public/images/profile-photo/' . $pimpinan_jurusan->user->foto;
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            return redirect()->route('dashboard.pimpinan-jurusan.index')->with('toast_success', 'Ketua Jurusan deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete Ketua Kurusan. Please try again.');
        }
    }
}
