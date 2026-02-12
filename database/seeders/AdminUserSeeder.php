<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RefPersonRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Role Admin ada
        $adminRole = DB::table('ref_person_roles')->where('slug_role', 'superadmin')->first();

        if (!$adminRole) {
            $idRole = DB::table('ref_person_roles')->insertGetId([
                'name_role' => 'Super Admin',
                'slug_role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $idRole = $adminRole->id_ref_person_role;
        }

        // 2. Buat Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'password' => Hash::make('Admin@123'),
                'id_ref_person_role' => $idRole,
                'status_user' => 'active',
                'id_person' => null,
            ]
        );
    }
}
