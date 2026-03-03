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
        $query = Beneficiary::with('sppgUnit')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_beneficiary', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('id_sppg_unit', $search);
            });
        }

        $perPage = $request->query('per_page', 5);
        $beneficiaries = $query->paginate($perPage)->withQueryString();

        $sppgUnits = SppgUnit::orderBy('name')->get();

        return view('admin.manage-beneficiary.index', compact('beneficiaries', 'sppgUnits'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sppg_unit' => 'nullable|exists:sppg_units,id_sppg_unit',
            'group_type' => 'nullable|in:Sekolah,Posyandu,Kelompok Lainnya',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:beneficiaries,code',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            Beneficiary::create($request->all());
            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.manage-beneficiary.index')]);
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
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $beneficiary->update($request->all());
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
