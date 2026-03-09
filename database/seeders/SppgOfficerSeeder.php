<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SppgOfficer;
use App\Models\Person;
use App\Models\SppgUnit;
use App\Models\RefPosition;
use App\Models\RefRole;
use App\Models\User;

class SppgOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = SppgUnit::all();
        $posKasppg = RefPosition::where('slug_position', 'kasppg')->first();
        $posAG = RefPosition::where('slug_position', 'ag')->first();
        $posAK = RefPosition::where('slug_position', 'ak')->first();

        // Role default untuk relawan (ID 5 = guest sesuai permintaan)
        $roleGuestId = 5;

        // Other Volunteer Positions
        $volunteerPositions = RefPosition::whereIn('slug_position', [
            'aslap',
            'chef',
            'pengolahan',
            'persiapan',
            'pemorsian',
            'distribusi',
            'cuci-ompreng',
            'kebersihan',
            'keamanan'
        ])->get();

        $firstNames = ['Made', 'Putu', 'Nyoman', 'Ketut', 'Gede', 'Luh', 'Kadek', 'Komang', 'Wayan', 'Agus', 'Siti', 'Budi', 'Dewi', 'Eko', 'Sari', 'Anak Agung', 'Ida Bagus'];
        $lastNames = ['Satria', 'Bagus', 'Adi', 'Wijaya', 'Rama', 'Pratama', 'Wira', 'Kusuma', 'Putra', 'Lestari', 'Saputra', 'Indah', 'Ratna', 'Sari', 'Dewi', 'Artha', 'Budianto', 'Utama', 'Wardana', 'Kirana'];

        $usedNames = [];
        $assignedPersonIds = [];

        foreach ($units as $unit) {
            // 1. Core Personnel (Leader, AG, AK) - sudah ada person-nya, hanya update ref_position
            $coreMap = [
                'leader_id'       => $posKasppg,
                'nutritionist_id' => $posAG,
                'accountant_id'   => $posAK,
            ];

            foreach ($coreMap as $personIdField => $pos) {
                if ($unit->$personIdField && $pos) {
                    $personId = $unit->$personIdField;

                    if (in_array($personId, $assignedPersonIds)) continue;

                    // Sinkronisasi id_ref_position ke person
                    Person::where('id_person', $personId)->update([
                        'id_ref_position' => $pos->id_ref_position,
                    ]);

                    SppgOfficer::updateOrCreate([
                        'id_person'        => $personId,
                        'id_sppg_unit'     => $unit->id_sppg_unit,
                        'id_ref_position'  => $pos->id_ref_position,
                    ], [
                        'daily_honor' => $personIdField == 'leader_id' ? 50000 : 40000,
                        'is_active'   => true,
                    ]);

                    $assignedPersonIds[] = $personId;

                    User::updateOrCreate(['id_person' => $personId], [
                        'id_ref_role'  => $roleGuestId,
                        'phone'        => '081234567' . rand(100, 999),
                        'status_user'  => 'active',
                    ]);
                }
            }

            // 2. Additional Volunteer Divisions (2-4 per unit)
            $randomPositions = $volunteerPositions->random(rand(2, 4));
            foreach ($randomPositions as $vPos) {
                // Generate unique name
                do {
                    $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
                    if (rand(0, 1)) $name .= ' ' . $lastNames[array_rand($lastNames)];
                } while (in_array($name, $usedNames));

                $usedNames[] = $name;
                $nik = '5103' . rand(100000000000, 999999999999);
                $phone = '0877' . rand(10000000, 99999999);

                // Buat Person — hanya field yang sesuai modal create
                // id_work_assignment dibiarkan null karena unit diambil dari sppg_officers
                $person = Person::create([
                    'name'           => $name,
                    'nik'            => $nik,
                    'no_kk'          => '5103' . rand(100000000000, 999999999999),
                    'gender'         => rand(0, 1) ? 'L' : 'P',
                    'place_birthday' => 'Singaraja',
                    'date_birthday'  => '1995-01-01',
                    'age'            => 30,
                    'religion'       => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'][rand(0, 5)],
                    'marital_status' => ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'][rand(0, 3)],
                    'no_bpjs_tk'     => null,
                    'village_ktp'    => 'Kampung Baru',
                    'district_ktp'   => 'Buleleng',
                    'regency_ktp'    => 'Buleleng',
                    'province_ktp'   => 'Bali',
                    'address_ktp'    => 'Jl. Singaraja',
                    'village_domicile'   => 'Kampung Baru',
                    'district_domicile'  => 'Buleleng',
                    'regency_domicile'   => 'Buleleng',
                    'province_domicile'  => 'Bali',
                    'address_domicile'   => 'Jl. Singaraja',
                    // Sinkronisasi jabatan — unit dari sppg_officers
                    'id_ref_position'    => $vPos->id_ref_position,
                ]);

                SppgOfficer::create([
                    'id_person'       => $person->id_person,
                    'id_sppg_unit'    => $unit->id_sppg_unit,
                    'id_ref_position' => $vPos->id_ref_position,
                    'daily_honor'     => 135000,
                    'is_active'       => true,
                ]);

                User::create([
                    'id_person'   => $person->id_person,
                    'id_ref_role' => $roleGuestId,
                    'phone'       => $phone,
                    'status_user' => 'active',
                ]);
            }
        }
    }
}
