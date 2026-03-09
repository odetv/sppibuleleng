<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\SppgUnit;

class SocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sosmed untuk Person (KECUALI PETUGAS)
        \App\Models\Person::whereDoesntHave('sppgOfficer')->get()->each(function ($person) {
            // Random chance (e.g., 60% have sosmed)
            if (rand(1, 100) <= 60) {
                $person->socialMedia()->updateOrCreate(
                    ['socialable_id' => $person->id_person, 'socialable_type' => \App\Models\Person::class],
                    [
                        'facebook_url'  => 'https://facebook.com/' . str()->slug($person->name),
                        'instagram_url' => 'https://instagram.com/' . str()->slug($person->name),
                        'tiktok_url'   => rand(0, 1) ? 'https://tiktok.com/' . str()->slug($person->name) : null,
                    ]
                );
            }
        });

        // 2. Sosmed untuk Unit SPPG (Random juga)
        SppgUnit::all()->each(function ($unit) {
            if (rand(1, 100) <= 70) {
                $unit->socialMedia()->updateOrCreate(
                    ['socialable_id' => $unit->id_sppg_unit, 'socialable_type' => SppgUnit::class],
                    [
                        'facebook_url' => 'https://facebook.com/unit-' . str()->slug($unit->name),
                        'instagram_url' => rand(0, 1) ? 'https://instagram.com/unit-' . str()->slug($unit->name) : null,
                        'tiktok_url' => 'https://tiktok.com/unit-' . str()->slug($unit->name),
                    ]
                );
            }
        });
    }
}
