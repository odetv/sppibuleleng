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


        $perPage = $request->query('per_page', 10);
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

        if ($request->ajax()) {
            return view('admin.manage-supplier.index', compact('suppliers', 'supplierTypes', 'sppgUnits'))->fragment('supplier-table-container');
        }

        return view('admin.manage-supplier.index', compact('suppliers', 'supplierTypes', 'sppgUnits'));
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
            'phone'         => 'required|string|max:20',
            'commodities'   => 'required|string',
            'province'      => 'required|string',
            'regency'       => 'required|string',
            'district'      => 'required|string',
            'village'       => 'required|string',
            'address'       => 'required|string',
            'postal_code'   => 'nullable|string|max:10',
            'sppg_units'    => 'required|array',
            'sppg_units.*'  => 'exists:sppg_units,id_sppg_unit',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $supplier = Supplier::create($request->except('sppg_units'));
            $supplier->sppgUnits()->attach($request->sppg_units);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Supplier berhasil ditambahkan.']);
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
            'phone'         => 'required|string|max:20',
            'commodities'   => 'required|string',
            'province'      => 'required|string',
            'regency'       => 'required|string',
            'district'      => 'required|string',
            'village'       => 'required|string',
            'address'       => 'required|string',
            'postal_code'   => 'nullable|string|max:10',
            'sppg_units'    => 'required|array',
            'sppg_units.*'  => 'exists:sppg_units,id_sppg_unit',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $supplier->update($request->except('sppg_units'));
            $supplier->sppgUnits()->sync($request->sppg_units);

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
}
