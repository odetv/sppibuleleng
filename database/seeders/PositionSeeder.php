<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name_position' => 'SPPI', 'slug_position' => 'sppi'],
            ['name_position' => 'Korwil', 'slug_position' => 'korwil'],
            ['name_position' => 'Korcam', 'slug_position' => 'korcam'],
            ['name_position' => 'Kepala SPPG', 'slug_position' => 'kasppg'],
            ['name_position' => 'Ahli Gizi', 'slug_position' => 'ag'],
            ['name_position' => 'Akuntansi', 'slug_position' => 'ak'],
        ];

        foreach ($positions as $pos) {
            DB::table('ref_positions')->updateOrInsert(
                ['slug_position' => $pos['slug_position']],
                ['name_position' => $pos['name_position'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
