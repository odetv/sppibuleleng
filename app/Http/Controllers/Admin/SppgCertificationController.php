<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SppgCertification;
use App\Models\SppgUnit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SppgCertificationController extends Controller
{
    public function index($id_sppg_unit)
    {
        $certifications = SppgCertification::where('id_sppg_unit', $id_sppg_unit)->latest()->get();
        return response()->json($certifications);
    }

    public function store(Request $request, $id_sppg_unit)
    {
        $request->validate([
            'name_certification'   => 'required|in:SLHS,Halal,HACCP,Chef',
            'certification_number' => 'required|string|max:255',
            'issued_by'            => 'required|string|max:255',
            'issued_date'          => 'required|date',
            'start_date'           => 'required|date',
            'expiry_date'          => 'required|date|after_or_equal:start_date',
            'file_certification'   => 'required|file|mimes:pdf|max:2048',
            'status'               => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $certification = SppgCertification::create([
                'id_sppg_unit'          => $id_sppg_unit,
                'name_certification'    => $request->name_certification,
                'certification_number'  => $request->certification_number,
                'issued_by'             => $request->issued_by,
                'issued_date'           => $request->issued_date,
                'start_date'            => $request->start_date,
                'expiry_date'           => $request->expiry_date,
                'status'                => $request->status,
                'file_certification'    => 'pending', // Temporary
            ]);

            $sppgHash = md5($id_sppg_unit . config('app.key'));
            $certHash = md5($certification->id_sppg_certification . config('app.key'));
            
            if ($request->hasFile('file_certification')) {
                $file = $request->file('file_certification');
                $fileName = $file->hashName();
                $path = "sppgunits/{$sppgHash}/files/{$certHash}/{$fileName}";
                $file->storeAs("public/{$path}", ''); // storeAs "public/..." stores in storage/app/public/...
                
                // Correctly use Storage::disk('public')->putFileAs or similar
                $storedPath = $file->storeAs("sppgunits/{$sppgHash}/files/{$certHash}", $fileName, 'public');
                
                $certification->update(['file_certification' => $storedPath]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sertifikasi berhasil ditambahkan',
                'certification' => $certification
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan sertifikasi: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $certification = SppgCertification::findOrFail($id);

        $request->validate([
            'name_certification'   => 'required|in:SLHS,Halal,HACCP,Chef',
            'certification_number' => 'required|string|max:255',
            'issued_by'            => 'required|string|max:255',
            'issued_date'          => 'required|date',
            'start_date'           => 'required|date',
            'expiry_date'          => 'required|date|after_or_equal:start_date',
            'file_certification'   => 'nullable|file|mimes:pdf|max:2048',
            'status'               => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only([
                'name_certification',
                'certification_number',
                'issued_by',
                'issued_date',
                'start_date',
                'expiry_date',
                'status'
            ]);

            if ($request->hasFile('file_certification')) {
                // Delete old file and directory
                $sppgHash = md5($certification->id_sppg_unit . config('app.key'));
                $certHash = md5($certification->id_sppg_certification . config('app.key'));
                Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$certHash}");

                $file = $request->file('file_certification');
                $fileName = $file->hashName();
                $storedPath = $file->storeAs("sppgunits/{$sppgHash}/files/{$certHash}", $fileName, 'public');
                $data['file_certification'] = $storedPath;
            }

            $certification->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sertifikasi berhasil diperbarui',
                'certification' => $certification
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui sertifikasi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $certification = SppgCertification::findOrFail($id);

            // Delete file directory
            $sppgHash = md5($certification->id_sppg_unit . config('app.key'));
            $certHash = md5($certification->id_sppg_certification . config('app.key'));
            Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$certHash}");

            $certification->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sertifikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus sertifikasi: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $certification = SppgCertification::findOrFail($id);
            $certification->update(['status' => !$certification->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status sertifikasi berhasil diubah',
                'status' => $certification->status
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}
