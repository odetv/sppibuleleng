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
        // Jika user login tapi belum punya data person
        if (auth()->check() && !auth()->user()->id_person) {
            // Jangan redirect jika user sudah berada di halaman pengisian profil
            if (!$request->is('complete-profile*')) {
                return redirect()->route('profile.complete')
                    ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
