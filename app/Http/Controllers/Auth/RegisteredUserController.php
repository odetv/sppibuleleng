<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RefRole;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// Gunakan alias (as) agar tidak bentrok dengan Facade Password
use Illuminate\Validation\Rules\Password as PasswordRules; 
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            // Gunakan nama alias PasswordRules di sini
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
        ]);

        return DB::transaction(function () use ($request) {
            try {
                $guestRole = RefRole::where('slug_role', 'guest')->first();

                $user = User::create([
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'status_user' => 'pending', 
                    'id_ref_role' => $guestRole->id_ref_role ?? null,
                    'email_verified_at' => null, 
                ]);

                // Mengirimkan event pendaftaran standar Laravel
                event(new Registered($user));

                return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.');

            } catch (Exception $e) {
                // Jika email gagal terkirim atau error sistem, DB Transaction akan rollback otomatis
                return back()->withErrors(['email' => 'Gagal mengirim email verifikasi.'])->withInput();
            }
        });
    }
}