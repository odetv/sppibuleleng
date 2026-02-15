<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\RefPosition;
use App\Models\RefRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('status_user', 'active')->count(),
            'pending' => User::where('status_user', 'pending')->count(),
            'incomplete' => User::whereNull('id_person')->count(),
        ];

        // Ambil data antrian (pending)
        $users = User::with(['person.position', 'role'])
            ->where('status_user', 'pending')
            ->whereNotNull('id_person')
            ->latest()
            ->get();

        // Ambil semua data untuk database utama
        $allUsers = User::with(['person.position', 'role'])->latest()->get();

        // Ambil data yang sudah di soft delete (relasi person harus withTrashed)
        $trashedUsers = User::onlyTrashed()->with(['person' => function ($query) {
            $query->withTrashed();
        }, 'role'])->latest()->get();

        $roles = RefRole::all();
        $positions = RefPosition::all();

        return view('admin.users.index', compact('users', 'allUsers', 'trashedUsers', 'stats', 'roles', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'id_ref_role' => 'required',
            'id_ref_position' => 'required',
            'photo' => 'nullable|image|max:2048',
            'date_birthday' => 'nullable|date',
        ]);

        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            $user->update([
                'email' => $request->email,
                'phone' => $request->phone,
                'id_ref_role' => $request->id_ref_role,
            ]);

            if ($user->id_person) {
                $person = Person::findOrFail($user->id_person);
                $data = $request->except(['photo', '_token', '_method']);

                if ($request->filled('date_birthday')) {
                    $data['age'] = \Carbon\Carbon::parse($request->date_birthday)->age;
                }

                if ($request->hasFile('photo')) {
                    if ($person->photo) {
                        Storage::disk('public')->delete($person->photo);
                    }
                    $data['photo'] = $request->file('photo')->store('photos', 'public');
                }

                $person->update($data);
            }

            DB::commit();
            return back()->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            // JANGAN ubah kolom status_user, biarkan aslinya (active/pending)
            // Cukup jalankan soft delete
            if ($user->person) {
                $user->person->delete();
            }
            $user->delete();

            DB::commit();
            return back()->with('success', 'Pengguna berhasil dipindahkan ke tempat sampah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengguna.');
        }
    }

    public function forceDelete($id)
    {
        // Ambil user dari sampah beserta personnya (termasuk yang di-softdelete)
        $user = User::onlyTrashed()->with(['person' => function ($q) {
            $q->withTrashed();
        }])->findOrFail($id);

        try {
            DB::beginTransaction();

            if ($user->id_person) {
                // Ambil instance person
                $person = Person::withTrashed()->find($user->id_person);

                if ($person) {
                    // 1. Hapus File Fisik Foto
                    if ($person->photo && Storage::disk('public')->exists($person->photo)) {
                        Storage::disk('public')->delete($person->photo);
                    }

                    // 2. Putus hubungan di table users dulu (agar tidak melanggar constraint)
                    $user->update(['id_person' => null]);

                    // 3. Hapus Permanen dari table persons
                    $person->forceDelete();
                }
            }

            // 4. Hapus Permanen dari table users
            $user->forceDelete();

            DB::commit();
            return back()->with('success', 'Data pengguna dan profil berhasil dibuang permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debug jika diperlukan: \Log::error($e->getMessage());
            return back()->with('error', 'Gagal hapus permanen: ' . $e->getMessage());
        }
    }

    public function forceDeleteAll()
    {
        try {
            DB::beginTransaction();

            // Ambil semua yang di sampah
            $trashedUsers = User::onlyTrashed()->get();

            foreach ($trashedUsers as $user) {
                if ($user->id_person) {
                    $person = Person::withTrashed()->find($user->id_person);
                    if ($person) {
                        // Hapus Foto
                        if ($person->photo && Storage::disk('public')->exists($person->photo)) {
                            Storage::disk('public')->delete($person->photo);
                        }
                        // Putus hubungan
                        $user->update(['id_person' => null]);
                        // Hapus Person
                        $person->forceDelete();
                    }
                }
                // Hapus User
                $user->forceDelete();
            }

            DB::commit();
            return back()->with('success', 'Tempat sampah berhasil dikosongkan total.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengosongkan sampah.');
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            // Mengambil user dari sampah
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore(); // Status_user akan tetap seperti sebelum dihapus

            // Pulihkan profil (person) yang terkait
            $person = Person::withTrashed()->find($user->id_person);
            if ($person) {
                $person->restore();
            }

            DB::commit();
            return back()->with('success', "Akun {$user->email} berhasil dipulihkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Gagal memulihkan pengguna.");
        }
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'status_user' => 'active',
            'id_ref_role' => $request->id_ref_role
        ]);

        return back()->with('success', "Akun {$user->email} berhasil diaktifkan.");
    }

    public function approveAll(Request $request)
    {
        $request->validate(['id_ref_role' => 'required']);

        User::where('status_user', 'pending')->update([
            'status_user' => 'active',
            'id_ref_role' => $request->id_ref_role
        ]);

        return back()->with('success', 'Semua pendaftar berhasil diverifikasi.');
    }
}
