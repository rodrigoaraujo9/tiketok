<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display a login form.
     */
    public function showLoginForm(): View
    {
        if (Auth::check()) {
            return redirect('/cards');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'], // Can be email or username
            'password' => ['required'],
        ]);

        // Detect if login is with email or username
        $fieldType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Prepare credentials
        $credentials = [
            $fieldType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // Attempt authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            if ($user->role_id == 1) { // Admin role
                return redirect()->intended('/admin/dashboard')
                    ->withSuccess('Welcome back, Admin!');
            } elseif ($user->role_id == 2) { // User role
                return redirect()->intended('/cards')
                    ->withSuccess('Welcome back!');
            }

            // Default redirection for other roles
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Log out the user from the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }
}
