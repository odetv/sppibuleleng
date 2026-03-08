<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\SppgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $sppgUnitId = $request->query('sppg_unit');
        $province = $request->query('province');
        $regency = $request->query('regency');
        $district = $request->query('district');
        $village = $request->query('village');
        $groupType = $request->query('group_type');
        $category = $request->query('category');
        $ownershipType = $request->query('ownership_type');

        $query = Beneficiary::with('sppgUnit');

        // Search logic
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_beneficiary', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === '1');
        }

        // SPPG Unit Filter
        if ($sppgUnitId === 'unassigned') {
            $query->whereNull('id_sppg_unit');
        } elseif ($sppgUnitId) {
            $query->where('id_sppg_unit', $sppgUnitId);
        }

        // Address Hierarchy Filters
        if ($province) $query->where('province', $province);
        if ($regency) $query->where('regency', $regency);
        if ($district) $query->where('district', $district);
        if ($village) $query->where('village', $village);

        // Additional Filters
        if ($groupType) $query->where('group_type', $groupType);
        if ($category) $query->where('category', $category);
        if ($ownershipType) $query->where('ownership_type', $ownershipType);

        $perPage = $request->query('per_page', 5);
        $beneficiaries = $query->latest()->paginate($perPage)->withQueryString();

        // Data for Filter Dropdowns
        $sppgUnits = SppgUnit::orderBy('name')->get();
        
        // Fetch unique address values to populate filter dropdowns dynamically based on existing data
        $filterData = [
            'provinces' => Beneficiary::whereNotNull('province')->distinct()->pluck('province')->sort(),
            'regencies' => $province ? Beneficiary::where('province', $province)->whereNotNull('regency')->distinct()->pluck('regency')->sort() : [],
            'districts' => $regency ? Beneficiary::where('regency', $regency)->whereNotNull('district')->distinct()->pluck('district')->sort() : [],
            'villages' => $district ? Beneficiary::where('district', $district)->whereNotNull('village')->distinct()->pluck('village')->sort() : [],
            'group_types' => Beneficiary::whereNotNull('group_type')->distinct()->pluck('group_type')->sort(),
            'categories' => $groupType ? Beneficiary::where('group_type', $groupType)->whereNotNull('category')->distinct()->pluck('category')->sort() : Beneficiary::whereNotNull('category')->distinct()->pluck('category')->sort(),
            'ownership_types' => Beneficiary::whereNotNull('ownership_type')->distinct()->pluck('ownership_type')->sort(),
        ];

        if ($request->ajax()) {
            return view('admin.manage-beneficiary.index', compact('beneficiaries', 'sppgUnits', 'filterData'))->fragment('beneficiary-table-container');
        }

        return view('admin.manage-beneficiary.index', compact('beneficiaries', 'sppgUnits', 'filterData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sppg_unit' => 'nullable|exists:sppg_units,id_sppg_unit',
            'group_type' => 'required|in:Sekolah,Posyandu',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:beneficiaries,code',
            'is_active' => 'required|boolean',
            'category' => 'required|string',
            'ownership_type' => 'nullable|in:Negeri,Swasta',
            'province' => 'required|string',
            'regency' => 'required|string',
            'district' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'latitude_gps' => 'required|string',
            'longitude_gps' => 'required|string',
            'pic_name' => 'required|string',
            'pic_phone' => 'required|string',
            'small_portion_male' => 'required|integer|min:0',
            'small_portion_female' => 'required|integer|min:0',
            'large_portion_male' => 'required|integer|min:0',
            'large_portion_female' => 'required|integer|min:0',
            'teacher_portion' => 'required|integer|min:0',
            'staff_portion' => 'required|integer|min:0',
            'cadre_portion' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['is_active'] = $request->boolean('is_active');
            
            // Set defaults for portions
            $portions = ['small_portion_male', 'small_portion_female', 'large_portion_male', 'large_portion_female', 'teacher_portion', 'staff_portion', 'cadre_portion'];
            foreach ($portions as $p) {
                $data[$p] = $request->input($p) ?? 0;
            }

            $beneficiary = Beneficiary::create($data);
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'beneficiary' => $beneficiary,
                    'redirect' => route('admin.manage-beneficiary.index')
                ]);
            }
            return redirect()->route('admin.manage-beneficiary.index')->with('success', 'Penerima Manfaat Berhasil Ditambah');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Gagal menambah penerima manfaat: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::where('id_beneficiary', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'id_sppg_unit' => 'nullable|exists:sppg_units,id_sppg_unit',
            'group_type' => 'required|in:Sekolah,Posyandu',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:beneficiaries,code,' . $id . ',id_beneficiary',
            'is_active' => 'required|boolean',
            'category' => 'required|string',
            'ownership_type' => 'nullable|in:Negeri,Swasta',
            'province' => 'required|string',
            'regency' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'latitude_gps' => 'required|string',
            'pic_name' => 'required|string',
            'pic_phone' => 'required|string',
            'small_portion_male' => 'required|integer|min:0',
            'small_portion_female' => 'required|integer|min:0',
            'large_portion_male' => 'required|integer|min:0',
            'large_portion_female' => 'required|integer|min:0',
            'teacher_portion' => 'required|integer|min:0',
            'staff_portion' => 'required|integer|min:0',
            'cadre_portion' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['is_active'] = $request->boolean('is_active');

            // Set defaults for portions
            $portions = ['small_portion_male', 'small_portion_female', 'large_portion_male', 'large_portion_female', 'teacher_portion', 'staff_portion', 'cadre_portion'];
            foreach ($portions as $p) {
                $data[$p] = $request->input($p) ?? 0;
            }

            $beneficiary->update($data);
            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.manage-beneficiary.index')]);
            }
            return redirect()->route('admin.manage-beneficiary.index')->with('success', 'Penerima Manfaat Berhasil Diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Gagal memperbarui penerima manfaat: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $beneficiary = Beneficiary::findOrFail($id);
            $beneficiary->delete();
            return redirect()->route('admin.manage-beneficiary.index')->with('success', 'Penerima Manfaat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penerima manfaat: ' . $e->getMessage());
        }
    }

    public function checkAvailability(Request $request)
    {
        $id = $request->query('id_beneficiary');
        $code = $request->query('code');
        $idSppgUnit = $request->query('id_sppg_unit');
        
        $codeExists = false;
        if ($code) {
            $query = Beneficiary::where('code', $code);
            if ($id) {
                $query->where('id_beneficiary', '!=', $id);
            }
            $codeExists = $query->exists();
        }

        $sppgExists = true;
        if ($idSppgUnit) {
            $sppgExists = \App\Models\SppgUnit::where('id_sppg_unit', $idSppgUnit)->exists();
        }

        return response()->json([
            'id_duplicate' => $id ? Beneficiary::where('id_beneficiary', $id)->exists() : false,
            'code_duplicate' => $codeExists,
            'sppg_exists' => $sppgExists,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'columns' => 'required|array|min:1'
        ], [
            'columns.required' => 'Pilih minimal satu kolom data yang ingin diekspor!'
        ]);

        $selectedColumns = $request->input('columns');
        $fileName = 'DATA PM ' . now()->format('His-dmY') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BeneficiaryExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BeneficiaryTemplateExport, 'TEMPLATE IMPORT PM.xlsx');
    }

    public function importBeneficiary(Request $request)
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
                \Illuminate\Support\Facades\DB::table('beneficiaries')->delete();
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $processedCodes = [];

            foreach ($data as $row) {
                // Auto generate ID PM. Use uniqid or similar logic. Assuming PM- + timestamp or random.
                $generateId = function() {
                    $prefix = 'PM';
                    $timestamp = now()->format('ymdHis');
                    $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    return "{$prefix}-{$timestamp}-{$random}";
                };
                
                $id_beneficiary = $generateId();
                $name = trim($row['NAMA PENERIMA MANFAAT'] ?? '');
                $code = trim($row['KODE PM'] ?? '');
                
                $requiredFields = [
                    'TIPE KELOMPOK (Sekolah/Posyandu)', 'KATEGORI', 'KODE PM', 'NAMA PENERIMA MANFAAT',
                    'NAMA PIC', 'NO TELEPON PIC', 'EMAIL PIC', 'PROVINSI', 'KABUPATEN/KOTA', 
                    'KECAMATAN', 'DESA/KELURAHAN', 'ALAMAT JALAN', 'KODE POS', 'TIPE KEPEMILIKAN (Negeri/Swasta)', 'LATITUDE GPS', 'LONGITUDE GPS',
                    'PORSI KECIL LAKI-LAKI', 'PORSI KECIL PEREMPUAN', 'PORSI BESAR LAKI-LAKI',
                    'PORSI BESAR PEREMPUAN', 'PORSI GURU', 'PORSI TENAGA KEPENDIDIKAN', 'PORSI KADER'
                ];

                $emptyFields = [];
                foreach ($requiredFields as $field) {
                    if (!isset($row[$field]) || trim((string)$row[$field]) === '') {
                        $emptyFields[] = $field;
                    }
                }

                if (!empty($emptyFields)) {
                    $errorMsg = "Baris dengan Kode PM '" . ($code ?: '-') . "' terlewati: Kolom berikut wajib diisi: " . implode(', ', $emptyFields) . ".";
                    $errorDetails[] = $errorMsg;
                    if ($mode === 'replace') throw new \Exception($errorMsg);
                    continue;
                }
                
                // Cek duplikasi di raw data excel yang sedang diproses
                if (!empty($code) && in_array($code, $processedCodes)) {
                    $errorMsg = "Baris dengan Kode PM '$code' terlewati: Terdapat duplikasi KODE PM '$code' dalam file Excel.";
                    $errorDetails[] = $errorMsg;
                    if ($mode === 'replace') throw new \Exception($errorMsg);
                    continue;
                }

                if (!empty($code)) $processedCodes[] = $code;

                if ($mode === 'append') {
                    // Hanya cek duplikasi kode jika mode append
                    if (!empty($code) && Beneficiary::where('code', $code)->exists()) {
                        $errorDetails[] = "Baris dengan Kode PM '$code' terlewati: KODE PM '$code' sudah terdaftar di sistem.";
                        continue;
                    }
                }

                $idSppgUnitRaw = trim(str_replace("\xc2\xa0", "", $row['ID SPPG UNIT'] ?? ''));
                if ($idSppgUnitRaw === '-' || $idSppgUnitRaw === '0') {
                    $idSppgUnitRaw = '';
                }
                
                $idSppgUnit = null;
                if (!empty($idSppgUnitRaw)) {
                    // Cek apakah SPPG Unit ada di database
                    if (!\App\Models\SppgUnit::where('id_sppg_unit', $idSppgUnitRaw)->exists()) {
                        $errorMsg = "Baris dengan Kode PM '$code' terlewati: ID SPPG UNIT '$idSppgUnitRaw' tidak ditemukan di sistem.";
                        $errorDetails[] = $errorMsg;
                        if ($mode === 'replace') throw new \Exception($errorMsg);
                        continue;
                    }
                    $idSppgUnit = $idSppgUnitRaw;
                }

                try {
                    // Status default selalu true (aktif)
                    $isActive = true;

                    $groupTypeRaw = ucfirst(strtolower(trim($row['TIPE KELOMPOK (Sekolah/Posyandu)'] ?? '')));
                    $groupType = in_array($groupTypeRaw, ['Sekolah', 'Posyandu']) ? $groupTypeRaw : null;
                    
                    if (!$groupType && !empty($groupTypeRaw)) {
                         throw new \Exception("Tipe Kelompok '$groupTypeRaw' tidak valid. Harus Sekolah atau Posyandu.");
                    }

                    $beneficiaryData = [
                        'id_beneficiary' => $id_beneficiary,
                        'id_sppg_unit' => $idSppgUnit,
                        'code' => !empty($code) ? $code : null,
                        'name' => $name,
                        'group_type' => $groupType,
                        'category' => trim($row['KATEGORI'] ?? null),
                        'ownership_type' => trim($row['TIPE KEPEMILIKAN (Negeri/Swasta)'] ?? null),
                        'is_active' => $isActive,
                        'small_portion_male' => abs((int)($row['PORSI KECIL LAKI-LAKI'] ?? 0)),
                        'small_portion_female' => abs((int)($row['PORSI KECIL PEREMPUAN'] ?? 0)),
                        'large_portion_male' => abs((int)($row['PORSI BESAR LAKI-LAKI'] ?? 0)),
                        'large_portion_female' => abs((int)($row['PORSI BESAR PEREMPUAN'] ?? 0)),
                        'teacher_portion' => abs((int)($row['PORSI GURU'] ?? 0)),
                        'staff_portion' => abs((int)($row['PORSI TENAGA KEPENDIDIKAN'] ?? 0)),
                        'cadre_portion' => abs((int)($row['PORSI KADER'] ?? 0)),
                        'province' => trim($row['PROVINSI'] ?? ''),
                        'regency' => trim($row['KABUPATEN/KOTA'] ?? ''),
                        'district' => trim($row['KECAMATAN'] ?? ''),
                        'village' => trim($row['DESA/KELURAHAN'] ?? ''),
                        'address' => trim($row['ALAMAT JALAN'] ?? null),
                        'postal_code' => trim($row['KODE POS'] ?? null),
                        'latitude_gps' => trim($row['LATITUDE GPS'] ?? ''),
                        'longitude_gps' => trim($row['LONGITUDE GPS'] ?? ''),
                        'pic_name' => trim($row['NAMA PIC'] ?? null),
                        'pic_phone' => trim($row['NO TELEPON PIC'] ?? null),
                        'pic_email' => trim($row['EMAIL PIC'] ?? null),
                    ];

                    Beneficiary::create($beneficiaryData);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorDetails[] = "Baris dengan Kode PM '$code': " . $e->getMessage();
                    if ($mode === 'replace') throw $e;
                }
            }

            DB::commit();

            $message = "Berhasil mengimpor $successCount Data PM.";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => empty($errorDetails) && $successCount > 0,
                    'message' => $message,
                    'errorDetails' => $errorDetails
                ]);
            }

            $response = redirect()->route('admin.manage-beneficiary.index');
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

    public function linkToSppg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_beneficiary' => 'required|exists:beneficiaries,id_beneficiary',
            'id_sppg_unit' => 'nullable|exists:sppg_units,id_sppg_unit',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $beneficiary = Beneficiary::findOrFail($request->id_beneficiary);
            $beneficiary->update(['id_sppg_unit' => $request->id_sppg_unit]);
            
            // Refresh relationships if needed by re-fetching
            $updated = Beneficiary::with('sppgUnit')->find($request->id_beneficiary);
            
            // Also need to get updated beneficiaries list for the unit
            $unitBeneficiaries = [];
            if ($request->id_sppg_unit) {
                $unitBeneficiaries = Beneficiary::where('id_sppg_unit', $request->id_sppg_unit)->get();
            }

            return response()->json([
                'success' => true, 
                'beneficiary' => $updated,
                'unit_beneficiaries' => $unitBeneficiaries
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    public function batchLinkToSppg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_beneficiary_list' => 'required|array',
            'id_beneficiary_list.*' => 'exists:beneficiaries,id_beneficiary',
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            Beneficiary::whereIn('id_beneficiary', $request->id_beneficiary_list)
                ->update(['id_sppg_unit' => $request->id_sppg_unit]);
            DB::commit();

            $unitBeneficiaries = Beneficiary::where('id_sppg_unit', $request->id_sppg_unit)->get();

            return response()->json([
                'success' => true,
                'unit_beneficiaries' => $unitBeneficiaries
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }
}
