<?php

namespace Database\Seeders;

use App\Models\SppgUnit;
use Illuminate\Database\Seeder;

class SppgUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'id_sppg_unit' => '2DWFSVHQ',
                'code_sppg_unit' => '51.08.04.2014.01',
                'name' => 'SPPG Buleleng Banjar Dencarik',
                'status' => 'Belum Operasional',
                'operational_date' => '2025-05-19',
                'province' => 'Bali',
                'regency' => 'Buleleng',
                'district' => 'Banjar',
                'village' => 'Dencarik',
                'address' => 'Jalan Seririt - Singaraja (Sebelah Barat SPBU Dencarik)',
                'latitude_gps' => -8.18644440,
                'longitude_gps' => 114.97655560,
                'leader_id' => 1,
                'photo' => null, // Default null
                'social' => [
                    'facebook_url' => 'https://facebook.com/sppg.dencarik',
                    'instagram_url' => 'https://instagram.com/sppg.dencarik',
                    'tiktok_url' => null,
                ]
            ],
            [
                'id_sppg_unit' => '59QMIA7P',
                'code_sppg_unit' => '51.08.05.2001.01',
                'name' => 'SPPG Buleleng Sukasada Pancasari',
                'status' => 'Operasional',
                'operational_date' => '2025-08-19',
                'province' => 'Bali',
                'regency' => 'Buleleng',
                'district' => 'Sukasada',
                'village' => 'Pancasari',
                'address' => 'Jalan Singaraja–Denpasar KM 4',
                'latitude_gps' => -8.15436110,
                'longitude_gps' => 115.10819440,
                'leader_id' => 2,
                'photo' => null,
                'social' => [
                    'facebook_url' => null,
                    'instagram_url' => 'https://instagram.com/sppg.pancasari',
                    'tiktok_url' => 'https://tiktok.com/@sppg.pancasari',
                ]
            ],
            [
                'id_sppg_unit' => 'RQCH6R1P',
                'code_sppg_unit' => '51.08.04.2015.02',
                'name' => 'SPPG Buleleng Banjar Temukus',
                'status' => 'Belum Operasional',
                'operational_date' => null,
                'province' => 'Bali',
                'regency' => 'Buleleng',
                'district' => 'Banjar',
                'village' => 'Temukus',
                'address' => 'Jalan Seririt-Singaraja, Desa Temukus',
                'latitude_gps' => -8.18411944,
                'longitude_gps' => 114.98326230,
                'leader_id' => null,
                'photo' => null,
                'social' => [
                    'facebook_url' => null,
                    'instagram_url' => null,
                    'tiktok_url' => null,
                ]
            ],
        ];

        foreach ($units as $unitData) {
            // Pisahkan data social media dari data unit utama
            $socialData = $unitData['social'];
            unset($unitData['social']);

            // Create Unit
            $unit = SppgUnit::create($unitData);

            // Create Social Media relation
            $unit->socialMedia()->create($socialData);
        }
    }
}
