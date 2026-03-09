<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SppgOfficer;
use App\Models\SppgUnit;
use App\Models\Person;
use App\Models\RefPosition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\RefRole;
use App\Exports\OfficersExport;
use App\Exports\OfficerTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $id_sppg_unit = $request->query('id_sppg_unit');
        $id_ref_position = $request->query('id_ref_position');
        $is_active = $request->query('is_active');
        $id_ref_role = $request->query('id_ref_role');

        $query = SppgOfficer::has('person')->with(['person.user', 'person.workAssignment', 'sppgUnit', 'position']);

        if ($search) {
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($id_sppg_unit) {
            $query->where('id_sppg_unit', $id_sppg_unit);
        }

        if ($id_ref_position) {
            $query->where('id_ref_position', $id_ref_position);
        }

        if ($is_active !== null && $is_active !== '') {
            $query->where('is_active', $is_active);
        }

        if ($id_ref_role) {
            $query->whereHas('person.user', function ($q) use ($id_ref_role) {
                $q->where('id_ref_role', $id_ref_role);
            });
        }

        $perPage = $request->query('per_page', 5);
        $officers = $query->latest()->paginate($perPage)->withQueryString();

        $sppgUnits = SppgUnit::orderBy('name')->get();
        $positions = RefPosition::whereNotIn('slug_position', ['sppi', 'korwil', 'korcam'])->orderBy('name_position')->get();
        $persons = Person::orderBy('name')->get();
        $roles = RefRole::orderBy('id_ref_role')->get();
        $workAssignments = \App\Models\WorkAssignment::with(['sppgUnit', 'decree.position'])->get();

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
            return view('admin.manage-officer.index', compact('officers', 'sppgUnits', 'positions', 'persons', 'roles', 'workAssignments', 'occupiedPositions'));
        }

        return view('admin.manage-officer.index', compact('officers', 'sppgUnits', 'positions', 'persons', 'roles', 'workAssignments', 'occupiedPositions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:persons,nik',
            'gender' => 'required|in:L,P',
            'id_sppg_unit' => 'required|string',
            'id_ref_position' => 'required|exists:ref_positions,id_ref_position',
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role',
            'phone' => 'required|string|unique:users,phone',
            'daily_honor' => 'nullable|numeric|min:0',
            'date_birthday' => 'required|date',
            'place_birthday' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'marital_status' => 'required|string|max:255',
            'no_kk' => 'required|string|size:16',
            'no_bpjs_kes' => 'nullable|string|max:255',
            'no_bpjs_tk' => 'nullable|string|max:255',
            
            // Field Alamat KTP
            'province_ktp' => 'required|string',
            'regency_ktp' => 'required|string',
            'district_ktp' => 'required|string',
            'village_ktp' => 'required|string',
            'address_ktp' => 'required|string',

            // Field Alamat Domisili
            'province_domicile' => 'required|string',
            'regency_domicile' => 'required|string',
            'district_domicile' => 'required|string',
            'village_domicile' => 'required|string',
            'address_domicile' => 'required|string',
            'id_work_assignment' => 'nullable|string', // Validasi manual di bawah jika perlu, atau handle 'none'
        ];

        $messages = [
            'required' => 'Wajib diisi',
            'string' => 'Harus berupa teks',
            'max' => 'Maksimal :max karakter',
            'numeric' => 'Harus berupa angka',
            'min' => 'Minimal :min',
            'date' => 'Format tanggal tidak valid',
            'unique' => 'Data sudah digunakan',
            'exists' => 'Data tidak ditemukan',
            'size' => 'Harus :size karakter',
            'in' => 'Pilihan tidak valid',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $position = \App\Models\RefPosition::find($request->id_ref_position);
        $posSlug  = $position ? $position->slug_position : null;
        $isCore   = $posSlug && in_array($posSlug, ['kasppg', 'ag', 'ak', 'kasppg-pengganti']);

        $assignedUnitId = $request->input('id_sppg_unit', 'none');
        
        if ($isCore && $request->id_work_assignment && $request->id_work_assignment !== 'none') {
            $wa = \App\Models\WorkAssignment::where('id_work_assignment', $request->id_work_assignment)->first();
            $assignedUnitId = $wa ? $wa->id_sppg_unit : null;
        } else if (($assignedUnitId === 'none' || !$assignedUnitId) && $request->id_work_assignment && $request->id_work_assignment !== 'none') {
            $wa = \App\Models\WorkAssignment::where('id_work_assignment', $request->id_work_assignment)->first();
            $assignedUnitId = $wa ? $wa->id_sppg_unit : null;
        }

        $mapping = [
            'kasppg' => 'leader_id',
            'ag'     => 'nutritionist_id',
            'ak'     => 'accountant_id',
        ];

        if ($posSlug && isset($mapping[$posSlug]) && $assignedUnitId) {
            $targetUnit = \App\Models\SppgUnit::find($assignedUnitId);
            if ($targetUnit) {
                $columnName = $mapping[$posSlug];
                
                // Jika slot terisi, tolak (karena tambah baru, posisi sudah fix diisi orang lain)
                if ($targetUnit->{$columnName}) {
                    $errorMessage = 'Unit ini sudah memiliki ' . strtoupper($posSlug) . ' aktif.';
                    if ($request->ajax()) {
                        return response()->json(['errors' => ['id_sppg_unit' => [$errorMessage]]], 422);
                    }
                    return back()->withErrors(['id_sppg_unit' => $errorMessage])->withInput();
                }
            }
        }

        try {
            DB::beginTransaction();

            // 1. Create Person (Profil) — hanya field dari modal
            $person = Person::create([
                'nik'          => $request->nik,
                'no_kk'        => $request->no_kk ?? null,
                'name'         => $request->name,
                'gender'       => $request->gender,
                'religion'     => $request->religion,
                'marital_status' => $request->marital_status,
                'no_bpjs_kes'  => $request->no_bpjs_kes ?? null,
                'no_bpjs_tk'   => $request->no_bpjs_tk ?? null,
                'place_birthday' => $request->place_birthday,
                'date_birthday' => $request->date_birthday,
                'age'          => \Carbon\Carbon::parse($request->date_birthday)->age,
                'province_ktp' => $request->province_ktp,
                'regency_ktp'  => $request->regency_ktp,
                'district_ktp' => $request->district_ktp,
                'village_ktp'  => $request->village_ktp,
                'address_ktp'  => $request->address_ktp,
                'province_domicile' => $request->province_domicile,
                'regency_domicile'  => $request->regency_domicile,
                'district_domicile' => $request->district_domicile,
                'village_domicile'  => $request->village_domicile,
                'address_domicile'  => $request->address_domicile,
                // Sinkronisasi jabatan & penugasan
                'id_ref_position' => $request->id_ref_position,
                'id_work_assignment' => $request->id_work_assignment !== 'none' ? $request->id_work_assignment : null,
            ]);

            // 2. Create User (Akun) — gunakan role dari input
            User::create([
                'id_person'    => $person->id_person,
                'id_ref_role'  => $request->id_ref_role,
                'phone'        => $request->phone,
                'email'        => null,
                'password'     => null,
                'status_user'  => 'active',
            ]);

            // id_sppg_unit sudah di-resolve di atas (assignedUnitId)
            SppgOfficer::create([
                'id_person'        => $person->id_person,
                'id_sppg_unit'     => $assignedUnitId,
                'id_ref_position'  => $request->id_ref_position,
                'daily_honor'      => $request->filled('daily_honor') ? $request->daily_honor : 0,
                'is_active'        => true,
            ]);

            // Tambahkan ini agar SppgUnit tersinkron jika posisi core/relawan
            $person->refresh();
            $person->syncWithUnit($assignedUnitId && $assignedUnitId !== 'none' ? $assignedUnitId : null);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.manage-officer.index')]);
            }

            return redirect()->route('admin.manage-officer.index')->with('success', 'Petugas baru berhasil ditambahkan dan akun otomatis telah dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing officer: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data petugas: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $officer = SppgOfficer::findOrFail($id);

        $messages = [
            'required' => 'Wajib diisi',
            'string' => 'Harus berupa teks',
            'max' => 'Maksimal :max karakter',
            'numeric' => 'Harus berupa angka',
            'min' => 'Minimal :min',
            'date' => 'Format tanggal tidak valid',
            'unique' => 'Data sudah digunakan',
            'exists' => 'Data tidak ditemukan',
            'size' => 'Harus :size karakter',
            'in' => 'Pilihan tidak valid',
        ];

        $validator = Validator::make($request->all(), [
            'id_person' => 'required|exists:persons,id_person',
            'id_sppg_unit' => 'required|string', // Allow 'none' for core
            'id_ref_position' => 'required|exists:ref_positions,id_ref_position',
            'id_ref_role' => 'required|exists:ref_roles,id_ref_role',
            'is_active' => 'required|boolean',
            'daily_honor' => 'nullable|numeric|min:0',
            
            'name_person' => 'required|string|max:255',
            'nik_person' => 'required|string|size:16|unique:persons,nik,' . $request->id_person . ',id_person',
            'phone_person' => 'required|string|unique:users,phone,' . ($officer->person->user->id_user ?? 0) . ',id_user',
            'gender_person' => 'required|in:L,P',
            'date_birthday_person' => 'required|date',
            'place_birthday_person' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'marital_status' => 'required|string|max:255',
            'no_kk' => 'required|string|size:16',
            'no_bpjs_kes' => 'nullable|string|max:255',
            'no_bpjs_tk' => 'nullable|string|max:255',
            
            // Alamat KTP
            'province_ktp' => 'required|string',
            'regency_ktp' => 'required|string',
            'district_ktp' => 'required|string',
            'village_ktp' => 'required|string',
            'address_ktp' => 'required|string',

            // Alamat Domisili
            'province_domicile' => 'required|string',
            'regency_domicile' => 'required|string',
            'district_domicile' => 'required|string',
            'village_domicile' => 'required|string',
            'address_domicile' => 'required|string',
            'id_work_assignment' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {
             if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $position = \App\Models\RefPosition::find($request->id_ref_position);
        $posSlug  = $position ? $position->slug_position : null;
        $isCore   = $posSlug && in_array($posSlug, ['kasppg', 'ag', 'ak', 'kasppg-pengganti']);

        $assignedUnitId = $request->input('id_sppg_unit', 'none');

        if ($isCore && $request->id_work_assignment && $request->id_work_assignment !== 'none') {
            $wa = \App\Models\WorkAssignment::where('id_work_assignment', $request->id_work_assignment)->first();
            $assignedUnitId = $wa ? $wa->id_sppg_unit : null;
        } else if (($assignedUnitId === 'none' || !$assignedUnitId) && $request->id_work_assignment && $request->id_work_assignment !== 'none') {
            $wa = \App\Models\WorkAssignment::where('id_work_assignment', $request->id_work_assignment)->first();
            $assignedUnitId = $wa ? $wa->id_sppg_unit : null;
        }

        $mapping = [
            'kasppg' => 'leader_id',
            'ag'     => 'nutritionist_id',
            'ak'     => 'accountant_id',
        ];

        if ($posSlug && isset($mapping[$posSlug]) && $assignedUnitId) {
            $targetUnit = \App\Models\SppgUnit::find($assignedUnitId);
            if ($targetUnit) {
                $columnName = $mapping[$posSlug];
                $existingPersonId = $targetUnit->{$columnName};
                
                // Jika slot terisi dan bukan orang ini sendiri, tolak
                if ($existingPersonId && $existingPersonId != $officer->id_person) {
                    $errorMessage = 'Unit ini sudah memiliki ' . strtoupper($posSlug) . ' aktif.';
                    if ($request->ajax()) {
                        return response()->json(['errors' => ['id_sppg_unit' => [$errorMessage]]], 422);
                    }
                    return back()->withErrors(['id_sppg_unit' => $errorMessage])->withInput();
                }
            }
        }

        try {
            // id_sppg_unit sudah di-resolve di atas (assignedUnitId)
            // Pastikan kita gunakan assignedUnitId jika ada (bisa null/none jika dihapus, tapi jangan fallback ke yang lama jika kita sengaja ubah)
            $newUnitId = ($assignedUnitId === 'none') ? null : $assignedUnitId;

            $officer->update([
                'id_person' => $request->id_person,
                'id_sppg_unit' => $newUnitId,
                'id_ref_position' => $request->id_ref_position,
                'is_active' => $request->is_active,
                'daily_honor' => $request->daily_honor,
            ]);

            // UPDATE DATA PROFIL PERSON
            $person = Person::findOrFail($request->id_person);
            $person->update([
                'name' => $request->name_person, // Kita perlu kirim nama juga di modal edit
                'nik' => $request->nik_person,
                'no_kk' => $request->no_kk ?? null,
                'gender' => $request->gender_person,
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
                'place_birthday' => $request->place_birthday_person,
                'date_birthday' => $request->date_birthday_person,
                'province_ktp' => $request->province_ktp,
                'regency_ktp' => $request->regency_ktp,
                'district_ktp' => $request->district_ktp,
                'village_ktp' => $request->village_ktp,
                'address_ktp' => $request->address_ktp,
                'province_domicile' => $request->province_domicile,
                'regency_domicile' => $request->regency_domicile,
                'district_domicile' => $request->district_domicile,
                'village_domicile' => $request->village_domicile,
                'address_domicile' => $request->address_domicile,
                'no_bpjs_kes' => $request->no_bpjs_kes ?? null,
                'no_bpjs_tk' => $request->no_bpjs_tk ?? null,
                'id_ref_position' => $request->id_ref_position,
                'id_work_assignment' => $request->id_work_assignment !== 'none' ? $request->id_work_assignment : null,
            ]);

            // Sinkronisasi data unit & officer via syncWithUnit (dua arah)
            $person->refresh();
            $person->syncWithUnit($newUnitId);

            // Update User (Akun) — phone dan role
            if ($person->user) {
                $person->user->update([
                    'name'        => $request->name_person,
                    'phone'       => $request->phone_person,
                    'id_ref_role' => $request->id_ref_role,
                ]);
            }

            if ($request->ajax()) {
                 return response()->json(['success' => true, 'redirect' => route('admin.manage-officer.index')]);
            }
            return redirect()->route('admin.manage-officer.index')->with('success', 'Data Petugas Berhasil Diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Gagal memperbarui petugas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $officer = SppgOfficer::findOrFail($id);
            $person = $officer->person;
            $user = $person?->user;

            // Pastikan tidak menghapus diri sendiri jika admin tersebut juga terdaftar sebagai officer
            if ($user && $user->id_user === \Illuminate\Support\Facades\Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak bisa menghapus diri sendiri.');
            }

            // Matikan sementara foreign key check untuk kelancaran pembersihan permanen
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Hapus record penugasan
            $officer->delete();

            // 2. Hapus PERMANEN (Force Delete) User & Person agar NIK/Phone kembali tersedia
            if ($user) $user->forceDelete();
            if ($person) $person->forceDelete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();
            return redirect()->route('admin.manage-officer.index')->with('success', 'Data petugas, akun, dan profil berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting officer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus petugas: ' . $e->getMessage());
        }
    }

    public function importFromSppg()
    {
        $posKasppg = \App\Models\RefPosition::where('slug_position', 'kasppg')->first();
        $posAG = \App\Models\RefPosition::where('slug_position', 'ag')->first();
        $posAK = \App\Models\RefPosition::where('slug_position', 'ak')->first();

        if (!$posKasppg || !$posAG || !$posAK) {
            return redirect()->back()->with('error', 'Jabatan Dasar (KaSPPG/AG/AK) tidak ditemukan. Pastikan Master Jabatan lengkap.');
        }

        $units = \App\Models\SppgUnit::all();
        $count = 0;

        foreach ($units as $unit) {
            if ($unit->leader_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->leader_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posKasppg->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }
            if ($unit->nutritionist_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->nutritionist_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posAG->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }
            if ($unit->accountant_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->accountant_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posAK->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }
        }

        return redirect()->route('admin.manage-officer.index')->with('success', "Berhasil sinkronisasi {$count} data petugas dari unit SPPG.");
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'columns' => 'required|array|min:1'
        ], [
            'columns.required' => 'Pilih minimal satu kolom data yang ingin diekspor!'
        ]);

        $selectedColumns = $request->input('columns');
        $fileName = 'DATA PETUGAS ' . now()->format('His-dmY') . '.xlsx';

        return Excel::download(new OfficersExport($selectedColumns), $fileName);
    }

    public function exportTemplate()
    {
        $positions = RefPosition::orderBy('id_ref_position')->pluck('name_position')->toArray();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OfficerTemplateExport($positions), 'TEMPLATE IMPORT PETUGAS.xlsx');
    }

    public function checkAvailability(Request $request)
    {
        $nik = $request->query('nik');
        $phone = $request->query('phone');

        $nikExists = Person::where('nik', $nik)->exists();
        $phoneExists = User::where('phone', $phone)->exists();

        // Validasi jabatan & unit
        $roles = RefRole::pluck('name_role')->map(fn($r) => strtolower($r))->toArray();
        $positions = RefPosition::pluck('name_position')->toArray();
        $units = SppgUnit::pluck('name')->toArray();

        return response()->json([
            'nik_duplicate' => $nikExists,
            'phone_duplicate' => $phoneExists,
            'valid_roles' => $roles,
            'valid_positions' => $positions,
            'valid_units' => $units
        ]);
    }

    public function importOfficers(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;
        
        if (!$data) return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 422);

        $roleMapping = RefRole::all()->pluck('id_ref_role', 'name_role')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        })->toArray();
        
        $posMapping = RefPosition::all()->pluck('id_ref_position', 'name_position')->toArray();
        $unitMapping = SppgUnit::all()->pluck('id_sppg_unit', 'name')->toArray();

        $successCount = 0;
        $errorDetails = [];
        $seenNiks = [];
        $seenPhones = [];

        try {
            DB::beginTransaction();

            if ($mode === 'replace') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                
                // Ambil semua data petugas yang ada
                $officersToDelete = SppgOfficer::all();
                foreach($officersToDelete as $off) {
                    $p = $off->person; // Mengambil model Person (inklusif SoftDeleted jika relasi diatur benar, tapi di sini SppgOfficer hanya link ke active person biasanya)
                    $u = $p?->user;
                    
                    // JANGAN hapus diri sendiri (Admin yang sedang login)
                    if ($u && $u->id_user === \Illuminate\Support\Facades\Auth::id()) continue;

                    // Hapus permanen data petugas
                    $off->delete(); // SppgOfficer biasanya tidak pakai SoftDeletes, jika pakai gunakan forceDelete()
                    
                    // Hapus permanen User & Person agar tidak bentrok NIK/Phone saat re-import
                    // Gunakan forceDelete() karena User & Person menggunakan SoftDeletes
                    if ($u) $u->forceDelete();
                    if ($p) $p->forceDelete();
                }
                
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            foreach ($data as $index => $row) {
                // Determine header keys
                $nikKey = isset($row['NIK']) ? 'NIK' : 'NIK (16 Digit)';
                $kkKey = isset($row['NO. KK']) ? 'NO. KK' : 'NO. KARTU KELUARGA (16 Digit)';
                $phoneKey = isset($row['TELEPON']) ? 'TELEPON' : (isset($row['NOMOR WHATSAPP']) ? 'NOMOR WHATSAPP' : 'TELEPON');
                
                $religionKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'AGAMA'));
                $maritalKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'STATUS PERNIKAHAN'));
                $posKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'JABATAN'));
                $roleKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'HAK AKSES SISTEM'));
                $birthKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'TGL LAHIR') || str_starts_with($k, 'TANGGAL LAHIR'));
                $activeKey = collect(array_keys($row))->first(fn($k) => str_starts_with($k, 'STATUS KEAKTIFAN'));
                $honorKey = 'HONOR PER HARI';

                $nik = trim($row[$nikKey] ?? '');
                $phone = trim($row[$phoneKey] ?? '');
                $name = trim($row['NAMA LENGKAP'] ?? '');
                
                $rowErrors = [];

                if (!$nik) $rowErrors[] = "NIK Kosong";
                if (!$phone) $rowErrors[] = "Telepon Kosong";
                if (!$name) $rowErrors[] = "Nama Kosong";

                // 1. Cross-row uniqueness
                if ($nik) {
                    if (in_array($nik, $seenNiks)) $rowErrors[] = "NIK $nik ganda di file";
                    $seenNiks[] = $nik;
                }
                if ($phone) {
                    if (in_array($phone, $seenPhones)) $rowErrors[] = "Telepon $phone ganda di file";
                    $seenPhones[] = $phone;
                }

                // 2. DB Uniqueness (if append)
                if ($mode === 'append') {
                    if ($nik && Person::where('nik', $nik)->exists()) $rowErrors[] = "NIK $nik sudah ada di database";
                    if ($phone && User::where('phone', $phone)->exists()) $rowErrors[] = "Telepon $phone sudah ada di database";
                }

                // 3. Format Validation
                $birthVal = trim($row[$birthKey] ?? '');
                $birthDate = null;
                if ($birthVal) {
                    try {
                        $birthDate = \Carbon\Carbon::createFromFormat('d-m-Y', $birthVal);
                    } catch (\Exception $e) {
                        $rowErrors[] = "Format Tanggal Lahir harus DD-MM-YYYY ($birthVal)";
                    }
                }

                $honorVal = trim($row[$honorKey] ?? '0');
                if ($honorVal !== '0' && !preg_match('/^\d+$/', $honorVal)) {
                    $rowErrors[] = "Honorarium harus angka saja ($honorVal)";
                }

                // 4. Master Data Mapping
                $roleNameText = strtolower(trim($row[$roleKey] ?? ''));
                $roleId = $roleMapping[$roleNameText] ?? null;
                if (!$roleId) $rowErrors[] = "Role \"$roleNameText\" tidak valid";

                $posName = trim($row[$posKey] ?? '');
                $posId = $posMapping[$posName] ?? null;
                if (!$posId) $rowErrors[] = "Jabatan \"$posName\" tidak valid";

                $unitName = trim($row['UNIT SPPG'] ?? '');
                $unitId = $unitMapping[$unitName] ?? null;
                if (!$unitId) $rowErrors[] = "Unit SPPG \"$unitName\" tidak valid";

                if (!empty($rowErrors)) {
                    $errorDetails[] = "Baris " . ($index + 1) . " (" . ($name ?: $nik ?: 'Data Kosong') . "): " . implode(', ', $rowErrors);
                    continue;
                }

                try {
                    // Create/Update Person
                    $person = Person::updateOrCreate(
                        ['nik' => $nik],
                        [
                            'name'         => $name,
                            'no_kk'        => $row[$kkKey] ?? null,
                            'gender'       => strtoupper(trim($row['JENIS KELAMIN (L/P)'] ?? 'L')),
                            'place_birthday' => $row['TEMPAT LAHIR'],
                            'date_birthday' => $birthDate ? $birthDate->format('Y-m-d') : null,
                            'age'          => $birthDate ? $birthDate->age : null,
                            'religion'     => $row[$religionKey] ?? null,
                            'marital_status' => $row[$maritalKey] ?? null,
                            'no_bpjs_kes'  => $row['NO. BPJS KESEHATAN'] ?? null,
                            'no_bpjs_tk'   => $row['NO. BPJS KETENAGAKERJAAN'] ?? null,
                            'id_ref_position' => $posId,
                            'province_ktp' => $row['PROVINSI (KTP)'] ?? null,
                            'regency_ktp'  => $row['KABUPATEN (KTP)'] ?? null,
                            'district_ktp' => $row['KECAMATAN (KTP)'] ?? null,
                            'village_ktp'  => $row['DESA/KELURAHAN (KTP)'] ?? null,
                            'address_ktp'  => $row['ALAMAT JALAN (KTP)'] ?? null,
                            'province_domicile' => $row['PROVINSI (DOMISILI)'] ?? null,
                            'regency_domicile'  => $row['KABUPATEN (DOMISILI)'] ?? null,
                            'district_domicile' => $row['KECAMATAN (DOMISILI)'] ?? null,
                            'village_domicile'  => $row['DESA/KELURAHAN (DOMISILI)'] ?? null,
                            'address_domicile'  => $row['ALAMAT JALAN (DOMISILI)'] ?? null,
                        ]
                    );

                    // Create/Update User
                    User::updateOrCreate(
                        ['id_person' => $person->id_person],
                        [
                            'id_ref_role' => $roleId,
                            'phone'       => $phone,
                            'status_user' => 'active',
                        ]
                    );

                    // Determine Keaktifan
                    $isActive = true;
                    if ($activeKey && isset($row[$activeKey])) {
                        $st = strtolower(trim($row[$activeKey]));
                        $isActive = ($st === 'aktif' || $st === '1' || $st === 'yes');
                    }

                    // Create/Update Officer
                    SppgOfficer::updateOrCreate(
                        ['id_person' => $person->id_person],
                        [
                            'id_sppg_unit'    => $unitId,
                            'id_ref_position' => $posId,
                            'daily_honor'     => (int)$honorVal,
                            'is_active'       => $isActive,
                        ]
                    );

                    $person->refresh();
                    $person->syncWithUnit();
                    $successCount++;

                } catch (\Exception $e) {
                    $errorDetails[] = "Baris " . ($index + 1) . " (NIK $nik): Hubungi Admin - " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => empty($errorDetails) && $successCount > 0,
                'message' => empty($errorDetails) 
                    ? "Berhasil mengimpor $successCount petugas." 
                    : "Berhasil mengimpor $successCount petugas dengan beberapa catatan.",
                'errorDetails' => $errorDetails
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengimpor: ' . $e->getMessage()], 500);
        }
    }
}
