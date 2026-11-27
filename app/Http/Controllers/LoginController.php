<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validate login fields
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Create a separate rate-limit key for each email
        $email = strtolower(trim($credentials['email']));

        $key = 'login:' . $email;

        // Allow a maximum of 5 failed attempts per minute
        if (RateLimiter::tooManyAttempts($key, 5)) {

            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => "Too many failed login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // Attempt authentication
        if (!Auth::attempt($credentials)) {

            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // Successful login: reset failed attempts
        RateLimiter::clear($key);

        // Regenerate session for security
        $request->session()->regenerate();

        $user = Auth::user();

        // Admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Teacher dashboard
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        // Reject accounts without valid roles
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Your account does not have a valid role.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have logged out successfully.');
    }
}
