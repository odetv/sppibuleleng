<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('work_assignments')->insert([
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
        ]);
    }
}
