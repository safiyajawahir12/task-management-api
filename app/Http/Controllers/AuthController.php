<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * GET /login
     * Show the login page.
     * If already logged in, redirect to correct dashboard.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * POST /login
     * Handle login form submission.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validate the incoming fields
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt authentication
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Failed — send back with error message
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // 3. Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        // 4. Redirect based on role
        return $this->redirectByRole(Auth::user());
    }

    /**
     * POST /logout
     * Log the user out and redirect to login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    /**
     * GET /register
     * Show the registration page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * POST /register
     * Handle registration. All new accounts are 'user' role by default.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user', // always starts as user
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Welcome! Your account has been created.');
    }

    // ── Private helpers ────────────────────────────────────────

    /**
     * Redirect to the correct dashboard based on the user's role.
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
}
