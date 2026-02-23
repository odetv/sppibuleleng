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

            // 1. PENGECUALIAN KRUSIAL: Izinkan proses Logout dan Verifikasi Email
            // Tanpa ini, user yang belum verifikasi email atau belum isi profil tidak akan bisa logout/verifikasi.
            if ($request->routeIs(['logout', 'verification.*'])) {
                return $next($request);
            }

            // 2. CEK VERIFIKASI EMAIL (Opsional - Jika ingin proteksi ganda)
            // Memastikan user sudah klik link di email sebelum diminta isi profil.
            if (is_null($user->email_verified_at)) {
                return $next($request); 
            }

            // 3. CEK PROFIL (id_person)
            // Jika user belum punya data person, paksa ke halaman pelengkapan profil.
            if (!$user->id_person) {
                if (!$request->is('complete-profile*')) {
                    return redirect()->route('profile.complete')
                        ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
                }
                return $next($request);
            }

            // 4. CEK STATUS USER PENDING
            // Jika akun sudah punya profil tapi masih 'pending' (belum di-approve admin), 
            // batasi akses mereka hanya ke dashboard dan pengaturan profil sendiri.
            if ($user->status_user === 'pending') {
                $allowedRoutes = [
                    'dashboard', 
                    'profile.show', 
                    'profile.edit', 
                    'profile.update', 
                    'profile.destroy'
                ];

                if (!$request->routeIs($allowedRoutes)) {
                    return redirect()->route('dashboard')
                        ->with('warning', 'Akun Anda sedang menunggu verifikasi Admin. Akses fitur lain masih dibatasi.');
                }
            }
        }

        return $next($request);
    }
}