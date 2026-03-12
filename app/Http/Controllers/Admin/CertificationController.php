<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\SppgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $query = Certification::with('sppgUnit');

        // Filter: Status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', (bool)$request->status);
        }

        // Filter: Jenis Sertifikasi
        if ($request->has('type') && $request->type !== '') {
            $query->where('name_certification', $request->type);
        }

        // Search: Unit Name or Cert Number
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certification_number', 'like', "%{$search}%")
                  ->orWhere('issued_by', 'like', "%{$search}%")
                  ->orWhereHas('sppgUnit', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $certifications = $query->latest()->paginate($perPage)->withQueryString();
        $sppgUnits = SppgUnit::with('certifications')->orderBy('name')->get();
        
        // Stats
        $stats = [
            'total' => Certification::count(),
            'aktif' => Certification::where('status', 1)->count(),
            'expired' => Certification::whereNotNull('expiry_date')
                                        ->where('expiry_date', '<', now())
                                        ->count(),
            'warning' => Certification::whereNotNull('expiry_date')
                                        ->where('expiry_date', '>', now())
                                        ->where('expiry_date', '<', now()->addMonths(3))
                                        ->count()
        ];
        
        return view('admin.manage-certification.index', compact('certifications', 'sppgUnits', 'stats'));
    }

    public function getByUnit($id_sppg_unit)
    {
        $certifications = Certification::where('id_sppg_unit', $id_sppg_unit)->get();
        return response()->json($certifications);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sppg_unit' => 'required|exists:sppg_units,id_sppg_unit',
            'name_certification' => 'required|string',
            'certification_number' => 'required|string',
            'issued_by' => 'required|string',
            'issued_date' => 'required|date',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'file_certification' => 'required|file|mimes:pdf|max:2048',
            'status' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $unit = SppgUnit::where('id_sppg_unit', $validated['id_sppg_unit'])->first();
            $folderHash = md5($unit->id_sppg_unit . config('app.key'));
            
            unset($validated['file_certification']);
            $certification = new Certification($validated);
            $certification->status = $request->input('status', true);
            $certification->save();
            
            if ($request->hasFile('file_certification')) {
                $file = $request->file('file_certification');
                $certHash = md5($certification->id_certification . config('app.key'));
                $path = $file->store("sppgunits/{$folderHash}/files/{$certHash}", 'public');
                $certification->update(['file_certification' => $path]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sertifikasi berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $certification = Certification::findOrFail($id);

        $validated = $request->validate([
            'name_certification' => 'required|string',
            'certification_number' => 'required|string',
            'issued_by' => 'required|string',
            'issued_date' => 'required|date',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'file_certification' => 'nullable|file|mimes:pdf|max:2048',
            'status' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('file_certification')) {
                // Delete old file
                if ($certification->file_certification) {
                    Storage::disk('public')->delete($certification->file_certification);
                }

                $unit = SppgUnit::where('id_sppg_unit', $certification->id_sppg_unit)->first();
                $folderHash = md5($unit->id_sppg_unit . config('app.key'));
                
                $file = $request->file('file_certification');
                $certHash = md5($certification->id_certification . config('app.key'));
                $path = $file->store("sppgunits/{$folderHash}/files/{$certHash}", 'public');
                $certification->file_certification = $path;
            }

            unset($validated['file_certification']);
            $certification->update($validated);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sertifikasi berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $certification = Certification::findOrFail($id);
            if ($certification->file_certification) {
                Storage::disk('public')->delete($certification->file_certification);
            }
            $certification->delete();
            return response()->json(['success' => true, 'message' => 'Sertifikasi berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $certification = Certification::findOrFail($id);
            $certification->status = !$certification->status;
            $certification->save();
            return response()->json(['success' => true, 'message' => 'Status sertifikasi berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
