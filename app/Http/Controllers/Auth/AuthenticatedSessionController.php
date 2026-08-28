<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->authenticate();

        $request->session()->regenerate();

        $role = $request->user()->role;
        $defaultRoute = match ($role) {
            'admin' => route('admin.dashboard', absolute: false),
            'instructor' => route('instructor.dashboard', absolute: false),
            'student' => route('student.dashboard', absolute: false),
            default => '/',
        };

        $roleMessage = match ($role) {
            'admin' => "You're logged in as an Admin!",
            'instructor' => "You're logged in as an Instructor!",
            'student' => "You're logged in as a Student!",
            default => "You're logged in!",
        };

        return redirect()->intended($defaultRoute)->with('toast_success', $roleMessage);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
