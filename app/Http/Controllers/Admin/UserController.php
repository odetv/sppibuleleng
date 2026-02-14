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

        $users = User::with(['person.position', 'role'])->where('status_user', 'pending')->whereNotNull('id_person')->latest()->get();
        $allUsers = User::with(['person.position', 'role'])->latest()->get();

        // Ambil data yang sudah di soft delete
        $trashedUsers = User::onlyTrashed()->with(['person'])->latest()->get();

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

                $data = $request->only([
                    'id_ref_position',
                    'nik',
                    'no_kk',
                    'name',
                    'title_education',
                    'gender',
                    'place_birthday',
                    'date_birthday',
                    'age',
                    'religion',
                    'marital_status',
                    'province',
                    'regency',
                    'district',
                    'village',
                    'address',
                    'gps_coordinates',
                    'npwp'
                ]);

                if ($request->hasFile('photo')) {
                    if ($person->photo) {
                        Storage::disk('public')->delete($person->photo);
                    }
                    $data['photo'] = $request->file('photo')->store('photos', 'public');
                }

                $person->update($data);
            }

            DB::commit();
            return back()->with('success', 'Data diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Cegah hapus diri sendiri
        if ($id == auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->person && $user->person->photo) {
            Storage::disk('public')->delete($user->person->photo);
        }

        // Hapus person jika ada (karena SoftDeletes digunakan di model Person)
        if ($user->person) {
            $user->person->delete();
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus secara permanen.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        if ($user->person) {
            $user->person()->forceDelete(); // Hapus permanen data personil
        }
        $user->forceDelete(); // Hapus permanen data user
        return back()->with('success', 'Pengguna telah dihapus permanen.');
    }

    public function forceDeleteAll()
    {
        User::onlyTrashed()->each(function ($user) {
            if ($user->person) $user->person()->forceDelete();
            $user->forceDelete();
        });
        return back()->with('success', 'Seluruh tempat sampah telah dikosongkan.');
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore(); // Mengembalikan data user

            // Jika personil juga di soft delete, kembalikan juga
            if ($user->person()->onlyTrashed()->exists()) {
                $user->person()->restore();
            }

            return back()->with('success', "Akun {$user->email} berhasil diaktifkan kembali.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal memulihkan pengguna.");
        }
    }

    // Tambahkan di UserController.php

    public function approve(Request $request, $id)
    {
        // Jika tidak ada role yang dipilih, kita beri default role (misal: 'guest' atau id role terendah)
        // Atau pastikan admin memilih role di tabel sebelum klik approve
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
        // Approve semua yang pending dan berikan role default dari input
        $request->validate(['id_ref_role' => 'required']);

        User::where('status_user', 'pending')->update([
            'status_user' => 'active',
            'id_ref_role' => $request->id_ref_role
        ]);

        return back()->with('success', 'Semua pendaftar berhasil diverifikasi.');
    }
}
