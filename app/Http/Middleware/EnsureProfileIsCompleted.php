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
        // Jika user sudah login tapi id_person masih kosong (belum isi profil)
        if (Auth::check() && is_null(Auth::user()->id_person)) {
            // Jangan redirect jika user memang sedang berada di halaman pengisian profil
            if (!$request->routeIs('profile.complete') && !$request->routeIs('profile.store')) {
                return redirect()->route('profile.complete')
                    ->with('info', 'Silakan lengkapi identitas Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
