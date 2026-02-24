<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAndPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Hak Akses (Teknis)
        $roles = [
            ['name_role' => 'Administrator', 'slug_role' => 'administrator'],
            ['name_role' => 'Author', 'slug_role' => 'author'],
            ['name_role' => 'Editor', 'slug_role' => 'editor'],
            ['name_role' => 'Subscriber', 'slug_role' => 'subscriber'],
            ['name_role' => 'Guest', 'slug_role' => 'guest'],
        ];

        foreach ($roles as $role) {
            DB::table('ref_roles')->updateOrInsert(
                ['slug_role' => $role['slug_role']],
                ['name_role' => $role['name_role'], 'created_at' => now()]
            );
        }

        // 2. Data Jabatan (Struktural)
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
                ['name_position' => $pos['name_position'], 'created_at' => now()]
            );
        }
    }
}
