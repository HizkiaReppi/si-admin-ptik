<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileUpdateRequest;
use App\Http\Requests\LecturerProfileUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\StudentProfileUpdateRequest;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\HeadOfDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $role = $request->user()->role;
        $userId = $request->user()->id;

        $cacheKey = "profile_{$role}_{$userId}";

        $user = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($role, $userId) {
            if ($role == 'admin' || $role == 'super-admin') {
                return User::find($userId);
            } else if ($role == 'student') {
                return Student::where('user_id', $userId)->with('user')->first();
            } else if ($role == 'lecturer') {
                return Lecturer::where('user_id', $userId)->with('user')->first();
            } else if ($role == 'HoD') {
                return HeadOfDepartment::where('user_id', $userId)->with('user')->first();
            }
        });


        return view('profile.edit', compact('user'));
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        $cacheKey = "profile_{$request->user()->role}_{$request->user()->id}";
        Cache::forget($cacheKey);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the admin profile information.
     */
    public function update_admin(AdminProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        $cacheKey = "profile_admin_{$request->user()->id}";
        Cache::forget($cacheKey);

        return Redirect::route('profile.edit')->with('toast_success', 'Profil berhasil diupdate');
    }

    /**
     * Update the user's profile information.
     */
    public function update_lecturer(LecturerProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($validatedData['fullname'])) {
                $request->user()->name = $validatedData['fullname'];
                $request->user()->save();
            }

            if (isset($validatedData['nidn'])) {
                $request->user()->username = $validatedData['nidn'];
                $request->user()->save();
                $request->user()->lecturer->nidn = $validatedData['nidn'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['email'])) {
                $request->user()->email = $validatedData['email'];
                $request->user()->save();
            }

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
                $request->user()->save();
            }

            if (isset($validatedData['nip'])) {
                $request->user()->lecturer->nip = $validatedData['nip'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['gelar-depan'])) {
                $request->user()->lecturer->front_degree = $validatedData['gelar-depan'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['gelar-belakang'])) {
                $request->user()->lecturer->back_degree = $validatedData['gelar-belakang'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['jabatan'])) {
                $request->user()->lecturer->position = $validatedData['jabatan'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['pangkat'])) {
                $request->user()->lecturer->rank = $validatedData['pangkat'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['golongan'])) {
                $request->user()->lecturer->type = $validatedData['golongan'];
                $request->user()->lecturer->save();
            }

            if (isset($validatedData['no-hp'])) {
                $request->user()->lecturer->phone_number = $validatedData['no-hp'];
                $request->user()->lecturer->save();
            }

            if ($request->hasFile('foto')) {
                $oldImagePath = 'public/images/profile-photo/' . $request->user()->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('foto');
                $fileName = time() . '_dosen_' . $request->user()->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $request->user()->photo = $fileName;
                $request->user()->save();
            }

            DB::commit();
            $cacheKey = "profile_lecturer_{$request->user()->id}";
            Cache::forget($cacheKey);

            return Redirect::route('profile.edit')->with('toast_success', 'Profil berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::route('profile.edit')->with('toast_error', 'Profil gagal diupdate');
        }
    }

    /**
     * Update the student's profile information.
     */
    public function update_student(StudentProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($validatedData['fullname'])) {
                $request->user()->name = $validatedData['fullname'];
                $request->user()->save();
            }

            if (isset($validatedData['email'])) {
                $request->user()->email = $validatedData['email'];
                $request->user()->save();
            }

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
                $request->user()->save();
            }

            if (isset($validatedData['no-hp'])) {
                $request->user()->student->phone_number = $validatedData['no-hp'];
                $request->user()->student->save();
            }

            if (isset($validatedData['konsentrasi'])) {
                $request->user()->student->concentration = $validatedData['konsentrasi'];
                $request->user()->student->save();
            }

            if (isset($validatedData['alamat'])) {
                $request->user()->student->address = $validatedData['alamat'];
                $request->user()->student->save();
            }

            if ($request->hasFile('foto')) {
                $oldImagePath = 'public/images/profile-photo/' . $request->user()->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('foto');
                $fileName = time() . '_mahasiswa_' . $request->user()->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $request->user()->photo = $fileName;
                $request->user()->save();
            }

            DB::commit();
            $cacheKey = "profile_student_{$request->user()->id}";
            Cache::forget($cacheKey);

            return Redirect::route('profile.edit')->with('toast_success', 'Profil berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::route('profile.edit')->with('toast_error', 'Profil gagal diupdate');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->role == 'admin' && User::where('role', 'admin')->count() <= 1) {
            return Redirect::route('profile.edit')->with('toast_error', 'Admin tidak bisa dihapus');
        }

        Auth::logout();

        $userId = $user->id;
        $userRole = $user->role;

        $user->delete();
        $cacheKey = "profile_{$userRole}_{$userId}";
        Cache::forget($cacheKey);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }
}
