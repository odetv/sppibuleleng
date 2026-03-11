<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SppgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource (Admin Perspective).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');

        $query = Supplier::with('sppgUnits');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name_supplier', 'like', "%{$search}%")
                  ->orWhere('leader_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type_supplier', $type);
        }


        $perPage = $request->query('per_page', 5);
        $suppliers = $query->latest()->paginate($perPage)->withQueryString();

        $supplierTypes = [
            'Koperasi Desa Merah Putih',
            'Koperasi',
            'Bumdes',
            'Bumdesma',
            'UMKM',
            'Supplier Lain'
        ];

        $sppgUnits = SppgUnit::orderBy('name')->get(['id_sppg_unit', 'name']);
        $validSppgIds = $sppgUnits->pluck('id_sppg_unit')->toArray();

        if ($request->ajax()) {
            return view('admin.manage-supplier.index', compact('suppliers', 'supplierTypes', 'sppgUnits', 'validSppgIds'))->fragment('supplier-table-container');
        }

        return view('admin.manage-supplier.index', compact('suppliers', 'supplierTypes', 'sppgUnits', 'validSppgIds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'type_supplier' => 'required|string',
            'name_supplier' => 'required|string|max:255',
            'leader_name'   => 'required|string|max:255',
            'phone'         => 'required|numeric',
            'commodities'   => 'required|string',
            'province_name' => 'required|string',
            'regency_name'  => 'required|string',
            'district_name' => 'required|string',
            'village_name'  => 'required|string',
            'address'       => 'required|string',
            'postal_code'   => 'required|numeric',
            'latitude_gps'  => 'required|string',
            'longitude_gps' => 'required|string',
            'sppg_units'    => 'nullable|array',
            'sppg_units.*'  => 'exists:sppg_units,id_sppg_unit',
        ];

        $messages = [
            'required' => 'Wajib diisi',
            'numeric'  => 'Wajib berupa angka',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('sppg_units');
            $data['province'] = $request->province_name;
            $data['regency']  = $request->regency_name;
            $data['district'] = $request->district_name;
            $data['village']  = $request->village_name;

            $supplier = Supplier::create($data);
            if ($request->has('sppg_units') && !empty($request->sppg_units)) {
                $supplier->sppgUnits()->attach($request->sppg_units);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Supplier berhasil ditambahkan.',
                    'supplier' => $supplier
                ]);
            }

            return redirect()->route('admin.manage-supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $rules = [
            'type_supplier' => 'required|string',
            'name_supplier' => 'required|string|max:255',
            'leader_name'   => 'required|string|max:255',
            'phone'         => 'required|numeric',
            'commodities'   => 'required|string',
            'province_name' => 'required|string',
            'regency_name'  => 'required|string',
            'district_name' => 'required|string',
            'village_name'  => 'required|string',
            'address'       => 'required|string',
            'postal_code'   => 'required|numeric',
            'latitude_gps'  => 'required|string',
            'longitude_gps' => 'required|string',
            'sppg_units'    => 'nullable|array',
            'sppg_units.*'  => 'exists:sppg_units,id_sppg_unit',
        ];

        $messages = [
            'required' => 'Wajib diisi',
            'numeric'  => 'Wajib berupa angka',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('sppg_units');
            $data['province'] = $request->province_name;
            $data['regency']  = $request->regency_name;
            $data['district'] = $request->district_name;
            $data['village']  = $request->village_name;

            $supplier->update($data);
            if ($request->has('sppg_units')) {
                $supplier->sppgUnits()->sync($request->sppg_units);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data supplier berhasil diperbarui.']);
            }

            return redirect()->route('admin.manage-supplier.index')->with('success', 'Data supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            return redirect()->route('admin.manage-supplier.index')->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'columns' => 'required|array|min:1'
        ]);

        $selectedColumns = $request->input('columns');
        $fileName = 'DATA SUPPLIER ' . now()->format('His-dmY') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SupplierExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SupplierTemplateExport, 'TEMPLATE IMPORT SUPPLIER.xlsx');
    }

    public function checkAvailability(Request $request)
    {
        $name = $request->query('name');
        
        $duplicate = Supplier::where('name_supplier', $name)->exists();

        return response()->json([
            'duplicate' => $duplicate
        ]);
    }

    public function importSupplier(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;

        if (!$data) return response()->json(['success' => false, 'message' => 'Data tidak valid.']);

        $successCount = 0;
        $errorDetails = [];

        try {
            DB::beginTransaction();

            if ($mode === 'replace') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('sppg_unit_supplier')->truncate();
                Supplier::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            foreach ($data as $row) {
                $name = trim($row['NAMA SUPPLIER'] ?? '');
                
                if (empty($name)) {
                    $errorDetails[] = "Baris terlewati: NAMA SUPPLIER wajib diisi.";
                    continue;
                }

                if ($mode === 'append') {
                    if (Supplier::where('name_supplier', $name)->exists()) {
                        $errorDetails[] = "Baris $name terlewati: Nama supplier sudah terdaftar.";
                        continue;
                    }
                }

                try {
                    $supplierData = [
                        'type_supplier' => trim($row['JENIS SUPPLIER (Koperasi Desa Merah Putih/Koperasi/Bumdes/Bumdesma/UMKM/Supplier Lain)'] ?? ''),
                        'name_supplier' => $name,
                        'leader_name'   => trim($row['NAMA PIMPINAN'] ?? ''),
                        'phone'         => trim($row['TELEPON (Hanya Angka)'] ?? ''),
                        'commodities'   => trim($row['KOMODITAS (Contoh: Beras, Sayuran, Daging)'] ?? ''),
                        'province'      => trim($row['PROVINSI'] ?? ''),
                        'regency'       => trim($row['KABUPATEN'] ?? ''),
                        'district'      => trim($row['KECAMATAN'] ?? ''),
                        'village'       => trim($row['DESA/KELURAHAN'] ?? ''),
                        'address'       => trim($row['ALAMAT JALAN'] ?? ''),
                        'postal_code'   => trim($row['KODE POS'] ?? ''),
                        'latitude_gps'  => trim($row['LATITUDE_GPS'] ?? ''),
                        'longitude_gps' => trim($row['LONGITUDE_GPS'] ?? ''),
                    ];

                    $supplier = Supplier::create($supplierData);

                    // Handle Linked SPPG
                    $linkedSppg = trim($row['ID UNIT SPPG TERKAIT (Pisahkan dengan koma jika banyak)'] ?? '');
                    if (!empty($linkedSppg)) {
                        $sppgIds = array_map('trim', explode(',', $linkedSppg));
                        $validSppgIds = SppgUnit::whereIn('id_sppg_unit', $sppgIds)->pluck('id_sppg_unit')->toArray();
                        
                        // Check for invalid IDs
                        $invalidIds = array_diff($sppgIds, $validSppgIds);
                        if (!empty($invalidIds)) {
                            $errorDetails[] = "Baris $name: ID SPPG tidak ditemukan: " . implode(', ', $invalidIds);
                            // Optional: you might want to skip or still save the supplier but skip invalid links
                            // For strictness, let's keep saving the supplier but report the bad links.
                            // The user asked for correct validation, so reporting is key.
                        }

                        if (!empty($validSppgIds)) {
                            $supplier->sppgUnits()->attach($validSppgIds);
                        }
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorDetails[] = "Baris $name: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => $successCount > 0,
                'message' => "Berhasil mengimpor $successCount Supplier.",
                'errorDetails' => $errorDetails
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return response()->json(['success' => false, 'message' => 'Gagal sistem: ' . $e->getMessage()]);
        }
    }
    
    public function batchLinkToSppg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_supplier_list' => 'required|array',
            'id_supplier_list.*' => 'exists:suppliers,id_supplier',
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $unit = SppgUnit::findOrFail($request->id_sppg_unit);
            $unit->suppliers()->syncWithoutDetaching($request->id_supplier_list);
            DB::commit();

            $unitSuppliers = $unit->suppliers()->get();

            return response()->json([
                'success' => true,
                'unit_suppliers' => $unitSuppliers
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }

    public function unlinkFromSppg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $unit = SppgUnit::findOrFail($request->id_sppg_unit);
            $unit->suppliers()->detach($request->id_supplier);
            
            $unitSuppliers = $unit->suppliers()->get();

            return response()->json([
                'success' => true, 
                'unit_suppliers' => $unitSuppliers
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['system' => [$e->getMessage()]]], 500);
        }
    }
}
