<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentDecreeSeeder extends Seeder
{
    public function run(): void
    {
        $posKepala = DB::table('ref_positions')->where('slug_position', 'kasppg')->first();
        $posGizi = DB::table('ref_positions')->where('slug_position', 'ag')->first();

        $decrees = [
            [
                'no_sk'          => 'SK/BGN/2026/001',
                'date_sk'        => '2026-01-05',
                'no_ba_verval'   => 'BA/V/2026/01',
                'date_ba_verval' => '2026-01-04',
                'type_sk'        => $posKepala->id_ref_position,
                'file_sk'        => null,
            ],
            [
                'no_sk'          => 'SK/BGN/2026/002',
                'date_sk'        => '2026-01-12',
                'no_ba_verval'   => 'BA/V/2026/02',
                'date_ba_verval' => '2026-01-11',
                'type_sk'        => $posGizi->id_ref_position,
                'file_sk'        => null,
            ],
        ];

        foreach ($decrees as $data) {
            \App\Models\AssignmentDecree::create($data);
        }
    }
}
