<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Unit SPPG
        DB::table('sppg_units')->insert([
            [
                'code_sppg' => 'BGN-BLL-01',
                'no_sppg' => '01',
                'district' => 'Buleleng',
                'regency' => 'Buleleng',
                'city' => 'Singaraja',
                'address' => 'Jl. Ngurah Rai No. 1',
                'date_ops' => '2025-01-01',
                'name' => 'Unit Pelayanan Buleleng Kota',
                'created_at' => now(),
            ],
            [
                'code_sppg' => 'BGN-SWR-02',
                'no_sppg' => '02',
                'district' => 'Sawan',
                'regency' => 'Buleleng',
                'city' => 'Singaraja',
                'address' => 'Jl. Raya Sangsit',
                'date_ops' => '2025-01-10',
                'name' => 'Unit Pelayanan Sawan',
                'created_at' => now(),
            ],
            [
                'code_sppg' => 'BGN-BJR-03',
                'no_sppg' => '03',
                'district' => 'Banjar',
                'regency' => 'Buleleng',
                'city' => 'Singaraja',
                'address' => 'Jl. Seririt-Singaraja',
                'date_ops' => '2025-01-15',
                'name' => 'Unit Pelayanan Banjar',
                'created_at' => now(),
            ],
        ]);

        // 2. Data SK (Assignment Decrees)
        DB::table('assignment_decrees')->insert([
            [
                'no_sk' => 'SK/BGN/2026/001',
                'date_sk' => '2026-01-05',
                'no_ba_verval' => 'BA/V/2026/01',
                'date_ba_verval' => '2026-01-04',
                'created_at' => now(),
            ],
            [
                'no_sk' => 'SK/BGN/2026/002',
                'date_sk' => '2026-01-12',
                'no_ba_verval' => 'BA/V/2026/02',
                'date_ba_verval' => '2026-01-11',
                'created_at' => now(),
            ],
            [
                'no_sk' => 'SK/BGN/2026/003',
                'date_sk' => '2026-01-20',
                'no_ba_verval' => 'BA/V/2026/03',
                'date_ba_verval' => '2026-01-19',
                'created_at' => now(),
            ],
        ]);

        // 3. Jembatan Work Assignments
        DB::table('work_assignments')->insert([
            ['id_assignment_decree' => 1, 'id_sppg_unit' => 1, 'created_at' => now()],
            ['id_assignment_decree' => 2, 'id_sppg_unit' => 2, 'created_at' => now()],
            ['id_assignment_decree' => 3, 'id_sppg_unit' => 3, 'created_at' => now()],
        ]);
    }
}
