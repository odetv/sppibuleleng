<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (Bisa menerima banyak role, contoh: 'administrator', 'author')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Ambil slug_role dari user yang login
        // Kita gunakan operator ?? untuk jaga-jaga jika role tidak ditemukan di database
        $userRole = auth()->user()->role->slug_role ?? null;

        // 3. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        if ($userRole && in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak punya akses, lempar error 403 (Forbidden)
        abort(403, 'Anda tidak memiliki akses ke halaman ini');
    }
}