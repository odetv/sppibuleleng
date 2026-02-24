<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,             // 1. Akun Login dibuat (id_person: null)
            SppgUnitSeeder::class,         // 2. Unit SPPG dibuat (Leader merujuk ke id_user)
            AssignmentDecreeSeeder::class, // 3. Data SK
            WorkAssignmentSeeder::class,   // 4. Data Penugasan
            PersonSeeder::class,           // 5. Data Diri diisi & Link ke users di-update
            SocialMediaSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
