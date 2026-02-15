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
        // 1. DATA PERSON: ADMINISTRATOR UTAMA
        $adminPersonId = DB::table('persons')->insertGetId([
            'id_ref_position' => 1,
            'nik'             => '1234567890123456',
            'no_kk'           => '1234567890123450',
            'name'            => 'Administrator Utama',
            'npwp'            => '123456789012000',
            'photo'           => '',
            'title_education' => 'S.Kom.',
            'gender'          => 'L',
            'place_birthday'  => 'Makassar',
            'date_birthday'   => '1990-01-01',
            'age'             => 36,
            'religion'        => 'Islam',
            'marital_status'  => 'Kawin',
            'village'         => 'Buleleng',
            'district'        => 'Buleleng',
            'regency'         => 'Buleleng',
            'province'        => 'Bali',
            'address'         => 'Jl. Utama Admin No. 1',
            'gps_coordinates' => '-8.112, 115.091',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 2. DATA PERSON: USER 1 (AKTIF)
        $user1PersonId = DB::table('persons')->insertGetId([
            'id_ref_position' => 1,
            'nik'             => '3201012345670001',
            'no_kk'           => '3201012345670002',
            'name'            => 'Budi Santoso',
            'npwp'            => '881234567890000',
            'photo'           => '',
            'title_education' => 'S.E.',
            'gender'          => 'L',
            'place_birthday'  => 'Denpasar',
            'date_birthday'   => '1995-05-20',
            'age'             => 30,
            'religion'        => 'Hindu',
            'marital_status'  => 'Kawin',
            'village'         => 'Sawan',
            'district'        => 'Sawan',
            'regency'         => 'Buleleng',
            'province'        => 'Bali',
            'address'         => 'Jl. Mawar Merah No. 45',
            'gps_coordinates' => '-8.125, 115.110',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 3. DATA PERSON: USER 2 (PENDING / BELUM MELENGKAPI PROFIL)

        // 4. DATA PERSON: USER 3 (INACTIVE / SOFT DELETE)
        $user3PersonId = DB::table('persons')->insertGetId([
            'id_ref_position' => 1,
            'nik'             => '3201012345670009',
            'no_kk'           => '3201012345670010',
            'name'            => 'Anak Agung Gede',
            'npwp'            => '991234567890000',
            'photo'           => '',
            'title_education' => 'SMA',
            'gender'          => 'L',
            'place_birthday'  => 'Singaraja',
            'date_birthday'   => '1988-08-08',
            'age'             => 37,
            'religion'        => 'Hindu',
            'marital_status'  => 'Duda',
            'village'         => 'Banjar',
            'district'        => 'Banjar',
            'regency'         => 'Buleleng',
            'province'        => 'Bali',
            'address'         => 'Jl. Pahlawan No. 9',
            'gps_coordinates' => '-8.110, 115.010',
            'created_at'      => now(),
            'updated_at'      => now(),
            'deleted_at'      => now(),
        ]);

        // --- PEMBUATAN AKUN USER ---

        // 1. Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'phone'       => '081234567890',
                'password'    => Hash::make('Admin@123'),
                'id_ref_role' => 1,
                'id_person'   => $adminPersonId,
                'status_user' => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // 2. Akun User 1 (Status: Active)
        User::updateOrCreate(
            ['email' => 'user1@gmail.com'],
            [
                'phone'       => '08987654321',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => $user1PersonId,
                'status_user' => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // 3. Akun User 2 (Status: Pending)
        User::updateOrCreate(
            ['email' => 'user2@gmail.com'],
            [
                'phone'       => '08555555555',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => null, // Belum melengkapi profil, jadi belum ada data person
                'status_user' => 'pending',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // 4. Akun User 3 (Status: Inactive / Soft Delete)
        User::updateOrCreate(
            ['email' => 'user3@gmail.com'],
            [
                'phone'       => '08555555556',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => $user3PersonId,
                'status_user' => 'pending',
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => now(),
            ]
        );
    }
}
