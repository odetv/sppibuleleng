<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentDecree;
use App\Models\SppgUnit;
use App\Models\Person;
use App\Models\SocialMedia;
use App\Models\WorkAssignment;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SppgUnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $decreeId = $request->query('id_assignment_decree');
        $province = $request->query('province');
        $regency = $request->query('regency');
        $district = $request->query('district');
        $village = $request->query('village');

        // Ambil data unit dengan eager loading relasi
        $query = SppgUnit::with(['leader', 'nutritionist', 'accountant', 'socialMedia', 'workAssignments.decree', 'beneficiaries', 'suppliers']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_sppg_unit', 'like', "%{$search}%")
                  ->orWhere('code_sppg_unit', 'like', "%{$search}%")
                  ->orWhereHas('leader', function ($ql) use ($search) {
                      $ql->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($status) $query->where('status', $status);
        if ($decreeId) {
            if ($decreeId === 'none') {
                $query->whereDoesntHave('workAssignments');
            } else {
                $query->whereHas('workAssignments', function ($qw) use ($decreeId) {
                    $qw->where('id_assignment_decree', $decreeId);
                });
            }
        }
        if ($province) $query->where('province', $province);
        if ($regency) $query->where('regency', $regency);
        if ($district) $query->where('district', $district);
        if ($village) $query->where('village', $village);

        $perPage = $request->query('per_page', 5);
        $units = $query->latest()->paginate($perPage)->withQueryString();

        // Data for Filters & Modals
        $leaders = Person::whereHas('position', fn($q) => $q->where('slug_position', 'kasppg'))->orderBy('name')->get();
        $nutritionists = Person::whereHas('position', fn($q) => $q->where('slug_position', 'ag'))->orderBy('name')->get();
        $accountants = Person::whereHas('position', fn($q) => $q->where('slug_position', 'ak'))->orderBy('name')->get();

        $occupiedPeople = [
            'kasppg' => SppgUnit::whereNotNull('leader_id')->pluck('leader_id')->toArray(),
            'ag'     => SppgUnit::whereNotNull('nutritionist_id')->pluck('nutritionist_id')->toArray(),
            'ak'     => SppgUnit::whereNotNull('accountant_id')->pluck('accountant_id')->toArray(),
        ];

        $decrees = AssignmentDecree::orderBy('date_sk', 'desc')->get()->groupBy('type_sk');
        
        $assignedDecreeMap = WorkAssignment::join('assignment_decrees', 'work_assignments.id_assignment_decree', '=', 'assignment_decrees.id_assignment_decree')
            ->select('work_assignments.id_sppg_unit', 'work_assignments.id_assignment_decree', 'assignment_decrees.type_sk')
            ->get()
            ->groupBy('id_sppg_unit')
            ->map(function($items) {
                return $items->pluck('id_assignment_decree', 'type_sk')->toArray();
            })
            ->toArray();
        $allBeneficiaries = Beneficiary::orderBy('name')->get();
        $allSuppliers = \App\Models\Supplier::orderBy('name_supplier')->get();
        $supplierTypes = [
            'Koperasi Desa Merah Putih',
            'Koperasi',
            'Bumdes',
            'Bumdesma',
            'UMKM',
            'Supplier Lain'
        ];

        // Unique address data for filters
        $filterData = [
            'provinces' => SppgUnit::whereNotNull('province')->distinct()->pluck('province')->sort(),
            'regencies' => $province ? SppgUnit::where('province', $province)->whereNotNull('regency')->distinct()->pluck('regency')->sort() : [],
            'districts' => $regency ? SppgUnit::where('regency', $regency)->whereNotNull('district')->distinct()->pluck('district')->sort() : [],
            'villages' => $district ? SppgUnit::where('district', $district)->whereNotNull('village')->distinct()->pluck('village')->sort() : [],
        ];

        $sppgUnits = SppgUnit::orderBy('name')->get(['id_sppg_unit', 'name']);

        if ($request->ajax()) {
            return view('admin.manage-sppg.index', compact('units', 'leaders', 'nutritionists', 'accountants', 'occupiedPeople', 'decrees', 'assignedDecreeMap', 'allBeneficiaries', 'allSuppliers', 'supplierTypes', 'filterData', 'sppgUnits'))->fragment('sppg-table-container');
        }

        return view('admin.manage-sppg.index', compact('units', 'leaders', 'nutritionists', 'accountants', 'occupiedPeople', 'decrees', 'assignedDecreeMap', 'allBeneficiaries', 'allSuppliers', 'supplierTypes', 'filterData', 'sppgUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Gunakan Validator Manual agar bisa kontrol response AJAX
        $validator = \Validator::make($request->all(), [
            'id_sppg_unit'          => 'required|string|unique:sppg_units,id_sppg_unit',
            'code_sppg_unit'        => 'nullable|string|unique:sppg_units,code_sppg_unit',
            'name'                  => 'required|string|max:255',
            'status'                => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'      => 'nullable|date',
            'province_name'         => 'required|string',
            'regency_name'          => 'required|string',
            'district_name'         => 'required|string',
            'village_name'          => 'required|string',
            'latitude_gps'          => 'required',
            'longitude_gps'         => 'required',
            'leader_id'             => 'required|string',
            'photo'                 => 'required|image|max:2048',
            'id_sk_leader'          => 'required|exists:assignment_decrees,id_assignment_decree',
            'id_sk_nutritionist'    => 'nullable|exists:assignment_decrees,id_assignment_decree',
            'id_sk_accountant'      => 'nullable|exists:assignment_decrees,id_assignment_decree',
            'facebook_url'          => 'nullable|url',
            'instagram_url'         => 'nullable|url',
            'tiktok_url'            => 'nullable|url',
        ], [
            'id_sk_leader.required' => 'SK Kepala SPPG wajib dipilih.',
            'id_sk_leader.exists'   => 'SK Kepala SPPG tidak valid.',
        ]);

        // 2. Jika Validasi Gagal (Termasuk Duplicate ID/Code)
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            $folderHash = md5($request->id_sppg_unit . config('app.key'));
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            }

            $unit = SppgUnit::create([
                'id_sppg_unit'    => $data['id_sppg_unit'],
                'code_sppg_unit'  => $data['code_sppg_unit'],
                'name'            => $data['name'],
                'status'          => $data['status'],
                'operational_date'=> $data['operational_date'] ?? null,
                'province'        => $data['province_name'],
                'regency'         => $data['regency_name'],
                'district'        => $data['district_name'],
                'village'         => $data['village_name'],
                'address'         => $data['address'],
                'latitude_gps'    => $data['latitude_gps'],
                'longitude_gps'   => $data['longitude_gps'],
                'leader_id'       => $data['leader_id'] === 'NULL' ? null : $data['leader_id'],
                'nutritionist_id' => (empty($data['nutritionist_id']) || $data['nutritionist_id'] === 'NULL') ? null : $data['nutritionist_id'],
                'accountant_id'   => (empty($data['accountant_id']) || $data['accountant_id'] === 'NULL') ? null : $data['accountant_id'],
                'photo'           => $data['photo'] ?? null,
            ]);

            // Buat WorkAssignment untuk setiap SK yang dipilih
            $decreeInputs = [
                'id_sk_leader',
                'id_sk_nutritionist',
                'id_sk_accountant'
            ];

            foreach ($decreeInputs as $input) {
                if ($request->filled($input)) {
                    WorkAssignment::create([
                        'id_assignment_decree' => $request->{$input},
                        'id_sppg_unit'         => $unit->id_sppg_unit,
                    ]);
                }
            }

            $unit->syncPersonnel();
            $unit->suppliers()->sync($request->supplier_ids);

            // Tautkan Penerima Manfaat (PM) jika ada yang dipilih saat pembuatan
            if ($request->filled('beneficiary_ids')) {
                \App\Models\Beneficiary::whereIn('id_beneficiary', $request->beneficiary_ids)
                    ->update(['id_sppg_unit' => $unit->id_sppg_unit]);
            }

            // Simpan Sosial Media
            $unit->socialMedia()->updateOrCreate(
                ['socialable_id' => $unit->id_sppg_unit, 'socialable_type' => SppgUnit::class],
                [
                    'facebook_url'  => $request->facebook_url  ?: null,
                    'instagram_url' => $request->instagram_url ?: null,
                    'tiktok_url'    => $request->tiktok_url    ?: null,
                ]
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.manage-sppg.index')]);
            }

            return redirect()->route('admin.manage-sppg.index')->with('success', 'Unit Berhasil Ditambah');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cari menggunakan ID Primary Key (id_sppg_unit)
        $sppg = SppgUnit::where('id_sppg_unit', $id)->firstOrFail();

        $validator = \Validator::make($request->all(), [
            'id_sppg_unit'          => 'required|string|unique:sppg_units,id_sppg_unit,' . $id . ',id_sppg_unit',
            'code_sppg_unit'        => 'nullable|string|unique:sppg_units,code_sppg_unit,' . $id . ',id_sppg_unit',
            'name'                  => 'required|string|max:255',
            'status'                => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'      => 'nullable|date',
            'province_name'         => 'required|string',
            'regency_name'          => 'required|string',
            'district_name'         => 'required|string',
            'village_name'          => 'required|string',
            'latitude_gps'          => 'required',
            'longitude_gps'         => 'required',
            'photo'                 => 'nullable|image|max:2048',
            'id_sk_leader'          => 'required|exists:assignment_decrees,id_assignment_decree',
            'id_sk_nutritionist'    => 'nullable|exists:assignment_decrees,id_assignment_decree',
            'id_sk_accountant'      => 'nullable|exists:assignment_decrees,id_assignment_decree',
            'facebook_url'          => 'nullable|url',
            'instagram_url'         => 'nullable|url',
            'tiktok_url'            => 'nullable|url',
        ], [
            'id_sk_leader.required' => 'SK Kepala SPPG wajib dipilih.',
            'id_sk_leader.exists'   => 'SK Kepala SPPG tidak valid.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $oldId = $sppg->id_sppg_unit;

            // Foto handling jika ada
            $photoPath = $sppg->photo;
            if ($request->hasFile('photo')) {
                if ($sppg->photo) {
                    Storage::disk('public')->delete($sppg->photo);
                }
                $folderHash = md5($request->id_sppg_unit . config('app.key'));
                $photoPath = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            }

            // Update manual mem-bypass batasan PK eloquent
            DB::table('sppg_units')->where('id_sppg_unit', $oldId)->update([
                'id_sppg_unit'    => $request->id_sppg_unit,
                'code_sppg_unit'  => $request->code_sppg_unit,
                'name'            => $request->name,
                'status'          => $request->status,
                'operational_date'=> $request->operational_date,
                'province'        => $request->province_name,
                'regency'         => $request->regency_name,
                'district'        => $request->district_name,
                'village'         => $request->village_name,
                'address'         => $request->address,
                'latitude_gps'    => $request->latitude_gps,
                'longitude_gps'   => $request->longitude_gps,
                'leader_id'       => $request->leader_id === 'NULL' ? null : $request->leader_id,
                'nutritionist_id' => (empty($request->nutritionist_id) || $request->nutritionist_id === 'NULL') ? null : $request->nutritionist_id,
                'accountant_id'   => (empty($request->accountant_id) || $request->accountant_id === 'NULL') ? null : $request->accountant_id,
                'photo'           => $photoPath,
                'updated_at'      => now(),
            ]);

            $sppg->refresh();

            // Update WorkAssignments
            // Hapus WA lama unit ini, lalu buat ulang berdasarkan pilihan 3 SK
            WorkAssignment::where('id_sppg_unit', $request->id_sppg_unit)->delete();

            $decreeInputs = [
                'id_sk_leader',
                'id_sk_nutritionist',
                'id_sk_accountant'
            ];

            foreach ($decreeInputs as $input) {
                if ($request->filled($input)) {
                    WorkAssignment::create([
                        'id_assignment_decree' => $request->{$input},
                        'id_sppg_unit'         => $request->id_sppg_unit,
                    ]);
                }
            }

            $sppg->syncPersonnel();
            $sppg->suppliers()->sync($request->supplier_ids);

            // Update Sosial Media: Cari menggunakan ID Lama, lalu update ke ID Baru
            SocialMedia::updateOrCreate(
                ['socialable_id' => $oldId, 'socialable_type' => SppgUnit::class],
                [
                    'socialable_id' => $request->id_sppg_unit,
                    'facebook_url'  => $request->facebook_url  ?: null,
                    'instagram_url' => $request->instagram_url ?: null,
                    'tiktok_url'    => $request->tiktok_url    ?: null,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'redirect' => route('admin.manage-sppg.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $sppg = SppgUnit::findOrFail($id);

            if ($sppg->photo) {
                $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
                Storage::disk('public')->deleteDirectory("sppgunits/{$folderHash}");
            }

            $sppg->socialMedia()->delete();
            $sppg->delete();

            DB::commit();
            return redirect()->route('admin.manage-sppg.index')->with('success', 'Unit berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus unit: ' . $e->getMessage());
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
        $fileName = 'DATA SPPG ' . now()->format('His-dmY') . '.xlsx';

        // Eksekusi menggunakan class export
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SppgExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SppgTemplateExport, 'TEMPLATE IMPORT SPPG.xlsx');
    }

    public function checkAvailability(Request $request)
    {
        $id_sppg = $request->query('id_sppg');
        $code = $request->query('code');

        return response()->json([
            'id_duplicate' => $id_sppg ? \App\Models\SppgUnit::where('id_sppg_unit', $id_sppg)->exists() : false,
            'code_duplicate' => $code ? \App\Models\SppgUnit::where('code_sppg_unit', $code)->exists() : false,
        ]);
    }

    public function importSppg(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;

        if (!$data) return back()->with('error', 'Data tidak valid.');

        $successCount = 0;
        $errorDetails = [];
        
        $processedIds = [];
        $processedCodes = [];

        try {
            DB::beginTransaction();

            if ($mode === 'replace') {
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                // Hapus foto jika ada
                $allSppg = \App\Models\SppgUnit::all();
                foreach($allSppg as $sppg) {
                     if ($sppg->photo) {
                        $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
                        \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory("sppgunits/{$folderHash}");
                    }
                }
                
                // Kosongkan penugasan di sisi pengguna
                \Illuminate\Support\Facades\DB::table('persons')->update(['id_work_assignment' => null]);
                // Hapus seluruh Work Assignments karena SPPG akan hangus
                \Illuminate\Support\Facades\DB::table('work_assignments')->delete();
                // Eksekusi hapus bersih SPPG
                \Illuminate\Support\Facades\DB::table('sppg_units')->delete();
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            foreach ($data as $row) {
                $id_sppg = trim($row['ID SPPG'] ?? '');
                $name = trim($row['NAMA SPPG'] ?? '');
                $code_sppg = trim($row['KODE SPPG'] ?? '');

                if (empty($id_sppg) || empty($name)) {
                    $errorMsg = "Baris ID $id_sppg terlewati: ID SPPG dan NAMA SPPG wajib diisi.";
                    $errorDetails[] = $errorMsg;
                    if ($mode === 'replace') throw new \Exception($errorMsg);
                    continue;
                }
                
                // Cek duplikat dalam file excel itu sendiri
                if (in_array($id_sppg, $processedIds)) {
                    $errorMsg = "Baris ID $id_sppg terlewati: Terdapat duplikasi ID SPPG dalam file Excel.";
                    $errorDetails[] = $errorMsg;
                    if ($mode === 'replace') throw new \Exception($errorMsg);
                    continue;
                }
                if (!empty($code_sppg) && in_array($code_sppg, $processedCodes)) {
                    $errorMsg = "Baris ID $id_sppg terlewati: Terdapat duplikasi KODE SPPG '$code_sppg' dalam file Excel.";
                    $errorDetails[] = $errorMsg;
                    if ($mode === 'replace') throw new \Exception($errorMsg);
                    continue;
                }

                $processedIds[] = $id_sppg;
                if (!empty($code_sppg)) $processedCodes[] = $code_sppg;

                // Cek duplikat di database jika mode Tambah Data (Append)
                if ($mode === 'append') {
                    $existsId = \App\Models\SppgUnit::where('id_sppg_unit', $id_sppg)->exists();
                    if ($existsId) {
                        $errorDetails[] = "Baris ID $id_sppg terlewati: ID SPPG sudah terdaftar di sistem.";
                        continue;
                    }

                    if (!empty($code_sppg)) {
                        $existsCode = \App\Models\SppgUnit::where('code_sppg_unit', $code_sppg)->exists();
                        if ($existsCode) {
                            $errorDetails[] = "Baris ID $id_sppg terlewati: KODE SPPG '$code_sppg' sudah terdaftar di sistem.";
                            continue;
                        }
                    }
                }

                try {
                    // Parse status strictly
                    $statusRaw = strtolower(trim($row['STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)'] ?? ''));
                    $status = match($statusRaw) {
                        'operasional' => 'Operasional',
                        'belum operasional' => 'Belum Operasional',
                        'tutup sementara' => 'Tutup Sementara',
                        'tutup permanen' => 'Tutup Permanen',
                        default => null
                    };

                    if (!$status) {
                        $rawOrig = $row['STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)'] ?? 'Kosong';
                        throw new \Exception("Status '$rawOrig' tidak sesuai format contoh.");
                    }

                    // Strict Date Validation
                    $rawDate = trim($row['TANGGAL OPERASIONAL (DD-MM-YYYY)'] ?? '');
                    $operational_date = null;
                    if (!empty($rawDate)) {
                        try {
                            $operational_date = \Carbon\Carbon::createFromFormat('d-m-Y', $rawDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            throw new \Exception("Format TANGGAL OPERASIONAL '$rawDate' salah. Gunakan DD-MM-YYYY.");
                        }
                    }

                    $sppgData = [
                        'code_sppg_unit' => !empty($code_sppg) ? $code_sppg : null,
                        'name' => $name,
                        'status' => $status,
                        'operational_date' => $operational_date,
                        'province' => trim($row['PROVINSI'] ?? ''),
                        'regency' => trim($row['KABUPATEN'] ?? ''),
                        'district' => trim($row['KECAMATAN'] ?? ''),
                        'village' => trim($row['DESA/KELURAHAN'] ?? ''),
                        'address' => trim($row['ALAMAT JALAN'] ?? null),
                        'latitude_gps' => trim($row['LATITUDE GPS'] ?? ''),
                        'longitude_gps' => trim($row['LONGITUDE GPS'] ?? ''),
                    ];

                    $sppgData['id_sppg_unit'] = $id_sppg;
                    $unit = \App\Models\SppgUnit::create($sppgData);

                    $facebookUrl = trim($row['LINK FACEBOOK'] ?? '');
                    $instagramUrl = trim($row['LINK INSTAGRAM'] ?? '');
                    $tiktokUrl = trim($row['LINK TIKTOK'] ?? '');

                    if ($facebookUrl || $instagramUrl || $tiktokUrl) {
                        $unit->socialMedia()->create([
                            'facebook_url' => $facebookUrl ?: null,
                            'instagram_url' => $instagramUrl ?: null,
                            'tiktok_url' => $tiktokUrl ?: null,
                        ]);
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorDetails[] = "Baris ID $id_sppg: " . $e->getMessage();
                    if ($mode === 'replace') throw $e;
                }
            }

            DB::commit();

            $message = "Berhasil mengimpor $successCount Unit SPPG.";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => empty($errorDetails) && $successCount > 0,
                    'message' => $message,
                    'errorDetails' => $errorDetails
                ]);
            }

            $response = redirect()->route('admin.manage-sppg.index');
            return empty($errorDetails)
                ? $response->with('success', $message)
                : $response->with('success', $message)->withErrors($errorDetails);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            if ($request->ajax()) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Gagal sistem: ' . $e->getMessage()
                 ]);
            }
            
            return back()->with('error', 'Gagal sistem: ' . $e->getMessage());
        }
    }
}
