<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        $isMaintenance = Setting::get('is_maintenance', '0') === '1';

        if ($isMaintenance) {
            if (Auth::check()) {
                $user = Auth::user();

                // Cek berdasarkan id_ref_role (1) atau slug (administrator) dari tabel Anda
                if ($user->id_ref_role == 1 || ($user->role && $user->role->slug_role === 'administrator')) {
                    return $next($request);
                }
            }

            // Izinkan halaman login agar Admin bisa masuk saat maintenance
            if ($request->is('login') || $request->routeIs('login') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Blokir selain itu
            return response()->view('errors.maintenance', [
                'isMaintenance' => true, // Kirim tanda bahwa sistem sedang maintenance
                'title' => 'Sistem Sedang Diperbarui'
            ], 503);
        }

        return $next($request);
    }
}