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
            UserSeeder::class,
            PersonSeeder::class,
            SettingSeeder::class,
            SocialMediaSeeder::class,
            SppgUnitSeeder::class,
            AssignmentDecreeSeeder::class,
            WorkAssignmentSeeder::class,
            SppgOfficerSeeder::class,
            BeneficiarySeeder::class,
            SupplierSeeder::class,
            CertificationSeeder::class,
        ]);
    }
}
