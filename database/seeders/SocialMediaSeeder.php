<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\SppgUnit;

class SocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        // Isi Sosmed untuk semua Person yang ada (Random/Dummy)
        Person::all()->each(function ($person) {
            $person->socialMedia()->updateOrCreate(
                ['socialable_id' => $person->id_person, 'socialable_type' => Person::class],
                [
                    'instagram_url' => 'https://instagram.com/' . str()->slug($person->name),
                    'facebook_url'  => 'https://facebook.com/' . str()->slug($person->name),
                ]
            );
        });

        // Isi Sosmed untuk Unit SPPG
        SppgUnit::all()->each(function ($unit) {
            $unit->socialMedia()->updateOrCreate(
                ['socialable_id' => $unit->id_sppg_unit, 'socialable_type' => SppgUnit::class],
                [
                    // Gunakan str()->slug dari nama unit karena no_sppg mungkin tidak ada di model
                    'facebook_url' => 'https://facebook.com/unit-' . str()->slug($unit->name),
                ]
            );
        });
    }
}
