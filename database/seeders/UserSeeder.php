<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id_user'     => 1,
                'phone'       => '081234567890',
                'password'    => Hash::make('Admin@123'),
                'id_ref_role' => 1,
                'id_person'   => null, // Null dulu sesuai alur pendaftaran
                'status_user' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun User 1
        User::updateOrCreate(
            ['email' => 'user1@gmail.com'],
            [
                'id_user'     => 2,
                'phone'       => '08987654321',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => null,
                'status_user' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun User 2 (Tetap Pending tanpa profil)
        User::updateOrCreate(
            ['email' => 'user2@gmail.com'],
            [
                'id_user'     => 3,
                'phone'       => '08555555555',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => null,
                'status_user' => 'pending',
            ]
        );

        // 4. Akun User 3
        User::updateOrCreate(
            ['email' => 'user3@gmail.com'],
            [
                'id_user'     => 4,
                'phone'       => '08555555556',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => null,
                'status_user' => 'pending',
                'deleted_at'  => now(),
            ]
        );
    }
}
