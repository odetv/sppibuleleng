<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Izinkan Logout & Verifikasi Email
            if ($request->routeIs(['logout', 'verification.*'])) {
                return $next($request);
            }

            // 2. Cek Profil (id_person)
            if (!$user->id_person) {
                // Jika belum punya id_person, paksa ke halaman pengisian profil
                // Kecuali jika memang sedang berada di halaman pengisian tersebut
                if (!$request->routeIs(['profile.complete', 'profile.store'])) {
                    return redirect()->route('profile.complete')
                        ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
                }
                return $next($request);
            }

            // 3. Cek Status User Pending
            if ($user->status_user === 'pending') {
                // Daftar route yang boleh diakses user pending (setelah isi profil)
                $allowedForPending = [
                    'dashboard',
                    'profile.show',
                    'profile.edit',
                    'profile.update',
                    'profile.destroy',
                    'logout'
                ];

                if (!$request->routeIs($allowedForPending)) {
                    return redirect()->route('dashboard')
                        ->with('warning', 'Akun Anda sedang menunggu verifikasi Admin.');
                }
            }
        }

        return $next($request);
    }
}