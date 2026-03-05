<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Check that the authenticated user has the required role.
     *
     * Usage in routes:
     *   Route::middleware('role:admin')  — only admins can pass
     *   Route::middleware('role:user')  — only regular users can pass

     * If the wrong role tries to access, they're redirected to their
     * own dashboard instead of seeing a 403 error (better UX).
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        // Not logged in at all — send to login
        if (!$user) {
            return redirect()->route('login');
        }

        // User has the correct role — let them through
        if ($user->role === $role) {
            return $next($request);
        }

        // Wrong role — redirect to their correct dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        return redirect()->route('user.dashboard')
            ->with('error', 'You do not have permission to access that page.');
    }
}
