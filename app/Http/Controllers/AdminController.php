<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
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

        $administrators = Cache::rememberForever('administrators', function () {
            return User::where('role', 'admin')->get();
        });
        return view('dashboard.administrator.index', compact('administrators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.administrator.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStoreRequest $request, User $user): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->role = 'admin';

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = time() . '_admin_' . $user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $user->photo = $fileName;
            }

            $user->save();

            DB::commit();
            return redirect()->route('dashboard.administrator.index')->with('toast_success', 'Administrator added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add Administrator. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $administrator): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        return view('dashboard.administrator.show', compact('administrator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $administrator): View
    {
        if (!Gate::allows('super-admin') && !Gate::allows('HoD') && auth()->user()->id != $administrator->id) {
            abort(403);
        }

        return view('dashboard.administrator.edit', compact('administrator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateRequest $request, User $administrator): RedirectResponse
    {
        if (!Gate::allows('super-admin') && !Gate::allows('HoD') && auth()->user()->id != $administrator->id) {
            abort(403);
        }

        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $administrator->name = $validatedData['fullname'];

            if(isset($validatedData['username'])) {
                $administrator->username = $validatedData['username'];
            }

            if(isset($validatedData['email'])) {
                $administrator->email = $validatedData['email'];
            }

            if(isset($validatedData['password'])) {
                $administrator->password = Hash::make($validatedData['password']);
            }

            if ($request->hasFile('foto')) {
                $oldImagePath = 'public/images/profile-photo/' . $administrator->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('foto');
                $fileName = time() . '_admin_' . $administrator->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $administrator->photo = $fileName;
            }

            $administrator->save();

            DB::commit();
            return redirect()->route('dashboard.administrator.index')->with('toast_success', 'Administrator updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to update Administrator. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (!Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();

            $oldImagePath = 'public/images/profile-photo/' . $user->foto;
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            return redirect()->route('dashboard.administrator.index')->with('toast_success', 'Administrator deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to delete Administrator. Please try again.');
        }
    }
}
