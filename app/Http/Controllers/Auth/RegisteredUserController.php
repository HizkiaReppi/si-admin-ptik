<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $lecturers = Cache::rememberForever('lecturers_student', function () {
            return Lecturer::all();
        });
        $concentrations = ['rpl', 'multimedia', 'tkj'];
        return view('auth.register', compact('lecturers', 'concentrations'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'nim' => ['required', 'string', 'max:10', 'min:4', 'unique:' . Student::class, 'regex:/^[0-9]*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:4', 'unique:' . User::class],
            'angkatan' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . (date('Y'))],
            'konsentrasi' => ['required', 'string', 'in:rpl,multimedia,tkj'],
            'lecturer_id_1' => ['required', 'exists:' . Lecturer::class . ',id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'fullname.regex' => 'The nim field must be alphabet.',
            'nim.unique' => 'The nim field must be unique.',
            'nim.regex' => 'The nim field must be number.',
            'konsentrasi.in' => 'The konsentrasi field must be one of the following: rpl, multimedia, tkj.',
            'lecturer_id_1.exists' => 'The selected dosen pembimbing 1 is invalid.',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->username = $validatedData['nim'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->role = 'student';
            $user->save();

            $student = new Student();
            $student->user_id = $user->id;
            $student->lecturer_id_1 = $validatedData['lecturer_id_1'];
            $student->nim = $validatedData['nim'];
            $student->batch = $validatedData['angkatan'];
            $student->concentration = $validatedData['konsentrasi'];

            $student->save();

            DB::commit();

            Auth::login($user);

            return redirect(route('dashboard.submission.student.index', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add Student. Please try again.');
        }
    }
}