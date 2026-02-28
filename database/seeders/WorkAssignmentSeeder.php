<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            [
                'id_assignment_decree' => 1,
                'id_sppg_unit' => '2DWFSVHQ',
                'created_at' => now()
            ],
            [
                'id_assignment_decree' => 2,
                'id_sppg_unit' => '59QMIA7P',
                'created_at' => now()
            ],
        ];

        DB::table('work_assignments')->insert($assignments);

        // --- Generate Dummy PDF files physically on disk ---
        $dummyPdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n188\n%%EOF";
        $decrees = DB::table('assignment_decrees')->get();

        foreach ($assignments as $assignment) {
            $decree = $decrees->firstWhere('id_assignment_decree', $assignment['id_assignment_decree']);
            if ($decree && $decree->file_sk) {
                // Generate Hashes
                $hashSppg = md5($assignment['id_sppg_unit'] . config('app.key'));
                $hashSk = md5($assignment['id_assignment_decree'] . config('app.key'));
                
                // Construct target path exactly like in controller
                $path = "public/sppgunits/{$hashSppg}/files/{$hashSk}/{$decree->file_sk}";
                
                // Write Dummy PDF to storage
                Storage::put($path, $dummyPdfContent);
            }
        }
    }
}
