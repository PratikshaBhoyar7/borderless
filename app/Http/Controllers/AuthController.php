<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            ActivityLog::log(
                Auth::id(),
                'login',
                'User logged in',
                $request
            );

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Logged in successfully!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $userRole = Role::where('name', Role::USER)->first();
        $user->roles()->attach($userRole);

        ActivityLog::log(
            $user->id,
            'registered',
            'User registered',
            $request
        );

        Auth::login($user);

        ActivityLog::log(
            $user->id,
            'login',
            'User logged in after registration',
            $request
        );

        return redirect(route('admin.dashboard'))
            ->with('success', 'Registered successfully!');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        ActivityLog::log(
            $userId,
            'logout',
            'User logged out',
            $request
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Logged out successfully!');
    }
}
