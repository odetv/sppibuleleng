<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppgUnit;
use App\Models\User;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SppgUnitController extends Controller
{
    public function index()
    {
        // 1. Ambil data unit dengan eager loading relasi
        $units = SppgUnit::with(['leader', 'socialMedia'])->latest()->paginate(10);

        // 2. Perbaikan: Ambil data dari tabel persons karena kolom 'name' ada di sana
        // Kita ambil id_person sebagai value dan name sebagai label di dropdown
        $leaders = \App\Models\Person::orderBy('name', 'asc')->get();

        // 3. Kirim ke view
        return view('admin.sppg.index', compact('units', 'leaders'));
    }

    public function create()
    {
        $leaders = User::all();
        return view('admin.sppg.create', compact('leaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_sppg_unit'    => 'nullable|unique:sppg_units,code_sppg_unit',
            'name'              => 'required|string|max:255',
            'status'            => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'  => 'nullable|date',
            'leader_id'         => 'nullable|exists:users,id_user',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facebook_url'      => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'tiktok_url'        => 'nullable|url',
            'province'          => 'nullable|string',
            'regency'           => 'nullable|string',
            'district'          => 'nullable|string',
            'village'           => 'nullable|string',
            'address'           => 'nullable|string',
        ]);

        // 1. Generate ID unik
        $id_unit = Str::upper(Str::random(8));
        $validated['id_sppg_unit'] = $id_unit;

        // 2. Handle Photo dengan Hash Folder
        if ($request->hasFile('photo')) {
            $folderHash = md5($id_unit . config('app.key'));
            $path = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            $validated['photo'] = $path;
        }

        // 3. Simpan Unit
        $sppg = SppgUnit::create($validated);

        // 4. Simpan Social Media (Polymorphic)
        $sppg->socialMedia()->create([
            'facebook_url'  => $request->facebook_url,
            'instagram_url' => $request->instagram_url,
            'tiktok_url'    => $request->tiktok_url,
        ]);

        return redirect()->route('admin.sppg.index')->with('success', 'Unit SPPG berhasil dibuat.');
    }

    public function edit($id)
    {
        $sppg = SppgUnit::with('socialMedia')->findOrFail($id);
        $leaders = User::all();
        return view('admin.sppg.edit', compact('sppg', 'leaders'));
    }

    public function update(Request $request, $id)
    {
        $sppg = SppgUnit::findOrFail($id);

        $validated = $request->validate([
            'code_sppg_unit'    => 'nullable|unique:sppg_units,code_sppg_unit,' . $id . ',id_sppg_unit',
            'name'              => 'required|string|max:255',
            'status'            => 'required|in:Operasional,Belum Operasional,Tutup Sementara,Tutup Permanen',
            'operational_date'  => 'nullable|date',
            'leader_id'         => 'nullable|exists:users,id_user',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facebook_url'      => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'tiktok_url'        => 'nullable|url',
            'province'          => 'nullable|string',
            'regency'           => 'nullable|string',
            'district'          => 'nullable|string',
            'village'           => 'nullable|string',
            'address'           => 'nullable|string',
        ]);

        // Handle Photo Update dengan Hash Folder
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($sppg->photo && Storage::disk('public')->exists($sppg->photo)) {
                Storage::disk('public')->delete($sppg->photo);
            }

            $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
            $path = $request->file('photo')->store("sppgunits/{$folderHash}/photos", 'public');
            $validated['photo'] = $path;
        }

        $sppg->update($validated);

        // Update atau Create Social Media
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

        // Hapus file fisik dan folder hash-nya
        if ($sppg->photo) {
            $folderHash = md5($sppg->id_sppg_unit . config('app.key'));
            Storage::disk('public')->deleteDirectory("sppgunits/{$folderHash}");
        }

        // Hapus data social media terkait
        $sppg->socialMedia()->delete();

        $sppg->delete();

        return redirect()->route('admin.sppg.index')->with('success', 'Unit berhasil dihapus.');
    }
}
