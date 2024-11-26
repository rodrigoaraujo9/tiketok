<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|regex:/^[0-9]{9,15}$/'
        ]);

        // Create a new user with the default "user" role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone, // Optional
            'role_id' => 2, // Default to "user" role_id
        ]);

        // Authenticate the user after registration
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('events.index')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
