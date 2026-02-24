<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Tambahkan ini
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // Gunakan Request biasa, bukan EmailVerificationRequest
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Menandai email sebagai terverifikasi tanpa mewajibkan login.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // 1. Ambil user berdasarkan ID yang ada di URL link email
        $user = User::findOrFail($request->route('id'));

        // 2. Validasi Signature (Keamanan): Pastikan link asli dan belum expired
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')->withErrors(['email' => 'Link verifikasi sudah kadaluarsa atau tidak valid.']);
        }

        // 3. Cek jika sudah diverifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Email sudah diverifikasi sebelumnya. Silakan masuk.');
        }

        // 4. Proses Verifikasi ke Database
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // 5. Paksa logout (jika ada session menggantung) agar user login secara resmi
        Auth::logout();

        // 6. Kembalikan ke login dengan pesan sukses kustom
        return redirect()->route('login')->with('status', 'Email Anda berhasil diverifikasi! Akun Anda kini aktif, silakan masuk ke portal.');
    }
}