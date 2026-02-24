<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPosition
{
    public function handle(Request $request, Closure $next, ...$positions): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Ambil slug_position dari user melalui relasi person
        $userPosition = auth()->user()->person->position->slug_position ?? null;
        $userRole = auth()->user()->role->slug_role ?? null;

        // 3. Administrator selalu punya akses, atau cek apakah posisi user diizinkan
        if ($userRole === 'administrator' || ($userPosition && in_array($userPosition, $positions))) {
            return $next($request);
        }

        // 4. Jika ditolak, lempar error 403
        abort(403, 'Hanya jabatan tertentu yang dapat mengakses halaman ini');
    }
}
