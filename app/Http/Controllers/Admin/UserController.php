<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\RefPosition;
use App\Models\RefRole;
use App\Models\User;
use App\Models\WorkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

        $users = User::with(['person.position', 'person.workAssignment.sppgUnit', 'person.workAssignment.decree', 'role'])
            ->where('status_user', 'pending')
            ->whereNotNull('id_person')
            ->latest()
            ->get();

        $allUsers = User::with(['person.position', 'person.workAssignment.sppgUnit', 'role'])->latest()->get();

        $trashedUsers = User::onlyTrashed()->with(['person' => function ($query) {
            $query->withTrashed();
        }, 'role'])->latest()->get();

        $roles = RefRole::all();
        $positions = RefPosition::all();

        $workAssignments = WorkAssignment::with(['sppgUnit', 'decree'])->get();

        return view('admin.users.index', compact(
            'users',
            'allUsers',
            'trashedUsers',
            'stats',
            'roles',
            'positions',
            'workAssignments'
        ));
    }

    public function checkAvailability(Request $request)
    {
        $email = $request->query('email');
        $phone = $request->query('phone');

        // Cek masing-masing secara independen
        $emailExists = User::where('email', $email)->exists();
        $phoneExists = User::where('phone', $phone)->exists();

        // Ambil daftar Role ID yang valid
        $validRoles = RefRole::pluck('id_ref_role')->toArray();

        return response()->json([
            'email_duplicate' => $emailExists,
            'phone_duplicate' => $phoneExists,
            'valid_roles' => $validRoles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'id_ref_role' => 'required'
        ]);

        // Admin hanya buat Akun (User), tidak buat Person
        $user = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password),
            'id_ref_role' => $request->id_ref_role,
            'status_user' => 'active',
        ]);

        return back()->with('success', 'Akun berhasil dibuat. Berikan akses login ke pengguna.');
    }

    public function exportExcel(Request $request)
    {
        // Validasi: Harus pilih kolom
        $request->validate([
            'columns' => 'required|array|min:1'
        ], [
            'columns.required' => 'Pilih minimal satu kolom data yang ingin diekspor!'
        ]);

        $selectedColumns = $request->input('columns');
        $fileName = 'DATA_PENGGUNA_SPPI_' . now()->format('d_M_Y_H_i') . '.xlsx';

        // Eksekusi menggunakan class export
        return Excel::download(
            new \App\Exports\UsersExport($selectedColumns),
            $fileName
        );
    }

    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'Template Upload Pengguna.xlsx');
    }

    public function importUsers(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;
        $adminEmail = auth()->user()->email;

        if (!$data) return back()->with('error', 'Data tidak valid.');

        // Ambil mapping role dari DB: ['administrator' => 1, 'author' => 2, ...]
        $roleMapping = RefRole::all()->pluck('id_ref_role', 'slug_role')->toArray();

        try {
            if ($mode === 'replace') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('persons')->join('users', 'users.id_person', '=', 'persons.id_person')
                    ->where('users.email', '!=', $adminEmail)->delete();
                DB::table('users')->where('email', '!=', $adminEmail)->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::transaction(function () use ($data, $roleMapping) {
                foreach ($data as $row) {
                    // Konversi Nama Role di Excel ke Slug, lalu cari ID-nya
                    $roleName = strtolower(trim($row['HAK AKSES (Administrator/Author/Editor/Subscriber/Guest)']));
                    $roleId = $roleMapping[$roleName] ?? 5; // Default ke Guest (ID 5) jika tidak ditemukan

                    DB::table('users')->insert([
                        'id_person'   => null,
                        'email'       => trim($row['EMAIL PENGGUNA']),
                        'phone'       => trim($row['NOMOR WHATSAPP']),
                        'id_ref_role' => $roleId,
                        'password'    => Hash::make($row['PASSWORD']),
                        'status_user' => 'active',
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                }
            });

            return redirect()->route('admin.users.index')->with('success', 'Database berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'id_ref_role' => 'required',
            'id_ref_position' => 'nullable',
            'id_work_assignment' => 'nullable|exists:work_assignments,id_work_assignment',
            'photo' => 'nullable|image|max:2048',
            'date_birthday' => 'nullable|date',
            'batch' => 'nullable|in:1,2,3,Non-SPPI',
            'employment_status' => 'nullable|in:ASN,Non-ASN',
            'last_education' => 'nullable|in:D-III,D-IV,S-1',
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

                // Ambil semua input kecuali yang spesifik tabel users
                $data = $request->except(['photo', '_token', '_method', 'email', 'phone', 'id_ref_role']);

                if ($request->filled('date_birthday')) {
                    $data['age'] = \Carbon\Carbon::parse($request->date_birthday)->age;
                }

                if ($request->hasFile('photo')) {
                    if ($person->photo) {
                        Storage::disk('public')->delete($person->photo);
                    }
                    $data['photo'] = $request->file('photo')->store('photos', 'public');
                }

                // Update data person (termasuk id_work_assignment & id_ref_position)
                $person->update($data);
            }

            DB::commit();
            return back()->with('success', 'Data pengguna dan plotting penugasan berhasil diperbarui.');
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
        // Saat approve, admin bisa langsung set Role dan Jabatannya
        $request->validate([
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role',
            'id_ref_position' => 'nullable|exists:ref_positions,id_ref_position',
            'id_work_assignment' => 'nullable|exists:work_assignments,id_work_assignment',
        ]);

        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            $user->update([
                'status_user' => 'active',
                'id_ref_role' => $request->id_ref_role
            ]);

            if ($user->id_person) {
                $user->person->update([
                    'id_ref_position' => $request->id_ref_position,
                    'id_work_assignment' => $request->id_work_assignment
                ]);
            }

            DB::commit();
            return back()->with('success', "Akun {$user->email} berhasil diaktifkan dan di-plotting.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
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
