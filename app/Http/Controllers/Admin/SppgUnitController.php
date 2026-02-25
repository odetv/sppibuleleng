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
    public function index()
    {
        // Ambil data unit dengan eager loading relasi
        $units = SppgUnit::with(['leader', 'socialMedia'])->latest()->paginate(5);

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
                'leader_id' => $data['leader_id'],
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
        $sppg = SppgUnit::findOrFail($id);

        $validated = $request->validate([
            'code_sppg_unit'    => 'nullable|string|unique:sppg_units,code_sppg_unit,' . $id . ',id_sppg_unit',
            'name'              => 'required|string|max:255',
            'status'            => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'  => 'nullable|date',
            'leader_id'         => 'nullable',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'province_name'     => 'nullable|string',
            'regency_name'      => 'nullable|string',
            'district_name'     => 'nullable|string',
            'village_name'      => 'nullable|string',
            'address'           => 'nullable|string',
            'latitude_gps'      => 'nullable|numeric',
            'longitude_gps'     => 'nullable|numeric',
        ]);

        // Update basic data
        $sppg->fill($request->except(['photo', 'province_name', 'regency_name', 'district_name', 'village_name']));

        // Update wilayah jika ada input baru
        if ($request->province_name) $sppg->province = $request->province_name;
        if ($request->regency_name)  $sppg->regency  = $request->regency_name;
        if ($request->district_name) $sppg->district = $request->district_name;
        if ($request->village_name)  $sppg->village  = $request->village_name;

        // Handle Photo Update
        if ($request->hasFile('photo')) {
            if ($sppg->photo) {
                Storage::disk('public')->delete($sppg->photo);
            }
            $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
            $path = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            $sppg->photo = $path;
        }

        $sppg->save();

        // Update Social Media
        $sppg->socialMedia()->updateOrCreate(
            ['socialable_id' => $sppg->id_sppg_unit, 'socialable_type' => SppgUnit::class],
            [
                'facebook_url'  => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'tiktok_url'    => $request->tiktok_url,
            ]
        );

        return redirect()->route('admin.sppg.index')->with('success', 'Data SPPG diperbarui.');
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
