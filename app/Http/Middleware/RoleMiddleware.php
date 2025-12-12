<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        $userRoles = $user->roles->pluck('slug')->toArray();

        // Check if user has any of the required roles
        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            // If user has no roles, redirect to login
            if (empty($userRoles)) {
                return redirect()->route('auth.login')
                    ->with('error', 'Akun Anda belum memiliki role yang ditentukan');
            }

            // If user has roles but not the required ones
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
