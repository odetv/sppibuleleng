<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentDecree;
use App\Models\SppgUnit;
use App\Models\WorkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentDecreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = AssignmentDecree::query()->with('workAssignments.sppgUnit');

        if ($search) {
            $query->where('no_sk', 'like', "%{$search}%")
                  ->orWhere('no_ba_verval', 'like', "%{$search}%");
        }

        $decrees = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        // Prepare list of all SPPGs for the Create/Edit Modals
        $sppgUnits = SppgUnit::orderBy('name', 'asc')->get();

        // Ambil pemetaan SPPG mana yang sudah terkait dengan suatu SK (agar dropdown bisa dinonaktifkan di Frontend)
        $assignedSppgsMap = WorkAssignment::pluck('id_assignment_decree', 'id_sppg_unit')->toArray();

        return view('admin.manage-assignment-decree.index', compact('decrees', 'sppgUnits', 'assignedSppgsMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_sk'             => 'required|string|max:255|unique:assignment_decrees,no_sk',
            'date_sk'           => 'required|date',
            'no_ba_verval'      => 'required|string|max:255',
            'date_ba_verval'    => 'required|date',
            'file_sk'           => 'required|file|mimes:pdf|max:2048',
            'sppg_units'        => 'required|array|min:1',
            'sppg_units.*'      => 'exists:sppg_units,id_sppg_unit|unique:work_assignments,id_sppg_unit'
        ], [
            'no_sk.unique' => 'Nomor SK ini sudah terdaftar sebelumnya.',
            'file_sk.required' => 'File SK (PDF) Wajib diunggah.',
            'sppg_units.required' => 'Minimal 1 Unit SPPG harus ditugaskan/dipilih.',
            'sppg_units.*.unique' => 'Satu atau lebih Unit SPPG yang dipilih sudah tertaut dengan SK lain.'
        ]);

        try {
            DB::beginTransaction();

            // Simpan file original ke folder temporary agar bisa disalin, ini tidak terikat ID decre
            $originalFilePath = $request->file('file_sk')->store('temp/sk', 'public');
            $fileName = basename($originalFilePath);

            $decree = AssignmentDecree::create([
                'no_sk'          => $request->no_sk,
                'file_sk'        => $fileName,
                'date_sk'        => $request->date_sk,
                'no_ba_verval'   => $request->no_ba_verval,
                'date_ba_verval' => $request->date_ba_verval,
            ]);

            $skHash = md5($decree->id_assignment_decree . config('app.key'));

            if ($request->filled('sppg_units')) {
                foreach ($request->sppg_units as $id_sppg) {
                    WorkAssignment::create([
                        'id_assignment_decree' => $decree->id_assignment_decree,
                        'id_sppg_unit'         => $id_sppg
                    ]);

                    $sppgHash = md5($id_sppg . config('app.key'));
                    $destPath = "sppgunits/{$sppgHash}/files/{$skHash}/{$fileName}";
                    
                    // Salin dari file temporary
                    Storage::disk('public')->copy($originalFilePath, $destPath);
                }
            }
            
            // Hapus file dari folder temporary
            Storage::disk('public')->delete($originalFilePath);

            DB::commit();
            return redirect()->route('admin.manage-assignment-decree.index')->with('success', 'Data SK berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan Data SK: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $decree = AssignmentDecree::findOrFail($id);

        $request->validate([
            'no_sk'             => 'required|string|max:255|unique:assignment_decrees,no_sk,' . $id . ',id_assignment_decree',
            'date_sk'           => 'required|date',
            'no_ba_verval'      => 'required|string|max:255',
            'date_ba_verval'    => 'required|date',
            'file_sk'           => 'nullable|file|mimes:pdf|max:2048',
            'sppg_units'        => 'required|array|min:1',
            'sppg_units.*'      => [
                'exists:sppg_units,id_sppg_unit',
                \Illuminate\Validation\Rule::unique('work_assignments', 'id_sppg_unit')->whereNot('id_assignment_decree', $id)
            ]
        ], [
            'no_sk.unique' => 'Nomor SK ini sudah terdaftar sebelumnya.',
            'sppg_units.required' => 'Minimal 1 Unit SPPG harus ditugaskan/dipilih.',
            'sppg_units.*.unique' => 'Satu atau lebih Unit SPPG yang dipilih sudah tertaut dengan SK lain.'
        ]);

        try {
            DB::beginTransaction();

            $skHash = md5($decree->id_assignment_decree . config('app.key'));
            $oldFileName = $decree->file_sk;
            $newFileName = $oldFileName;
            
            $existingAssignments = WorkAssignment::where('id_assignment_decree', $id)->get();
            $existingSppgIds = $existingAssignments->pluck('id_sppg_unit')->toArray();
            $newSppgIds = $request->sppg_units ?? [];
            
            $toDelete = array_diff($existingSppgIds, $newSppgIds);
            $toAdd = array_diff($newSppgIds, $existingSppgIds);
            $keptSppgIds = array_intersect($existingSppgIds, $newSppgIds);

            $originalFilePath = null;
            
            // JIKA ADA FILE BARU DI UPLOAD
            if ($request->hasFile('file_sk')) {
                // Simpan original sementara
                $originalFilePath = $request->file('file_sk')->store('temp/sk', 'public');
                $newFileName = basename($originalFilePath);
                
                // Hapus file lama di semua folder sppg yang ditugaskan selama ini
                foreach ($existingSppgIds as $id_sppg) {
                    $sppgHash = md5($id_sppg . config('app.key'));
                    Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
                }
                
                // Copy file baru ke semua SPPG yang akan aktif (SPPG baru + SPPG dipertahankan)
                foreach ($newSppgIds as $id_sppg) {
                    $sppgHash = md5($id_sppg . config('app.key'));
                    $destPath = "sppgunits/{$sppgHash}/files/{$skHash}/{$newFileName}";
                    Storage::disk('public')->copy($originalFilePath, $destPath);
                }
            } else {
                // JIKA TIDAK ADA FILE BARU TAPI ADA PERUBAHAN SPPG
                // 1. Ambil file original sementara dari salah satu SPPG yang sudah ada jika ada SPPG baru yang ditambahkan
                if(!empty($toAdd) && !empty($existingSppgIds)) {
                     $tempSppgHash = md5($existingSppgIds[0] . config('app.key'));
                     $sourceTemp = "sppgunits/{$tempSppgHash}/files/{$skHash}/{$oldFileName}";
                     
                     if(Storage::disk('public')->exists($sourceTemp)) {
                         foreach ($toAdd as $id_sppg) {
                             $sppgHash = md5($id_sppg . config('app.key'));
                             $destPath = "sppgunits/{$sppgHash}/files/{$skHash}/{$oldFileName}";
                             Storage::disk('public')->copy($sourceTemp, $destPath);
                         }
                     }
                }
                
                // 2. Hapus folder file di unit SPPG yang tidak dipilih lagi
                foreach ($toDelete as $id_sppg) {
                    $sppgHash = md5($id_sppg . config('app.key'));
                    Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
                }
            }

            $decree->update([
                'no_sk'          => $request->no_sk,
                'file_sk'        => $newFileName,
                'date_sk'        => $request->date_sk,
                'no_ba_verval'   => $request->no_ba_verval,
                'date_ba_verval' => $request->date_ba_verval,
            ]);

            // Jika ada yang dihapus, lepaskan dulu dari tabel persons agar tidak error null
            if (!empty($toDelete)) {
                $waToDelete = WorkAssignment::where('id_assignment_decree', $id)
                                            ->whereIn('id_sppg_unit', $toDelete)
                                            ->get();

                foreach($waToDelete as $wa) {
                    DB::table('persons')->where('id_work_assignment', $wa->id_work_assignment)->update([
                        'id_work_assignment' => null
                    ]);
                    $wa->delete();
                }
            }

            // Tambahkan Relasi SPPG yang baru
            foreach ($toAdd as $id_sppg) {
                WorkAssignment::create([
                    'id_assignment_decree' => $decree->id_assignment_decree,
                    'id_sppg_unit'         => $id_sppg
                ]);
            }
            
            if ($originalFilePath) {
                Storage::disk('public')->delete($originalFilePath);
            }

            DB::commit();
            return redirect()->route('admin.manage-assignment-decree.index')->with('success', 'Data SK berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui Data SK: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $decree = AssignmentDecree::findOrFail($id);

            // Bersihkan Orphans form Persons
            $workAssignments = WorkAssignment::where('id_assignment_decree', $id)->get();
            $waIds = $workAssignments->pluck('id_work_assignment')->toArray();
            
            $skHash = md5($decree->id_assignment_decree . config('app.key'));
            
            if(!empty($waIds)) {
                DB::table('persons')->whereIn('id_work_assignment', $waIds)->update([
                    'id_work_assignment' => null
                ]);
                
                // Hapus file SK nya di folder SPPG masing - masing
                foreach($workAssignments as $wa) {
                    $sppgHash = md5($wa->id_sppg_unit . config('app.key'));
                    Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
                }
                
                WorkAssignment::whereIn('id_work_assignment', $waIds)->delete();
            }

            $decree->delete();

            DB::commit();
            
            // Handle redirect via JS if using DELETE method
            return redirect()->route('admin.manage-assignment-decree.index')->with('success', 'Data SK berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data SK: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'columns' => 'required|array|min:1'
        ], [
            'columns.required' => 'Pilih minimal satu kolom data yang ingin diekspor!'
        ]);

        $selectedColumns = $request->input('columns');
        $fileName = 'DATA_SK_' . now()->format('d_M_Y_H_i') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AssignmentDecreeExport($selectedColumns),
            $fileName
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AssignmentDecreeTemplateExport, 'Template Import SK.xlsx');
    }

    public function importDecree(Request $request)
    {
        $data = json_decode($request->json_data, true);
        $mode = $request->import_mode;

        if (!$data) return back()->with('error', 'Data tidak valid.');

        $successCount = 0;
        $errorDetails = [];
        
        $processedSk = [];

        try {
            if ($mode === 'replace') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                // Hapus direktori file SK dari semua unit SPPG
                $allSppg = SppgUnit::all();
                $allDecree = AssignmentDecree::all();
                
                foreach ($allDecree as $decree) {
                    $skHash = md5($decree->id_assignment_decree . config('app.key'));
                    foreach ($allSppg as $sppg) {
                        $sppgHash = md5($sppg->id_sppg_unit . config('app.key'));
                        Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
                    }
                }

                // Hapus penugasan pada tabel Person (agar tidak orphaned)
                $waIds = WorkAssignment::pluck('id_work_assignment')->toArray();
                if(!empty($waIds)) {
                    DB::table('persons')->whereIn('id_work_assignment', $waIds)->update([
                        'id_work_assignment' => null
                    ]);
                }
                
                // Hapus relasi WorkAssignments
                DB::table('work_assignments')->delete();
                // Hapus tabel AssignmentDecree
                DB::table('assignment_decrees')->delete();
                
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            foreach ($data as $row) {
                // Parse Data
                $no_sk = trim($row['NOMOR SK'] ?? '');
                $date_sk = trim($row['TANGGAL SK (DD-MM-YYYY)'] ?? '');
                $no_ba_verval = trim($row['NOMOR BA VERVAL'] ?? '');
                $date_ba_verval = trim($row['TANGGAL BA VERVAL (DD-MM-YYYY)'] ?? '');
                
                // Parse Assigned SPPGs (Comma separated)
                $sppgRaw = trim($row['ID SPPG TERKAIT (Pisahkan dengan Koma Jika >1)'] ?? '');
                $assignedSppgs = empty($sppgRaw) ? [] : array_map('trim', explode(',', $sppgRaw));

                if (empty($no_sk) || empty($no_ba_verval)) {
                    $errorDetails[] = "Baris SK $no_sk terlewati: Nomor SK dan Nomor BA Verval wajib diisi.";
                    continue;
                }
                
                // Cek duplikat dalam file excel itu sendiri
                if (in_array($no_sk, $processedSk)) {
                    $errorDetails[] = "Baris SK $no_sk terlewati: Terdapat duplikasi NOMOR SK '$no_sk' dalam file Excel.";
                    continue;
                }
                $processedSk[] = $no_sk;

                // Cek duplikat di database jika mode Tambah Data (Append)
                if ($mode === 'append') {
                    $existsSk = AssignmentDecree::where('no_sk', $no_sk)->exists();
                    if ($existsSk) {
                        $errorDetails[] = "Baris SK $no_sk terlewati: NOMOR SK '$no_sk' sudah terdaftar di sistem.";
                        continue;
                    }
                }

                // Validasi SPPG - Check if all exist and are NOT linked to ANY SK (since it's 1:1)
                $validSppgs = [];
                $sppgErrors = false;
                
                foreach ($assignedSppgs as $sp_id) {
                    $existsSppg = \App\Models\SppgUnit::where('id_sppg_unit', $sp_id)->exists();
                    if (!$existsSppg) {
                        $errorDetails[] = "Baris SK $no_sk terlewati: ID SPPG '$sp_id' tidak ditemukan di database.";
                        $sppgErrors = true;
                        break;
                    }
                    
                    // Enforce 1:1 Rules => Does it already have an assignment?
                    $alreadyAssigned = WorkAssignment::where('id_sppg_unit', $sp_id)->exists();
                    if ($alreadyAssigned) {
                        $errorDetails[] = "Baris SK $no_sk terlewati: ID SPPG '$sp_id' sudah memiliki relasi dengan SK lain.";
                        $sppgErrors = true;
                        break;
                    }
                    $validSppgs[] = $sp_id;
                }
                
                if ($sppgErrors) {
                    continue; // Skip SK insertion if ANY of the demanded SPPG are invalid.
                }

                try {
                    DB::transaction(function () use ($no_sk, $date_sk, $no_ba_verval, $date_ba_verval, $validSppgs, &$successCount) {
                        
                        // Buat SK Kosong Tanpa File
                        $decree = AssignmentDecree::create([
                            'no_sk'          => $no_sk,
                            'file_sk'        => null, // Import masal tidak membawa lampiran
                            'date_sk'        => !empty($date_sk) ? \Carbon\Carbon::createFromFormat('d-m-Y', $date_sk)->format('Y-m-d') : null,
                            'no_ba_verval'   => $no_ba_verval,
                            'date_ba_verval' => !empty($date_ba_verval) ? \Carbon\Carbon::createFromFormat('d-m-Y', $date_ba_verval)->format('Y-m-d') : null,
                        ]);

                        // Kaitkan SPPG
                        foreach ($validSppgs as $id_sppg) {
                            WorkAssignment::create([
                                'id_assignment_decree' => $decree->id_assignment_decree,
                                'id_sppg_unit'         => $id_sppg
                            ]);
                        }

                        $successCount++;
                    });
                } catch (\Exception $e) {
                    $errorDetails[] = "Baris SK $no_sk: " . $e->getMessage();
                }
            }

            $message = "Berhasil mengimpor $successCount Data SK.";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => empty($errorDetails) && $successCount > 0,
                    'message' => $message,
                    'errorDetails' => $errorDetails
                ]);
            }

            $response = redirect()->route('admin.manage-assignment-decree.index');
            return empty($errorDetails)
                ? $response->with('success', $message)
                : $response->with('success', $message)->withErrors($errorDetails);

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            if ($request->ajax()) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Gagal sistem: ' . $e->getMessage()
                 ]);
            }
            
            return back()->with('error', 'Gagal sistem: ' . $e->getMessage());
        }
    }

    public function checkAvailability(Request $request)
    {
        $numbers = $request->input('numbers', []);
        if (empty($numbers)) {
            return response()->json(['duplicates' => []]);
        }

        $duplicates = AssignmentDecree::whereIn('no_sk', $numbers)->pluck('no_sk')->toArray();
        
        return response()->json([
            'duplicates' => $duplicates
        ]);
    }
}
