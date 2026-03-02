<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\RefPosition;
use App\Models\RefRole;
use App\Models\SppgUnit;
use App\Models\User;
use App\Models\WorkAssignment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $allUsersDisplay = User::with(['person.position', 'person.workAssignment.sppgUnit', 'person.workAssignment.decree', 'person.socialMedia', 'role'])->latest()->get();

        // Ambil keyword masing-masing
        $searchPending = $request->query('search_pending');
        $searchAll = $request->query('search_all');
        $searchTrash = $request->query('search_trash');

        $perPagePending = $request->query('per_page_pending', 5);
        $perPageAll = $request->query('per_page_all', 5);
        $perPageTrash = $request->query('per_page_trash', 5);

        // 1. Antrian Verifikasi (Pending)
        $pendingQuery = User::query()->with(['person.position', 'person.workAssignment.sppgUnit', 'role'])
            ->where('status_user', 'pending');
        if ($searchPending) {
            $pendingQuery->where(function ($q) use ($searchPending) {
                $q->where('email', 'like', "%{$searchPending}%")
                    ->orWhere('phone', 'like', "%{$searchPending}%")
                    ->orWhereHas('person', fn($qp) => $qp->where('name', 'like', "%{$searchPending}%"));
            });
        }
        $pendingUsers = $pendingQuery->latest()->paginate($perPagePending, ['*'], 'pending_page')->withQueryString();

        // 2. Daftar Seluruh Pengguna (Active)
        $allQuery = User::query()->with(['person.position', 'person.workAssignment.sppgUnit', 'role'])
            ->where('status_user', 'active');
        if ($searchAll) {
            $allQuery->where(function ($q) use ($searchAll) {
                $q->where('email', 'like', "%{$searchAll}%")
                    ->orWhere('phone', 'like', "%{$searchAll}%")
                    ->orWhereHas('person', fn($qp) => $qp->where('name', 'like', "%{$searchAll}%"));
            });
        }
        $allUsers = $allQuery->latest()->paginate($perPageAll, ['*'], 'all_page')->withQueryString();

        // 3. Tempat Sampah (Trashed)
        $trashQuery = User::onlyTrashed()->with(['person' => fn($q) => $q->withTrashed(), 'role']);
        if ($searchTrash) {
            $trashQuery->where(function ($q) use ($searchTrash) {
                $q->where('email', 'like', "%{$searchTrash}%")
                    ->orWhere('phone', 'like', "%{$searchTrash}%")
                    ->orWhereHas('person', fn($qp) => $qp->where('name', 'like', "%{$searchTrash}%"));
            });
        }
        $trashedUsers = $trashQuery->latest('deleted_at')->paginate($perPageTrash, ['*'], 'trash_page')->withQueryString();

        // Stats & Data Master tetap sama
        $stats = [
            'total' => User::count(),
            'active' => User::where('status_user', 'active')->count(),
            'pending' => User::where('status_user', 'pending')->count(),
            'incomplete' => User::whereNull('id_person')->count(),
        ];

        $roles = RefRole::all();
        $positions = RefPosition::all();
        $workAssignments = WorkAssignment::with(['sppgUnit', 'decree'])->get();

        // Ambil data personil yang sudah terisi di SPPG Unit untuk validasi UI
        $occupiedPositions = SppgUnit::select('id_sppg_unit', 'leader_id', 'nutritionist_id', 'accountant_id')
            ->get()
            ->mapWithKeys(function($unit) {
                return [$unit->id_sppg_unit => [
                    'kasppg' => $unit->leader_id,
                    'ag' => $unit->nutritionist_id,
                    'ak' => $unit->accountant_id,
                ]];
            })->toArray();

        if ($request->ajax()) {
            return view('admin.manage-user.index', compact('pendingUsers', 'allUsersDisplay', 'allUsers', 'trashedUsers', 'stats', 'roles', 'positions', 'workAssignments', 'occupiedPositions'))->render();
        }

        return view('admin.manage-user.index', compact('pendingUsers', 'allUsersDisplay', 'allUsers', 'trashedUsers', 'stats', 'roles', 'positions', 'workAssignments', 'occupiedPositions'));
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
        // 1. Validasi: Menggunakan rfc,dns untuk memastikan domain email nyata
        try {
            $request->validate([
                'email' => 'required|email:rfc,dns|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'id_ref_role' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal, pastikan email memiliki domain aktif.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        try {
            DB::beginTransaction();
            
            // 2. Buat User sementara (belum commit ke DB permanen)
            $user = User::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make(Str::random(32)),
                'id_ref_role' => $request->id_ref_role,
                'status_user' => 'active',
                'email_verified_at' => null, 
            ]);

            // 3. Kirim Link Aktivasi
            // Jika server email menolak (alamat fiktif), ini akan melempar Exception
            $token = \Illuminate\Support\Facades\Password::createToken($user);
            $user->sendPasswordResetNotification($token);

            DB::commit();

            // Jika sampai sini, berarti email berhasil diterima server SMTP
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil dibuat. Link aktivasi telah dikirim ke email pengguna.'
                ]);
            }
            return back()->with('success', 'Akun berhasil dibuat. Link aktivasi telah dikirim ke email pengguna.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Jika Exception dilempar (karena email fiktif)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat akun: Alamat email tidak dapat dijangkau atau fiktif.'
                ], 400);
            }
            return back()->with('error', 'Gagal membuat akun: Alamat email tidak dapat dijangkau atau fiktif.');
        }
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
        $fileName = 'DATA_PENGGUNA_' . now()->format('d_M_Y_H_i') . '.xlsx';

        // Eksekusi menggunakan class export
        return Excel::download(
            new \App\Exports\UsersExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return Excel::download(new UserTemplateExport, 'Template Import Akun Pengguna.xlsx');
    }

    public function importUsers(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;
        $adminEmail = auth()->user()->email;
        $roleKey = 'HAK AKSES SISTEM (Administrator/Author/Editor/Subscriber/Guest)';

        if (!$data) return back()->with('error', 'Data tidak valid.');

        $roleMapping = RefRole::all()->pluck('id_ref_role', 'slug_role')->toArray();
        $successCount = 0;
        $errorDetails = [];

        try {
            if ($mode === 'replace') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('persons')->join('users', 'users.id_person', '=', 'persons.id_person')
                    ->where('users.email', '!=', $adminEmail)->delete();
                DB::table('users')->where('email', '!=', $adminEmail)->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            foreach ($data as $row) {
                $email = trim($row['EMAIL PENGGUNA']);

                try {
                    DB::transaction(function () use ($row, $roleMapping, $email, $roleKey, &$successCount) {
                        // 1. Validasi DNS tetap dijalankan untuk kebersihan data
                        $v = Validator::make(['email' => $email], ['email' => 'required|email:rfc,dns|unique:users,email']);
                        if ($v->fails()) throw new Exception($v->errors()->first());

                        $roleName = strtolower(trim($row[$roleKey] ?? 'guest'));
                        $roleId = $roleMapping[$roleName] ?? 5;

                        // 2. Buat user dengan password acak
                        $user = User::create([
                            'id_person'   => null,
                            'email'       => $email,
                            'phone'       => trim($row['NOMOR WHATSAPP']),
                            'id_ref_role' => $roleId,
                            'password'    => Hash::make(Str::random(32)), // Password random otomatis
                            'status_user' => 'active',
                            'email_verified_at' => null, // Wajib aktivasi email
                        ]);

                        // 3. Kirim notifikasi aktivasi (Set Password)
                        $token = Password::createToken($user);
                        $user->sendPasswordResetNotification($token);

                        $successCount++;
                    });
                } catch (Exception $e) {
                    $errorDetails[] = "Baris Email $email: " . $e->getMessage();
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => empty($errorDetails) 
                        ? "Berhasil mengimpor $successCount pengguna." 
                        : "Berhasil mengimpor $successCount pengguna dengan beberapa error.",
                    'errorDetails' => $errorDetails
                ]);
            }

            $response = redirect()->route('admin.manage-user.index');
            return empty($errorDetails)
                ? $response->with('success', "Berhasil mengimpor $successCount pengguna.")
                : $response->with('success', "Berhasil mengimpor $successCount pengguna.")->withErrors($errorDetails);
                
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses file import: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal memproses file import: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // KONVERSI 'none' KE null SEBELUM VALIDASI (agar rule 'exists' tidak gagal)
        if ($request->id_work_assignment === 'none') {
            $request->merge(['id_work_assignment' => null]);
        }
        if ($request->id_ref_position === 'none' || $request->id_ref_position === '') {
            $request->merge(['id_ref_position' => null]);
        }

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
            'facebook_url'  => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url'    => 'nullable|url',
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

                // Ambil data person kecuali yang milik table users dan link sosmed
                $data = $request->except(['photo', '_token', '_method', 'email', 'phone', 'id_ref_role', 'facebook_url', 'instagram_url', 'tiktok_url']);

                // KONVERSI sudah dilakukan sebelum validasi via request->merge()
                // Tidak perlu konversi lagi di sini

                if ($request->filled('date_birthday')) {
                    $data['age'] = \Carbon\Carbon::parse($request->date_birthday)->age;
                }

                // --- PERBAIKAN LOGIKA FOTO (HASH FOLDER) ---
                if ($request->hasFile('photo')) {
                    if ($person->photo && Storage::disk('public')->exists($person->photo)) {
                        Storage::disk('public')->delete($person->photo);
                    }

                    // Konsisten menggunakan id_person
                    $folderHash = md5($person->id_person . config('app.key'));

                    $path = $request->file('photo')->store("persons/{$folderHash}/photos", 'public');
                    $data['photo'] = $path;
                }
                // -------------------------------------------

                // Update data person (termasuk id_work_assignment & id_ref_position)
                $person->update($data);

                // --- TRIGGER SINKRONISASI SATU PINTU ---
                // PENTING: refresh() diperlukan agar Eloquent me-reload relasi dari DB
                // (workAssignment, position) setelah update, bukan pakai cache lama
                $person->refresh();
                $person->syncWithUnit();

                // SIMPAN / UPDATE SOSIAL MEDIA
                $person->socialMedia()->updateOrCreate(
                    // Search condition: pastikan relasi polymorphic ini ditemukan
                    ['socialable_id' => $person->id_person, 'socialable_type' => Person::class],
                    // Values to update/create
                    [
                        'facebook_url'  => $request->facebook_url,
                        'instagram_url' => $request->instagram_url,
                        'tiktok_url'    => $request->tiktok_url,
                    ]
                );
            }

            DB::commit();
            return back()->with('success', 'Data pengguna dan plotting penugasan berhasil diperbarui.');
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
                    // --- PERBAIKAN LOGIKA HAPUS FOLDER & FILE ---

                    // 1. Definisikan folder hash (sama dengan logika saat upload)
                    $folderHash = md5($person->id_person . config('app.key'));
                    $directoryPath = "persons/{$folderHash}";

                    // 2. Hapus seluruh direktori person tersebut (termasuk subfolder photos & filenya)
                    if (Storage::disk('public')->exists($directoryPath)) {
                        Storage::disk('public')->deleteDirectory($directoryPath);
                    }

                    // --------------------------------------------

                    // 3. Putus hubungan di table users dulu
                    $user->update(['id_person' => null]);

                    // 4. Hapus Permanen dari table persons
                    $person->forceDelete();
                }
            }

            // 4. Hapus Permanen dari table users
            $user->forceDelete();

            DB::commit();
            return back()->with('success', 'Data pengguna dan profil berhasil dibuang permanen.');
        } catch (Exception $e) {
            DB::rollBack();
            // Log error untuk debug jika diperlukan: \Log::error($e->getMessage());
            return back()->with('error', 'Gagal hapus permanen: ' . $e->getMessage());
        }
    }

    public function forceDeleteAll()
    {
        try {
            DB::beginTransaction();

            // Ambil semua user yang ada di trash beserta data personnya
            $trashedUsers = User::onlyTrashed()->with(['person' => function ($q) {
                $q->withTrashed();
            }])->get();

            foreach ($trashedUsers as $user) {
                if ($user->id_person) {
                    $person = Person::withTrashed()->find($user->id_person);

                    if ($person) {
                        // --- LOGIKA HAPUS FOLDER HASH ---
                        $folderHash = md5($person->id_person . config('app.key'));
                        $directoryPath = "persons/{$folderHash}";

                        // Hapus folder beserta seluruh isinya
                        if (Storage::disk('public')->exists($directoryPath)) {
                            Storage::disk('public')->deleteDirectory($directoryPath);
                        }
                        // -------------------------------

                        // Putus hubungan di table users agar tidak melanggar foreign key
                        $user->update(['id_person' => null]);

                        // Hapus Person secara permanen
                        $person->forceDelete();
                    }
                }
                // Hapus User secara permanen
                $user->forceDelete();
            }

            DB::commit();
            return back()->with('success', 'Tempat sampah berhasil dikosongkan total beserta berkas fisiknya.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengosongkan sampah: ' . $e->getMessage());
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
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', "Gagal memulihkan pengguna.");
        }
    }

    public function approve(Request $request, $id)
    {
        // Saat approve, kita validasi bahwa admin HARUS memilih sesuatu di dropdown
        // Atribut required di Blade akan mencegah pengiriman jika value="", 
        // tapi di server kita pastikan datanya masuk.
        $request->validate([
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role',
            'id_ref_position' => 'required',
            'id_work_assignment' => 'required',
            'facebook_url'  => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url'    => 'nullable|url',
        ]);

        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            // 1. Update status user menjadi active dan set Hak Akses
            $user->update([
                'status_user' => 'active',
                'id_ref_role' => $request->id_ref_role
            ]);

            if ($user->id_person) {
                // 2. Logic khusus: Jika admin memilih "none", simpan sebagai NULL di database.
                // Jika selain "none", simpan ID yang dipilih.
                $user->person->update([
                    'id_ref_position' => $request->id_ref_position === 'none' ? null : $request->id_ref_position,
                    'id_work_assignment' => $request->id_work_assignment === 'none' ? null : $request->id_work_assignment
                ]);

                // --- TRIGGER SINKRONISASI SATU PINTU ---
                $user->person->refresh(); // Reload relasi agar tidak pakai cache lama
                $user->person->syncWithUnit();

                // 3. Update atau buat data Sosial Media
                $user->person->socialMedia()->updateOrCreate(
                    ['socialable_id' => $user->person->id_person, 'socialable_type' => Person::class],
                    [
                        'facebook_url'  => $request->facebook_url,
                        'instagram_url' => $request->instagram_url,
                        'tiktok_url'    => $request->tiktok_url,
                    ]
                );
            }

            DB::commit();
            return back()->with('success', "Akun {$user->email} berhasil diaktifkan dan diverifikasi.");
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function approveAll(Request $request)
    {
        // 1. Validasi input: Admin wajib memilih sesuatu di modal
        $request->validate([
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role',
            'id_ref_position' => 'required',
            'id_work_assignment' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // 2. Ambil semua ID user yang sedang pending
            $pendingUserIds = User::where('status_user', 'pending')->pluck('id_user');

            if ($pendingUserIds->isEmpty()) {
                return back()->with('info', 'Tidak ada pendaftar dalam antrian.');
            }

            // 3. Update tabel users (Status & Role)
            User::whereIn('id_user', $pendingUserIds)->update([
                'status_user' => 'active',
                'id_ref_role'  => $request->id_ref_role,
                'updated_at'   => now()
            ]);

            // 4. Update tabel persons (Penugasan & Jabatan)
            // Terjemahkan 'none' menjadi null
            $positionVal = $request->id_ref_position === 'none' ? null : $request->id_ref_position;
            $assignmentVal = $request->id_work_assignment === 'none' ? null : $request->id_work_assignment;

            // Ambilsemua id_person dari user yang baru saja diupdate
            $personIds = User::whereIn('id_user', $pendingUserIds)
                ->whereNotNull('id_person')
                ->pluck('id_person');

            if ($personIds->isNotEmpty()) {
                Person::whereIn('id_person', $personIds)->update([
                    'id_ref_position'    => $positionVal,
                    'id_work_assignment' => $assignmentVal,
                    'updated_at'         => now()
                ]);
            }

            DB::commit();
            return back()->with('success', count($pendingUserIds) . ' pendaftar berhasil diverifikasi massal.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi massal: ' . $e->getMessage());
        }
    }
}
