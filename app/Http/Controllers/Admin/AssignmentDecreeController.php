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
        $date_sk = $request->input('date_sk');
        $date_ba = $request->input('date_ba');

        $query = AssignmentDecree::query()->with('workAssignments.sppgUnit');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('no_sk', 'like', "%{$search}%")
                  ->orWhere('no_ba_verval', 'like', "%{$search}%");
            });
        }

        if ($date_sk) {
            $query->whereDate('date_sk', $date_sk);
        }

        if ($date_ba) {
            $query->whereDate('date_ba_verval', $date_ba);
        }

        $decrees = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        // Prepare list of all SPPGs for the Create/Edit Modals
        $sppgUnits = SppgUnit::orderBy('name', 'asc')->get();

        // Ambil list jabatan untuk Tipe SK
        $positions = DB::table('ref_positions')->orderBy('name_position', 'asc')->get();

        // Ambil pemetaan SPPG mana yang sudah terkait dengan SK tertentu (dikelompokkan berdasarkan type_sk)
        // Format: [type_sk => [id_sppg_unit1, id_sppg_unit2, ...]]
        $assignedSppgsMap = DB::table('work_assignments')
            ->join('assignment_decrees', 'work_assignments.id_assignment_decree', '=', 'assignment_decrees.id_assignment_decree')
            ->select('work_assignments.id_sppg_unit', 'assignment_decrees.id_assignment_decree', 'assignment_decrees.type_sk')
            ->get()
            ->groupBy('type_sk')
            ->map(function($items) {
                return $items->pluck('id_assignment_decree', 'id_sppg_unit')->toArray();
            })
            ->toArray();

        return view('admin.manage-assignment-decree.index', compact('decrees', 'sppgUnits', 'assignedSppgsMap', 'positions'));
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
            'type_sk'           => 'required|exists:ref_positions,id_ref_position',
            'file_sk'           => 'required|file|mimes:pdf|max:2048',
            'sppg_units'        => [
                function ($attribute, $value, $fail) use ($request) {
                    $pos = DB::table('ref_positions')->where('id_ref_position', $request->type_sk)->first();
                    $isCoreRole = $pos && in_array($pos->slug_position, ['kasppg', 'ag', 'ak']);
                    
                    if ($isCoreRole && (empty($value) || !is_array($value) || count($value) === 0)) {
                        $fail('Minimal 1 Unit SPPG harus ditugaskan untuk jabatan ini.');
                    }
                }
            ],
            'sppg_units.*'      => [
                'exists:sppg_units,id_sppg_unit',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('work_assignments')
                        ->join('assignment_decrees', 'work_assignments.id_assignment_decree', '=', 'assignment_decrees.id_assignment_decree')
                        ->where('work_assignments.id_sppg_unit', $value)
                        ->where('assignment_decrees.type_sk', $request->type_sk)
                        ->exists();
                    if ($exists) {
                        $fail('Satu atau lebih Unit SPPG yang dipilih sudah memiliki SK untuk jabatan ini.');
                    }
                }
            ]
        ], [
            'no_sk.unique' => 'Nomor SK ini sudah terdaftar sebelumnya.',
            'file_sk.required' => 'File SK (PDF) Wajib diunggah.',
        ]);

        try {
            DB::beginTransaction();

            // Ambil slug jabatan untuk path penyimpanan
            $pos = DB::table('ref_positions')->where('id_ref_position', $request->type_sk)->first();
            $posSlug = $pos->slug_position ?? 'unknown';

            // Simpan file original ke folder temporary agar bisa disalin
            $originalFilePath = $request->file('file_sk')->store('temp/sk', 'public');
            $fileName = basename($originalFilePath);

            $decree = AssignmentDecree::create([
                'no_sk'          => $request->no_sk,
                'file_sk'        => $fileName,
                'date_sk'        => $request->date_sk,
                'no_ba_verval'   => $request->no_ba_verval,
                'date_ba_verval' => $request->date_ba_verval,
                'type_sk'        => $request->type_sk,
            ]);

            $skHash = md5($decree->id_assignment_decree . config('app.key'));

            if ($request->filled('sppg_units')) {
                // JIKA ADA SPPG (Wajib untuk KaSPPG, AG, AK; Opsional untuk lainnya)
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
            } else {
                // JIKA TIDAK ADA SPPG: buat WA dengan unit null agar muncul di dropdown penugasan
                WorkAssignment::create([
                    'id_assignment_decree' => $decree->id_assignment_decree,
                    'id_sppg_unit'         => null
                ]);

                $destPath = "positions/{$posSlug}/files/{$skHash}/{$fileName}";
                Storage::disk('public')->copy($originalFilePath, $destPath);
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
            'type_sk'           => 'required|exists:ref_positions,id_ref_position',
            'file_sk'           => 'nullable|file|mimes:pdf|max:2048',
            'sppg_units'        => [
                function ($attribute, $value, $fail) use ($request) {
                    $pos = DB::table('ref_positions')->where('id_ref_position', $request->type_sk)->first();
                    $isCoreRole = $pos && in_array($pos->slug_position, ['kasppg', 'ag', 'ak']);
                    
                    if ($isCoreRole && (empty($value) || !is_array($value) || count($value) === 0)) {
                        $fail('Minimal 1 Unit SPPG harus ditugaskan untuk jabatan ini.');
                    }
                }
            ],
            'sppg_units.*'      => [
                'exists:sppg_units,id_sppg_unit',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $exists = DB::table('work_assignments')
                        ->join('assignment_decrees', 'work_assignments.id_assignment_decree', '=', 'assignment_decrees.id_assignment_decree')
                        ->where('work_assignments.id_sppg_unit', $value)
                        ->where('assignment_decrees.type_sk', $request->type_sk)
                        ->where('assignment_decrees.id_assignment_decree', '!=', $id)
                        ->exists();
                    if ($exists) {
                        $fail('Satu atau lebih Unit SPPG yang dipilih sudah memiliki SK untuk jabatan ini.');
                    }
                }
            ]
        ], [
            'no_sk.unique' => 'Nomor SK ini sudah terdaftar sebelumnya.',
        ]);

        try {
            DB::beginTransaction();

            // Info Jabatan Baru & Lama
            $newPos = DB::table('ref_positions')->where('id_ref_position', $request->type_sk)->first();
            $newPosSlug = $newPos->slug_position ?? 'unknown';
            
            $oldPos = DB::table('ref_positions')->where('id_ref_position', $decree->type_sk)->first();
            $oldPosSlug = $oldPos->slug_position ?? 'unknown';

            $skHash = md5($decree->id_assignment_decree . config('app.key'));
            $oldFileName = $decree->file_sk;
            $newFileName = $oldFileName;
            
            $existingAssignments = WorkAssignment::where('id_assignment_decree', $id)->get();
            // Filter out null sppg_unit (WA record untuk posisi non-unit)
            $existingSppgIds = $existingAssignments->pluck('id_sppg_unit')->filter()->values()->toArray();
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
                
                // 1. Bersihkan semua folder SPPG lama
                foreach ($existingSppgIds as $id_sppg) {
                    $sppgHash = md5($id_sppg . config('app.key'));
                    Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
                }

                // 2. Bersihkan folder posisi lama
                Storage::disk('public')->deleteDirectory("positions/{$oldPosSlug}/files/{$skHash}");
                
                // 3. Simpan ke destinasi baru
                if (!empty($newSppgIds)) {
                    foreach ($newSppgIds as $id_sppg) {
                        $sppgHash = md5($id_sppg . config('app.key'));
                        $destPath = "sppgunits/{$sppgHash}/files/{$skHash}/{$newFileName}";
                        Storage::disk('public')->copy($originalFilePath, $destPath);
                    }
                } else {
                    $destPath = "positions/{$newPosSlug}/files/{$skHash}/{$newFileName}";
                    Storage::disk('public')->copy($originalFilePath, $destPath);
                }
            } else {
                // JIKA TIDAK ADA FILE BARU TAPI ADA PERUBAHAN SPPG ATAU JABATAN
                
                // 1. Cari sumber file yang ada (bisa dari SPPG lama atau folder Posisi)
                $sourcePath = null;
                if (!empty($existingSppgIds)) {
                    $tempSppgHash = md5($existingSppgIds[0] . config('app.key'));
                    $sourcePath = "sppgunits/{$tempSppgHash}/files/{$skHash}/{$oldFileName}";
                } else {
                    $sourcePath = "positions/{$oldPosSlug}/files/{$skHash}/{$oldFileName}";
                }

                // Jika sumber ditemukan, lakukan migrasi/copy
                if(Storage::disk('public')->exists($sourcePath)) {
                    // Jika sekarang ada SPPG, copy ke yang baru/belum ada
                    if (!empty($newSppgIds)) {
                        foreach ($toAdd as $id_sppg) {
                            $sppgHash = md5($id_sppg . config('app.key'));
                            $destPath = "sppgunits/{$sppgHash}/files/{$skHash}/{$oldFileName}";
                            Storage::disk('public')->copy($sourcePath, $destPath);
                        }
                        
                        // Hapus folder posisi jika sebelumnya ada disana
                        Storage::disk('public')->deleteDirectory("positions/{$oldPosSlug}/files/{$skHash}");
                    } else {
                        // Jika sekarang TIDAK ada SPPG, pindahkan ke folder posisi (jika ganti jabatan atau sebelumnya dari SPPG)
                        $destPath = "positions/{$newPosSlug}/files/{$skHash}/{$oldFileName}";
                        if ($newPosSlug !== $oldPosSlug || !empty($existingSppgIds)) {
                            Storage::disk('public')->copy($sourcePath, $destPath);
                            
                            // Bersihkan folder lama jika bukan folder posisi yang sama
                            if ($newPosSlug !== $oldPosSlug) {
                                Storage::disk('public')->deleteDirectory("positions/{$oldPosSlug}/files/{$skHash}");
                            }
                        }
                    }
                }
                
                // Hapus folder file di unit SPPG yang tidak dipilih lagi
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
                'type_sk'        => $request->type_sk,
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

            // Jika tidak ada sppg_units (posisi non-unit), pastikan ada 1 WA null
            if (empty($newSppgIds)) {
                $hasNullWa = WorkAssignment::where('id_assignment_decree', $id)
                    ->whereNull('id_sppg_unit')->exists();
                if (!$hasNullWa) {
                    WorkAssignment::create([
                        'id_assignment_decree' => $decree->id_assignment_decree,
                        'id_sppg_unit'         => null
                    ]);
                }
            } else {
                // Posisi berubah menjadi unit-role: hapus WA null jika ada
                WorkAssignment::where('id_assignment_decree', $id)
                    ->whereNull('id_sppg_unit')->delete();
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

            // Ambil info jabatan untuk path
            $pos = DB::table('ref_positions')->where('id_ref_position', $decree->type_sk)->first();
            $posSlug = $pos->slug_position ?? 'unknown';

            // Bersihkan Orphans form Persons
            $workAssignments = WorkAssignment::where('id_assignment_decree', $id)->get();
            $waIds = $workAssignments->pluck('id_work_assignment')->toArray();
            
            $skHash = md5($decree->id_assignment_decree . config('app.key'));
            
            // 1. Hapus penugasan person
            if(!empty($waIds)) {
                DB::table('persons')->whereIn('id_work_assignment', $waIds)->update([
                    'id_work_assignment' => null
                ]);
            }
                
            // 2. Hapus file SK di folder SPPG (jika ada relasi, skip jika id_sppg_unit null)
            foreach($workAssignments as $wa) {
                if (!$wa->id_sppg_unit) continue;
                $sppgHash = md5($wa->id_sppg_unit . config('app.key'));
                Storage::disk('public')->deleteDirectory("sppgunits/{$sppgHash}/files/{$skHash}");
            }

            // 3. Hapus file SK di folder POSISI (jika tidak ada relasi SPPG)
            Storage::disk('public')->deleteDirectory("positions/{$posSlug}/files/{$skHash}");
            
            // 4. Hapus Relasi & Decree
            WorkAssignment::where('id_assignment_decree', $id)->delete();
            $decree->delete();

            DB::commit();
            
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
