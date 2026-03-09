<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateSppgOfficers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-sppg-officers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting migration of SPPG Officers...");

        // Load specific positions
        $posKasppg = \App\Models\RefPosition::where('slug_position', 'kasppg')->first();
        $posAG = \App\Models\RefPosition::where('slug_position', 'ag')->first();
        $posAK = \App\Models\RefPosition::where('slug_position', 'ak')->first();

        if (!$posKasppg || !$posAG || !$posAK) {
            $this->error("Terdapat Jabatan Dasar (KaSPPG/AG/AK) yang tidak ditemukan di referensi (ref_positions). Pastikan seeder sudah berjalan.");
            return;
        }

        $units = \App\Models\SppgUnit::all();
        $count = 0;

        foreach ($units as $unit) {
            // KaSPPG
            if ($unit->leader_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->leader_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posKasppg->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }
            
            // Ahli Gizi (ag)
            if ($unit->nutritionist_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->nutritionist_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posAG->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }

            // Akuntan (ak)
            if ($unit->accountant_id) {
                \App\Models\SppgOfficer::updateOrCreate([
                    'id_person' => $unit->accountant_id,
                    'id_sppg_unit' => $unit->id_sppg_unit,
                    'id_ref_position' => $posAK->id_ref_position,
                ], ['is_active' => true]);
                $count++;
            }
        }

        $this->info("Successfully migrated {$count} officers into sppg_officers pivot table.");
    }
}
