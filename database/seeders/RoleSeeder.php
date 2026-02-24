<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                ['name_role' => $role['name_role'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
