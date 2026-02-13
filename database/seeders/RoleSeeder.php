<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name_role' => 'Super Admin', 'slug_role' => 'superadmin'],
            ['name_role' => 'Guest', 'slug_role' => 'guest'],
            ['name_role' => 'SPPI', 'slug_role' => 'sppi'],
            ['name_role' => 'Koordinator Wilayah', 'slug_role' => 'korwil'],
            ['name_role' => 'Koordinator Kecamatan', 'slug_role' => 'korcam'],
            ['name_role' => 'Kepala SPPG', 'slug_role' => 'kasppg'],
            ['name_role' => 'Ahli Gizi', 'slug_role' => 'ag'],
            ['name_role' => 'Akuntansi', 'slug_role' => 'ak'],
        ];

        foreach ($roles as $role) {
            DB::table('ref_person_roles')->updateOrInsert(
                ['slug_role' => $role['slug_role']],
                ['name_role' => $role['name_role'], 'created_at' => now()]
            );
        }
    }
}