<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certification;
use App\Models\SppgUnit;
use Carbon\Carbon;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = SppgUnit::limit(2)->get();
        if ($units->count() < 2) {
            $this->command->error('Need at least 2 SPPG units to run this seeder.');
            return;
        }

        $types = ['SLHS', 'Halal', 'HACCP', 'Chef'];
        $issuers = ['Dinas Kesehatan', 'Kemenag', 'LPPOM MUI', 'Professional Body'];

        foreach ($units as $index => $unit) {
            for ($i = 0; $i < 4; $i++) {
                $data = [
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'name_certification' => $types[$i],
                    'certification_number' => 'CERT/' . $unit->id_sppg_unit . '/' . Carbon::now()->year . '/' . ($i + 1),
                    'issued_by' => $issuers[$i],
                    'issued_date' => Carbon::now()->subMonths(rand(1, 12))->format('Y-m-d'),
                    'status' => true,
                    'file_certification' => null,
                ];

                // Example Cases:
                if ($i == 0) {
                    // Fully populated
                    $data['start_date'] = Carbon::now()->subMonths(rand(1, 6))->format('Y-m-d');
                    $data['expiry_date'] = Carbon::now()->addMonths(rand(6, 24))->format('Y-m-d');
                } elseif ($i == 1) {
                    // No Start Date
                    $data['start_date'] = null;
                    $data['expiry_date'] = Carbon::now()->addMonths(rand(6, 24))->format('Y-m-d');
                } elseif ($i == 2) {
                    // No Expiry Date (Infinity)
                    $data['start_date'] = Carbon::now()->subMonths(rand(1, 6))->format('Y-m-d');
                    $data['expiry_date'] = null;
                } else {
                    // Missing Both (Infinity)
                    $data['start_date'] = null;
                    $data['expiry_date'] = null;
                }

                Certification::create($data);
            }
        }

        $this->command->info('8 Certification records created successfully across 2 units.');
    }
}
