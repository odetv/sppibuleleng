<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Hitung statistik untuk Dashboard Admin
        $stats = [
            'total'   => User::count(),
            'active'  => User::where('status_user', 'active')->count(),
            'pending' => User::where('status_user', 'pending')->count(),
        ];

        // Mengambil user yang masih pending untuk tabel verifikasi
        $users = User::with(['person', 'role'])
            ->where('status_user', 'pending')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status_user' => 'active']); // Mengubah status menjadi aktif

        return back()->with('success', "Akun {$user->email} berhasil diaktifkan!");
    }
}
