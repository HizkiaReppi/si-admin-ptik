<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $inputPassword = $request->input('password');
        $inputEmail = $request->input('email');

        $hoDUser = User::where('email', 'like', 'ptik.'.$inputEmail)->first();

        if ($hoDUser && Hash::check($inputPassword, $hoDUser->password)) {
            $request->merge(['email' => 'ptik.' . $inputEmail]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        if(Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin'){
            return redirect()->intended(route('dashboard', absolute: false));
        } else if(Auth::user()->role == 'lecturer'){ // TODO: FIX THIS LATER
            return redirect()->intended(route('dashboard', absolute: false));
        } else if(Auth::user()->role == 'student'){
            return redirect()->intended(route('dashboard.submission.student.index', absolute: false));
        } else if(Auth::user()->role == 'HoD'){
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            return redirect()->intended('/');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
