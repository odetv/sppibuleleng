<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RefRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cari ID untuk role 'guest' secara otomatis agar tidak salah ID
        $guestRole = RefRole::where('slug_role', 'guest')->first();

        $user = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status_user' => 'pending', // User baru statusnya pending sampai di-approve admin
            'id_ref_role' => $guestRole->id_ref_role ?? null, // Menggunakan kolom baru id_ref_role
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Setelah register, arahkan ke dashboard. 
        // Di dashboard nanti kita bisa beri notifikasi agar user melengkapi data Person.
        return redirect(route('dashboard', absolute: false));
    }
}