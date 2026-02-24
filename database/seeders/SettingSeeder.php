<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan status awal untuk mode maintenance
        Setting::updateOrCreate(
            ['key' => 'is_maintenance'],
            ['value' => '0'] // Default: Mati (bisa diakses)
        );
    }
}
