<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppgUnit;
use App\Models\Person; // Pastikan menggunakan model Person sesuai index Anda
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SppgUnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Ambil data unit dengan eager loading relasi
        $query = SppgUnit::with(['leader', 'nutritionist', 'accountant', 'socialMedia'])->latest();

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

        $perPage = $request->query('per_page', 5);
        $units = $query->paginate($perPage)->withQueryString();

        // Ambil data person untuk dropdown leader
        $leaders = Person::whereHas('position', fn($q) => $q->where('slug_position', 'kasppg'))->orderBy('name')->get();
        $nutritionists = Person::whereHas('position', fn($q) => $q->where('slug_position', 'ag'))->orderBy('name')->get();
        $accountants = Person::whereHas('position', fn($q) => $q->where('slug_position', 'ak'))->orderBy('name')->get();

        return view('admin.manage-sppg.index', compact('units', 'leaders', 'nutritionists', 'accountants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Gunakan Validator Manual agar bisa kontrol response AJAX
        $validator = \Validator::make($request->all(), [
            'id_sppg_unit'      => 'required|string|unique:sppg_units,id_sppg_unit',
            'code_sppg_unit'    => 'nullable|string|unique:sppg_units,code_sppg_unit',
            'name'              => 'required|string|max:255',
            'status'            => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'  => 'nullable|date',
            'province_name'     => 'required|string',
            'regency_name'      => 'required|string',
            'district_name'     => 'required|string',
            'village_name'      => 'required|string',
            'latitude_gps'      => 'required',
            'longitude_gps'     => 'required',
            'leader_id'         => 'required|string', // String valid karena kita kirim kata "NULL" bukan kosong
            'photo'             => 'required|image|max:2048',
        ]);

        // 2. Jika Validasi Gagal (Termasuk Duplicate ID/Code)
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            // ... Logika Simpan Data Anda ...
            $data = $request->all();
            // (Pastikan mapping nama wilayah dan upload foto sudah benar disini)

            $folderHash = md5($request->id_sppg_unit . config('app.key'));
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            }

            \App\Models\SppgUnit::create([
                'id_sppg_unit' => $data['id_sppg_unit'],
                'code_sppg_unit' => $data['code_sppg_unit'],
                'name' => $data['name'],
                'status' => $data['status'],
                'operational_date' => $data['operational_date'] ?? null,
                'province' => $data['province_name'],
                'regency' => $data['regency_name'],
                'district' => $data['district_name'],
                'village' => $data['village_name'],
                'address' => $data['address'],
                'latitude_gps' => $data['latitude_gps'],
                'longitude_gps' => $data['longitude_gps'],
                'leader_id' => $data['leader_id'] === 'NULL' ? null : $data['leader_id'],
                'nutritionist_id' => (empty($data['nutritionist_id']) || $data['nutritionist_id'] === 'NULL') ? null : $data['nutritionist_id'],
                'accountant_id' => (empty($data['accountant_id']) || $data['accountant_id'] === 'NULL') ? null : $data['accountant_id'],
                'photo' => $data['photo'] ?? null,
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.manage-sppg.index')]);
            }

            return redirect()->route('admin.manage-sppg.index')->with('success', 'Unit Berhasil Ditambah');
        } catch (\Exception $e) {
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
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
            'id_sppg_unit'      => 'required|string|unique:sppg_units,id_sppg_unit,' . $id . ',id_sppg_unit',
            'code_sppg_unit'    => 'nullable|string|unique:sppg_units,code_sppg_unit,' . $id . ',id_sppg_unit',
            'name'              => 'required|string|max:255',
            'status'            => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'  => 'nullable|date',
            'province_name'     => 'required|string',
            'regency_name'      => 'required|string',
            'district_name'     => 'required|string',
            'village_name'      => 'required|string',
            'latitude_gps'      => 'required',
            'longitude_gps'     => 'required',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $oldId = $sppg->id_sppg_unit;

            // Foto handling jika ada 
            $photoPath = $sppg->photo;
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($sppg->photo) {
                    Storage::disk('public')->delete($sppg->photo);
                }
                $folderHash = md5($request->id_sppg_unit . config('app.key'));
                $photoPath = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            }

            // Update manual mem-bypass batasan PK eloquent
            \DB::table('sppg_units')->where('id_sppg_unit', $oldId)->update([
                'id_sppg_unit' => $request->id_sppg_unit,
                'code_sppg_unit' => $request->code_sppg_unit,
                'name' => $request->name,
                'status' => $request->status,
                'operational_date' => $request->operational_date,
                'province' => $request->province_name,
                'regency' => $request->regency_name,
                'district' => $request->district_name,
                'village' => $request->village_name,
                'address' => $request->address,
                'latitude_gps' => $request->latitude_gps,
                'longitude_gps' => $request->longitude_gps,
                'leader_id' => $request->leader_id === 'NULL' ? null : $request->leader_id,
                'nutritionist_id' => (empty($request->nutritionist_id) || $request->nutritionist_id === 'NULL') ? null : $request->nutritionist_id,
                'accountant_id' => (empty($request->accountant_id) || $request->accountant_id === 'NULL') ? null : $request->accountant_id,
                'photo' => $photoPath,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'redirect' => route('admin.manage-sppg.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    public function destroy($id)
    {
        $sppg = SppgUnit::findOrFail($id);

        if ($sppg->photo) {
            $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
            Storage::disk('public')->deleteDirectory("sppgunits/{$folderHash}");
        }

        $sppg->socialMedia()->delete();
        $sppg->delete();

        return redirect()->route('admin.manage-sppg.index')->with('success', 'Unit berhasil dihapus.');
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
        $fileName = 'DATA_SPPG_' . now()->format('d_M_Y_H_i') . '.xlsx';

        // Eksekusi menggunakan class export
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SppgExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SppgTemplateExport, 'Template Import SPPG.xlsx');
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
                    $errorDetails[] = "Baris ID $id_sppg terlewati: ID SPPG dan NAMA SPPG wajib diisi.";
                    continue;
                }
                
                // Cek duplikat dalam file excel itu sendiri
                if (in_array($id_sppg, $processedIds)) {
                    $errorDetails[] = "Baris ID $id_sppg terlewati: Terdapat duplikasi ID SPPG dalam file Excel.";
                    continue;
                }
                if (!empty($code_sppg) && in_array($code_sppg, $processedCodes)) {
                    $errorDetails[] = "Baris ID $id_sppg terlewati: Terdapat duplikasi KODE SPPG '$code_sppg' dalam file Excel.";
                    continue;
                }

                $processedIds[] = $id_sppg;
                if (!empty($code_sppg)) $processedCodes[] = $code_sppg;

                // Cek duplikat di database jika mode Tambah Data
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
                    \Illuminate\Support\Facades\DB::transaction(function () use ($row, $id_sppg, $name, $code_sppg, &$successCount, $mode) {
                        
                        // Parse status, default to Belum Operasional if match fails
                        $statusRaw = strtolower(trim($row['STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)'] ?? ''));
                        $status = match($statusRaw) {
                            'operasional' => 'Operasional',
                            'belum operasional' => 'Belum Operasional',
                            'tutup sementara' => 'Tutup Sementara',
                            'tutup permanen' => 'Tutup Permanen',
                            default => 'Belum Operasional'
                        };

                        $sppgData = [
                            'code_sppg_unit' => !empty($code_sppg) ? $code_sppg : null,
                            'name' => $name,
                            'status' => $status,
                            'operational_date' => !empty(trim($row['TANGGAL OPERASIONAL (DD-MM-YYYY)'] ?? '')) ? \Carbon\Carbon::createFromFormat('d-m-Y', trim($row['TANGGAL OPERASIONAL (DD-MM-YYYY)']))->format('Y-m-d') : null,
                            'province' => trim($row['PROVINSI'] ?? ''),
                            'regency' => trim($row['KABUPATEN'] ?? ''),
                            'district' => trim($row['KECAMATAN'] ?? ''),
                            'village' => trim($row['DESA/KELURAHAN'] ?? ''),
                            'address' => trim($row['ALAMAT JALAN'] ?? null),
                            'latitude_gps' => trim($row['LATITUDE GPS'] ?? ''),
                            'longitude_gps' => trim($row['LONGITUDE GPS'] ?? ''),
                        ];

                        $sppgData['id_sppg_unit'] = $id_sppg;
                        \App\Models\SppgUnit::create($sppgData);

                        $successCount++;
                    });
                } catch (\Exception $e) {
                    $errorDetails[] = "Baris ID $id_sppg: " . $e->getMessage();
                }
            }

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
