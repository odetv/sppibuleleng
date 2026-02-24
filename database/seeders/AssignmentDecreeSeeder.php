<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentDecreeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assignment_decrees')->insert([
            [
                'id_assignment_decree' => 1,
                'no_sk' => 'SK/BGN/2026/001',
                'date_sk' => '2026-01-05',
                'no_ba_verval' => 'BA/V/2026/01',
                'date_ba_verval' => '2026-01-04',
                'created_at' => now(),
            ],
            [
                'id_assignment_decree' => 2,
                'no_sk' => 'SK/BGN/2026/002',
                'date_sk' => '2026-01-12',
                'no_ba_verval' => 'BA/V/2026/02',
                'date_ba_verval' => '2026-01-11',
                'created_at' => now(),
            ],
        ]);
    }
}
