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
            'id_ref_position'    => 1,
            'id_work_assignment' => 1,
            'nik'                => '1234567890123456',
            'no_kk'               => '1234567890123450',
            'name'               => 'Administrator Utama',
            'nip'                => '123456789012000',
            'npwp'               => '123456789012000',
            'photo'              => '',
            'title_education'    => 'S.Kom.',
            'last_education'     => 'S-1',
            'major_education'    => 'Teknik Informatika',
            'clothing_size'      => 'XL',
            'shoe_size'          => '42',
            'batch'              => '1',
            'employment_status'  => 'Non-ASN',
            'payroll_bank_name'           => 'BNI',
            'payroll_bank_account_number' => '0012345678',
            'payroll_bank_account_name'   => 'Administrator Utama',
            'gender'             => 'L',
            'place_birthday'     => 'Makassar',
            'date_birthday'      => '1990-01-01',
            'age'                => 36,
            'religion'           => 'Islam',
            'marital_status'     => 'Kawin',

            // Field Baru
            'no_bpjs_kes'        => '000123456789',
            'no_bpjs_tk'         => '999123456789',

            // Alamat KTP
            'village_ktp'        => 'Buleleng',
            'district_ktp'       => 'Buleleng',
            'regency_ktp'        => 'Buleleng',
            'province_ktp'       => 'Bali',
            'address_ktp'        => 'Jl. Utama Admin No. 1',

            // Alamat Domisili
            'village_domicile'   => 'Buleleng',
            'district_domicile'  => 'Buleleng',
            'regency_domicile'   => 'Buleleng',
            'province_domicile'  => 'Bali',
            'address_domicile'   => 'Jl. Utama Admin No. 1',
            'latitude_gps_domicile'  => -8.11200000,
            'longitude_gps_domicile' => 115.09100000,

            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // 2. DATA PERSON: USER 1 (AKTIF)
        $user1PersonId = DB::table('persons')->insertGetId([
            'id_ref_position'    => 1,
            'id_work_assignment' => 2,
            'nik'                => '3201012345670001',
            'no_kk'               => '3201012345670002',
            'name'               => 'Budi Santoso',
            'nip'                => '123456789012000',
            'npwp'               => '881234567890000',
            'photo'              => '',
            'title_education'    => 'S.E.',
            'last_education'     => 'S-1',
            'major_education'    => 'Akuntansi',
            'clothing_size'      => 'L',
            'shoe_size'          => '40',
            'batch'              => '2',
            'employment_status'  => 'ASN',
            'payroll_bank_name'           => 'Mandiri',
            'payroll_bank_account_number' => '1230009876543',
            'payroll_bank_account_name'   => 'Budi Santoso',
            'gender'             => 'L',
            'place_birthday'     => 'Denpasar',
            'date_birthday'      => '1995-05-20',
            'age'                => 30,
            'religion'           => 'Hindu',
            'marital_status'     => 'Kawin',

            // Field Baru
            'no_bpjs_kes'        => null, // Contoh Nullable
            'no_bpjs_tk'         => null,

            'village_ktp'        => 'Sawan',
            'district_ktp'       => 'Sawan',
            'regency_ktp'        => 'Buleleng',
            'province_ktp'       => 'Bali',
            'address_ktp'        => 'Jl. Mawar Merah No. 45',

            'village_domicile'   => 'Sawan',
            'district_domicile'  => 'Sawan',
            'regency_domicile'   => 'Buleleng',
            'province_domicile'  => 'Bali',
            'address_domicile'   => 'Jl. Mawar Merah No. 45',
            'latitude_gps_domicile'  => -8.12500000,
            'longitude_gps_domicile' => 115.11000000,

            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // 3. DATA PERSON: USER 3 (INACTIVE / SOFT DELETE)
        $user3PersonId = DB::table('persons')->insertGetId([
            'id_ref_position'    => 1,
            'id_work_assignment' => 3,
            'nik'                => '3201012345670009',
            'no_kk'               => '3201012345670010',
            'name'               => 'Anak Agung Gede',
            'nip'                => '123456789012000',
            'npwp'               => '991234567890000',
            'photo'              => '',
            'title_education'    => 'SMA',
            'last_education'     => 'D-III',
            'major_education'    => 'Umum',
            'clothing_size'      => 'M',
            'shoe_size'          => '41',
            'batch'              => '3',
            'employment_status'  => 'Non-ASN',
            'payroll_bank_name'           => 'BPD Bali',
            'payroll_bank_account_number' => '0100202123456',
            'payroll_bank_account_name'   => 'Anak Agung Gede',
            'gender'             => 'L',
            'place_birthday'     => 'Singaraja',
            'date_birthday'      => '1988-08-08',
            'age'                => 37,
            'religion'           => 'Hindu',
            'marital_status'     => 'Duda',

            'no_bpjs_kes'        => '000888777666',
            'no_bpjs_tk'         => '999888777666',

            'village_ktp'        => 'Banjar',
            'district_ktp'       => 'Banjar',
            'regency_ktp'        => 'Buleleng',
            'province_ktp'       => 'Bali',
            'address_ktp'        => 'Jl. Pahlawan No. 9',

            'village_domicile'   => 'Banjar',
            'district_domicile'  => 'Banjar',
            'regency_domicile'   => 'Buleleng',
            'province_domicile'  => 'Bali',
            'address_domicile'   => 'Jl. Pahlawan No. 9',
            'latitude_gps_domicile'  => -8.11000000,
            'longitude_gps_domicile' => 115.01000000,

            'created_at'         => now(),
            'updated_at'         => now(),
            'deleted_at'         => now(),
        ]);

        // --- PEMBUATAN AKUN USER ---
        // (Bagian Akun User tidak berubah karena relasi id_person tetap sama)

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

        // 3. Akun User 2 (Status: Pending / Belum melengkapi profil)
        User::updateOrCreate(
            ['email' => 'user2@gmail.com'],
            [
                'phone'       => '08555555555',
                'password'    => Hash::make('User@123'),
                'id_ref_role' => 5,
                'id_person'   => null,
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
