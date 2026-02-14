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
        // 1. Buat data profil di tabel persons terlebih dahulu
        // Kita gunakan DB::table agar tidak bergantung pada model jika belum beres
        $personId = DB::table('persons')->insertGetId([
            'id_ref_position' => 1,
            'nik'             => '1234567890123456',
            'no_kk'           => '1234567890123456',
            'name'            => 'Administrator Utama',
            'npwp'            => '1234567890123456',
            'photo'           => '',
            'title_education' => 'S.Kom.',
            'gender'          => 'L',
            'place_birthday'  => 'Makassar',
            'date_birthday'   => '1990-01-01',
            'age'             => 36,
            'religion'        => 'Islam',
            'marital_status'  => 'Menikah',
            'village'         => 'Nama Desa',
            'district'        => 'Nama Kecamatan',
            'regency'         => 'Nama Kabupaten',
            'province'        => 'Sulawesi Selatan',
            'address'         => 'Jl. Contoh Alamat No. 123',
            'gps_coordinates' => '-5.123456,119.123456',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 2. Buat data akun di tabel users dan hubungkan ke id_person di atas
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'phone'       => '081234567890',
                'password'    => Hash::make('Admin@123'),
                'id_ref_role' => 1, // Merujuk ke Administrator
                'id_person'   => $personId, // Menghubungkan ke profil yang baru dibuat
                'status_user' => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
    }
}
