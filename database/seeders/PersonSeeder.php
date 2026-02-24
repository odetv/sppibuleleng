<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DATA PERSON: ADMINISTRATOR UTAMA
        DB::table('persons')->insert([
            'id_person'          => 1,
            'id_ref_position'    => 1,
            'id_work_assignment' => 1,
            'nik'                => '1234567890123456',
            'no_kk'              => '1234567890123450',
            'name'               => 'Gede Bagler Pradita',
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
            'no_bpjs_kes'        => '000123456789',
            'no_bpjs_tk'         => '999123456789',
            'village_ktp'        => 'Ambengan',
            'district_ktp'       => 'Sukasada',
            'regency_ktp'        => 'Buleleng',
            'province_ktp'       => 'Bali',
            'address_ktp'        => 'Jl. Utama Niti Mandala No. 1',
            'village_domicile'   => 'Ambengan',
            'district_domicile'  => 'Sukasada',
            'regency_domicile'   => 'Buleleng',
            'province_domicile'  => 'Bali',
            'address_domicile'   => 'Jl. Utama Niti Mandala No. 1',
            'latitude_gps_domicile'  => -8.11200000,
            'longitude_gps_domicile' => 115.09100000,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
        // LINK KE USER 1
        DB::table('users')->where('id_user', 1)->update(['id_person' => 1]);

        // 2. DATA PERSON: USER 1 (AKTIF)
        DB::table('persons')->insert([
            'id_person'          => 2,
            'id_ref_position'    => 1,
            'id_work_assignment' => 2,
            'nik'                => '3201012345670001',
            'no_kk'              => '3201012345670002',
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
            'no_bpjs_kes'        => null,
            'no_bpjs_tk'         => null,
            'village_ktp'        => 'Bondalem',
            'district_ktp'       => 'Tejakula',
            'regency_ktp'        => 'Buleleng',
            'province_ktp'       => 'Bali',
            'address_ktp'        => 'Jl. Mawar Merah No. 45',
            'village_domicile'   => 'Bondalem',
            'district_domicile'  => 'Tejakula',
            'regency_domicile'   => 'Buleleng',
            'province_domicile'  => 'Bali',
            'address_domicile'   => 'Jl. Mawar Merah No. 45',
            'latitude_gps_domicile'  => -8.12500000,
            'longitude_gps_domicile' => 115.11000000,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
        // LINK KE USER 2
        DB::table('users')->where('id_user', 2)->update(['id_person' => 2]);

        // 3. DATA PERSON: USER 3 (INACTIVE / SOFT DELETE)
        DB::table('persons')->insert([
            'id_person'          => 3,
            'id_ref_position'    => 1,
            'id_work_assignment' => null,
            'nik'                => '3201012345670009',
            'no_kk'              => '3201012345670010',
            'name'               => 'Anak Agung Gede',
            'nip'                => '123456789012000',
            'npwp'                => '991234567890000',
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
        // LINK KE USER 4
        DB::table('users')->where('id_user', 4)->update(['id_person' => 3]);
    }
}
