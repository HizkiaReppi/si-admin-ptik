<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $administrators = User::where('role', 'admin')->get();
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
    public function store(AdminStoreRequest $request, User $user)
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
