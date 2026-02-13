<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'phone'              => '081234567890',
                'password'           => Hash::make('Admin@123'),
                'id_ref_person_role' => 1,
                'status_user'        => 'active',
                'id_person'          => null,
            ]
        );
    }
}
