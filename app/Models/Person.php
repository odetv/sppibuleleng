<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $table = 'persons';
    protected $primaryKey = 'id_person';

    /**
     * EAGER LOADING OTOMATIS
     * Ini akan memastikan data social_media selalu ada saat objek 
     * diubah menjadi JSON untuk modal @json($person)
     */
    protected $with = ['socialMedia', 'position', 'workAssignment'];

    protected $fillable = [
        'id_ref_position',
        'id_work_assignment',
        'nik',
        'no_kk',
        'nip',
        'npwp',
        'name',
        'photo',
        'title_education',
        'last_education',
        'major_education',
        'clothing_size',
        'shoe_size',
        'batch',
        'employment_status',
        'payroll_bank_name',
        'payroll_bank_account_number',
        'payroll_bank_account_name',
        'gender',
        'place_birthday',
        'date_birthday',
        'age',
        'religion',
        'marital_status',

        // Field Baru BPJS
        'no_bpjs_kes',
        'no_bpjs_tk',

        // Field Baru KTP
        'village_ktp',
        'district_ktp',
        'regency_ktp',
        'province_ktp',
        'address_ktp',

        // Field Baru Domicile
        'village_domicile',
        'district_domicile',
        'regency_domicile',
        'province_domicile',
        'address_domicile',
        'latitude_gps_domicile',
        'longitude_gps_domicile',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'id_ref_position', 'id_ref_position');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_person', 'id_person');
    }

    public function workAssignment(): BelongsTo
    {
        return $this->belongsTo(WorkAssignment::class, 'id_work_assignment', 'id_work_assignment');
    }

    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }

    /**
     * Sinkronisasi data person dengan unit SPPG setelah profil/penugasan diubah.
     * Dipanggil dari UserController saat admin mengubah penugasan di manage-user.
     */
    public function syncWithUnit()
    {
        $idPerson = $this->id_person;
        $posSlug  = $this->position?->slug_position;
        $mapping  = ['kasppg' => 'leader_id', 'ag' => 'nutritionist_id', 'ak' => 'accountant_id'];

        // 1. Bersihkan person ini dari SEMUA unit yang saat ini mencantumkannya
        \App\Models\SppgUnit::where('leader_id', $idPerson)
            ->orWhere('nutritionist_id', $idPerson)
            ->orWhere('accountant_id', $idPerson)
            ->each(function($u) use ($idPerson) {
                $u->update([
                    'leader_id'       => $u->leader_id       == $idPerson ? null : $u->leader_id,
                    'nutritionist_id' => $u->nutritionist_id == $idPerson ? null : $u->nutritionist_id,
                    'accountant_id'   => $u->accountant_id   == $idPerson ? null : $u->accountant_id,
                ]);
            });

        // 2. Jika tidak ada penugasan atau jabatan yang relevan, selesai
        if (!$this->id_work_assignment || !$posSlug || !isset($mapping[$posSlug])) {
            return;
        }

        // 3. Dapatkan unit tujuan dari WorkAssignment
        $wa   = $this->workAssignment;
        $unit = $wa?->sppgUnit;

        if (!$unit) return;

        $column = $mapping[$posSlug];

        // 4. Jika slot di unit tujuan sudah dipakai orang lain, kosongkan orang lain itu
        $otherPersonId = $unit->{$column};
        if ($otherPersonId && $otherPersonId != $idPerson) {
            \App\Models\Person::where('id_person', $otherPersonId)->update([
                'id_work_assignment' => null,
                'id_ref_position'    => null,
            ]);
        }

        // 5. Tetapkan diri sendiri di unit tersebut
        $unit->update([$column => $idPerson]);
    }
}
