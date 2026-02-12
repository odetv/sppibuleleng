<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Hitung statistik
        $stats = [
            'total' => User::count(),
            'active' => User::where('status_user', 'active')->count(),
            'pending' => User::where('status_user', 'pending')->count(),
            // User yang statusnya pending DAN belum punya data di tabel persons
            'incomplete' => User::where('status_user', 'pending')
                ->whereNull('id_person')
                ->count(),
        ];

        // Ambil user yang SUDAH melengkapi profil tapi belum di-approve
        $users = User::with(['person', 'role'])
            ->where('status_user', 'pending')
            ->whereNotNull('id_person') // Pastikan data person ada agar tidak error
            ->latest()
            ->get();

        // Ambil daftar role untuk dropdown (kecuali superadmin jika ingin dibatasi)
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'id_ref_person_role' => 'required|exists:ref_person_roles,id_ref_person_role',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'status_user' => 'active',
            'id_ref_person_role' => $request->id_ref_person_role
        ]);

        return back()->with('success', "Akun {$user->email} berhasil diaktifkan dengan role baru.");
    }
}
