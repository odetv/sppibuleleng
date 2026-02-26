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
        $query = SppgUnit::with(['leader', 'socialMedia'])->latest();

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
        $leaders = Person::orderBy('name', 'asc')->get();

        return view('admin.sppg.index', compact('units', 'leaders'));
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
                'province' => $data['province_name'],
                'regency' => $data['regency_name'],
                'district' => $data['district_name'],
                'village' => $data['village_name'],
                'address' => $data['address'],
                'latitude_gps' => $data['latitude_gps'],
                'longitude_gps' => $data['longitude_gps'],
                'leader_id' => $data['leader_id'] === 'NULL' ? null : $data['leader_id'],
                'photo' => $data['photo'] ?? null,
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('admin.sppg.index')]);
            }

            return redirect()->route('admin.sppg.index')->with('success', 'Unit Berhasil Ditambah');
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
                'province' => $request->province_name,
                'regency' => $request->regency_name,
                'district' => $request->district_name,
                'village' => $request->village_name,
                'address' => $request->address,
                'latitude_gps' => $request->latitude_gps,
                'longitude_gps' => $request->longitude_gps,
                'leader_id' => $request->leader_id === 'NULL' ? null : $request->leader_id,
                'photo' => $photoPath,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'redirect' => route('admin.sppg.index')
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

        return redirect()->route('admin.sppg.index')->with('success', 'Unit berhasil dihapus.');
    }
}
