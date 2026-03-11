<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\SppgUnit;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'type_supplier' => 'Koperasi',
                'name_supplier' => 'Koperasi Merta Sari',
                'leader_name'   => 'I Nyoman Sudarma',
                'phone'         => '081234567891',
                'commodities'   => 'Beras, Minyak Goreng, Bumbu Dapur',
                'province'      => 'Bali',
                'regency'       => 'Buleleng',
                'district'      => 'Sawan',
                'village'       => 'Sangsit',
                'address'       => 'Jl. Raya Sangsit No. 10',
                'latitude_gps'  => '-8.123456',
                'longitude_gps' => '115.123456',
            ],
            [
                'type_supplier' => 'UMKM',
                'name_supplier' => 'UD. Sayur Segar',
                'leader_name'   => 'Siti Aminah',
                'phone'         => '081234567892',
                'commodities'   => 'Sayur Mayur, Cabai, Bawang',
                'province'      => 'Bali',
                'regency'       => 'Buleleng',
                'district'      => 'Buleleng',
                'village'       => 'Penarukan',
                'address'       => 'Jl. Gajah Mada No. 45',
                'latitude_gps'  => '-8.123457',
                'longitude_gps' => '115.123457',
            ],
            [
                'type_supplier' => 'Bumdes',
                'name_supplier' => 'Bumdes Mukti Harapan',
                'leader_name'   => 'Ketut Widiana',
                'phone'         => '081234567893',
                'commodities'   => 'Daging Ayam, Telur, Ikan',
                'province'      => 'Bali',
                'regency'       => 'Buleleng',
                'district'      => 'Banjar',
                'village'       => 'Banjar',
                'address'       => 'Jl. Seririt No. 22',
                'latitude_gps'  => '-8.123458',
                'longitude_gps' => '115.123458',
            ],
            [
                'type_supplier' => 'Supplier Lain',
                'name_supplier' => 'Catering Berkah',
                'leader_name'   => 'Hj. Fatimah',
                'phone'         => '081234567894',
                'commodities'   => 'Lauk Matang, Nasi Kotak',
                'province'      => 'Bali',
                'regency'       => 'Buleleng',
                'district'      => 'Buleleng',
                'village'       => 'Kampung Anyar',
                'address'       => 'Jl. Patimura No. 5',
                'latitude_gps'  => '-8.123459',
                'longitude_gps' => '115.123459',
            ],
            [
                'type_supplier' => 'UMKM',
                'name_supplier' => 'Toko Sembako Murah',
                'leader_name'   => 'Agung Wijaya',
                'phone'         => '081234567895',
                'commodities'   => 'Gula, Tepung, Susu',
                'province'      => 'Bali',
                'regency'       => 'Buleleng',
                'district'      => 'Sukasada',
                'village'       => 'Sukasada',
                'address'       => 'Jl. Jelantik No. 8',
                'latitude_gps'  => '-8.123460',
                'longitude_gps' => '115.123460',
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }

        // Assign some suppliers to units
        $units = SppgUnit::all();
        $allSuppliers = Supplier::all();

        if ($units->count() > 0 && $allSuppliers->count() > 0) {
            // Assign 1st supplier to 1st unit
            $units[0]->suppliers()->attach($allSuppliers[0]->id_supplier);
            
            // Assign 2nd supplier to 1st unit
            $units[0]->suppliers()->attach($allSuppliers[1]->id_supplier);

            // Assign 3rd supplier to 2nd unit (if exists)
            if (isset($units[1])) {
                $units[1]->suppliers()->attach($allSuppliers[2]->id_supplier);
            }

            // Assign 1st supplier to 2nd unit too (Many-to-Many)
            if (isset($units[1])) {
                $units[1]->suppliers()->attach($allSuppliers[0]->id_supplier);
            }
        }
    }
}
