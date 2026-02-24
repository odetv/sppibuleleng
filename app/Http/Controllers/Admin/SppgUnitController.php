<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppgUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SppgUnitController extends Controller
{
    public function index()
    {
        $units = SppgUnit::with('leader')->latest()->paginate(10);
        return view('admin.sppg.index', compact('units'));
    }

    public function create()
    {
        // Mengambil user yang mungkin jadi leader (bisa difilter berdasarkan position 'kasppg')
        $leaders = User::all();
        return view('admin.sppg.create', compact('leaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_sppg_unit' => 'nullable|unique:sppg_units,code_sppg_unit',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date' => 'nullable|date',
            'leader_id' => 'nullable|exists:users,id_user',
            'province' => 'nullable|string',
            'regency' => 'nullable|string',
            'district' => 'nullable|string',
            'village' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Generate ID string unik 8 karakter
        $validated['id_sppg_unit'] = Str::upper(Str::random(8));

        SppgUnit::create($validated);

        return redirect()->route('admin.sppg.index')->with('success', 'Unit SPPG berhasil dibuat.');
    }

    public function edit($id)
    {
        $sppg = SppgUnit::findOrFail($id);
        $leaders = User::all();
        return view('admin.sppg.edit', compact('sppg', 'leaders'));
    }

    public function update(Request $request, $id)
    {
        $sppg = SppgUnit::findOrFail($id);

        $validated = $request->validate([
            'code_sppg_unit' => 'nullable|unique:sppg_units,code_sppg_unit,' . $id . ',id_sppg_unit',
            'name' => 'required|string|max:255',
            'status' => 'required',
            'operational_date' => 'nullable|date',
            'leader_id' => 'nullable|exists:users,id_user',
        ]);

        $sppg->update($validated);

        return redirect()->route('admin.sppg.index')->with('success', 'Data SPPG diperbarui.');
    }

    public function destroy($id)
    {
        SppgUnit::findOrFail($id)->delete();
        return redirect()->route('admin.sppg.index')->with('success', 'Unit berhasil dihapus.');
    }
}
