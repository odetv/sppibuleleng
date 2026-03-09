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
            BeneficiarySeeder::class,      // 3. Data Penerima Manfaat
            AssignmentDecreeSeeder::class, // 4. Data SK
            WorkAssignmentSeeder::class,   // 5. Data Penugasan
            PersonSeeder::class,           // 6. Data Diri diisi & Link ke users di-update
            SppgOfficerSeeder::class,
            SocialMediaSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
