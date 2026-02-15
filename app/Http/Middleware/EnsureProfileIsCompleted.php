<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Cek profil (punya id_person)
            if (!$user->id_person) {
                if (!$request->is('complete-profile*') && !$request->routeIs('logout')) {
                    return redirect()->route('profile.complete')
                        ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
                }
                return $next($request);
            }

            // 2. Cek status_user pending
            if ($user->status_user === 'pending') {
                $allowedRoutes = ['dashboard', 'profile.edit', 'profile.update', 'profile.destroy', 'logout'];
                if (!$request->routeIs($allowedRoutes)) {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}
