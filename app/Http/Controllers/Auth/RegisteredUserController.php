<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Menangani permintaan registrasi masuk.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Ambil ID Role 'guest' dari tabel ref_person_roles
        $guestRole = DB::table('ref_person_roles')->where('slug_role', 'guest')->first();

        // 2. Buat User baru sesuai skema diagram
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_ref_person_role' => $guestRole->id_ref_person_role, // Otomatis jadi Guest
            'status_user' => 'pending', // Status awal
        ]);

        event(new Registered($user));

        Auth::login($user);

        // 3. Arahkan ke halaman pengisian profil (Tabel Persons)
        // Pastikan Anda sudah membuat route dengan nama 'profile.complete' nanti
        return redirect()->intended(route('profile.complete', absolute: false));
    }
}
