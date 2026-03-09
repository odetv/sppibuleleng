<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SppgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SppgSupplierController extends Controller
{
    public function index(Request $request)
    {
        $person = Auth::user()->person;
        
        if (!$person) {
            return redirect()->route('dashboard')->with('error', 'Profil Anda belum lengkap.');
        }

        $unit = SppgUnit::where('leader_id', $person->id_person)
            ->orWhere('nutritionist_id', $person->id_person)
            ->orWhere('accountant_id', $person->id_person)
            ->first();

        if (!$unit && Auth::user()->role->slug_role !== 'administrator') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terhubung dengan unit SPPG manapun.');
        }

        if (!$unit && Auth::user()->role->slug_role === 'administrator') {
            $unit = SppgUnit::first();
        }

        if (!$unit) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada unit SPPG yang ditemukan.');
        }

        $search = $request->query('search');
        $suppliersQuery = $unit->suppliers();

        if ($search) {
            $suppliersQuery->where(function($q) use ($search) {
                $q->where('name_supplier', 'like', "%{$search}%")
                  ->orWhere('leader_name', 'like', "%{$search}%");
            });
        }

        $mySuppliers = $suppliersQuery->get();
        
        $availableSuppliers = Supplier::whereDoesntHave('sppgUnits', function($q) use ($unit) {
            $q->where('sppg_unit_supplier.id_sppg_unit', $unit->id_sppg_unit);
        })->where('status', true)->orderBy('name_supplier')->get();

        return view('sppg.supplier.index', compact('unit', 'mySuppliers', 'availableSuppliers'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
        ]);

        $unit = SppgUnit::findOrFail($request->id_sppg_unit);
        $unit->suppliers()->syncWithoutDetaching([$request->id_supplier => ['is_active' => true]]);

        return back()->with('success', 'Supplier berhasil ditambahkan ke unit Anda.');
    }

    public function unassign(Request $request)
    {
        $request->validate([
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
        ]);

        $unit = SppgUnit::findOrFail($request->id_sppg_unit);
        $unit->suppliers()->detach($request->id_supplier);

        return back()->with('success', 'Supplier berhasil dilepas dari unit Anda.');
    }
}
