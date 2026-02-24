<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Cek User di database berdasarkan email saja
        $user = User::withTrashed()->where('email', $request->email)->first();

        // 2. CEK SOFT DELETE: Jika user ada di sampah (Non-Aktif)
        if ($user && $user->trashed()) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan oleh administrator.',
            ])->withInput($request->only('email'));
        }

        // 3. CEK AKTIVASI: Jika user ditemukan tapi belum verifikasi email
        // Ini menangani user baru (Input Admin) yang mencoba login sebelum set password
        if ($user && is_null($user->email_verified_at)) {
            return back()->withErrors([
                'email' => 'Akun Anda belum aktif. Silakan periksa email masuk (atau folder spam) untuk melakukan aktivasi dan pembuatan password.',
            ])->withInput($request->only('email'));
        }

        // 4. Jika lolos pengecekan di atas, jalankan autentikasi bawaan Breeze
        // Di sini Laravel baru akan mencocokkan Email dan Password
        $request->authenticate();

        $request->session()->regenerate();

        // 5. Setelah login berhasil, arahkan ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}