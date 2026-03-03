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
            'group_type' => 'nullable|in:Sekolah,Posyandu,Kelompok Lainnya',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:beneficiaries,code',
            'is_active' => 'nullable|boolean',
            'small_portion_male' => 'nullable|integer|min:0',
            'small_portion_female' => 'nullable|integer|min:0',
            'large_portion_male' => 'nullable|integer|min:0',
            'large_portion_female' => 'nullable|integer|min:0',
            'teacher_portion' => 'nullable|integer|min:0',
            'staff_portion' => 'nullable|integer|min:0',
            'cadre_portion' => 'nullable|integer|min:0',
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
            'group_type' => 'nullable|in:Sekolah,Posyandu,Kelompok Lainnya',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:beneficiaries,code,' . $id . ',id_beneficiary',
            'is_active' => 'nullable|boolean',
            'small_portion_male' => 'nullable|integer|min:0',
            'small_portion_female' => 'nullable|integer|min:0',
            'large_portion_male' => 'nullable|integer|min:0',
            'large_portion_female' => 'nullable|integer|min:0',
            'teacher_portion' => 'nullable|integer|min:0',
            'staff_portion' => 'nullable|integer|min:0',
            'cadre_portion' => 'nullable|integer|min:0',
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

        return response()->json([
            'id_duplicate' => $id ? Beneficiary::where('id_beneficiary', $id)->exists() : false,
            'code_duplicate' => $code ? Beneficiary::where('code', $code)->exists() : false,
        ]);
    }

    public function exportExcel(Request $request)
    {
        // Implementation for export if needed
        return response()->json(['message' => 'Export logic not implemented yet']);
    }

    public function exportTemplate()
    {
        // Implementation for template if needed
        return response()->json(['message' => 'Template logic not implemented yet']);
    }

    public function importBeneficiary(Request $request)
    {
        // Implementation for import if needed
        return response()->json(['message' => 'Import logic not implemented yet']);
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
