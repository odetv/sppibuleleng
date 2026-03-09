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

    public function sppgOfficer(): HasOne
    {
        return $this->hasOne(SppgOfficer::class, 'id_person', 'id_person');
    }

    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }

    /**
     * Sinkronisasi data Person ke SppgUnit (jika jabatan inti) 
     * dan ke SppgOfficer (Manage Officer).
     */
    public function syncWithUnit($forcedUnitId = 'use_existing')
    {
        $idPerson = $this->id_person;
        $posSlug  = $this->position?->slug_position;
        $mapping  = ['kasppg' => 'leader_id', 'ag' => 'nutritionist_id', 'ak' => 'accountant_id'];

        // 1. Bersihkan dulu slot di Unit manapun jika person ini adalah Kasppg/AG/AK sebelumnya
        \App\Models\SppgUnit::where('leader_id', $idPerson)->update(['leader_id' => null]);
        \App\Models\SppgUnit::where('nutritionist_id', $idPerson)->update(['nutritionist_id' => null]);
        \App\Models\SppgUnit::where('accountant_id', $idPerson)->update(['accountant_id' => null]);

        // 2. Tentukan Target Unit ID secara ketat
        $targetSppgUnitId = null;

        if ($forcedUnitId !== 'use_existing') {
            // Jika ada paksaan (dari controller), patuhi sepenuhnya (bisa null/none untuk lepas penugasan)
            $targetSppgUnitId = ($forcedUnitId === 'none' || !$forcedUnitId) ? null : $forcedUnitId;
        } else {
            // Jika tidak dipaksa, coba cari dari SK atau data lama
            if ($this->id_work_assignment && $this->workAssignment) {
                $targetSppgUnitId = $this->workAssignment->id_sppg_unit;
            } else if ($this->sppgOfficer) {
                $targetSppgUnitId = $this->sppgOfficer->id_sppg_unit;
            }
        }

        // 3. Update Slot di SppgUnit jika Jabatan Inti
        $isCore = $posSlug && isset($mapping[$posSlug]);
        if ($isCore && $targetSppgUnitId) {
            $targetSppgUnit = \App\Models\SppgUnit::find($targetSppgUnitId);
            if ($targetSppgUnit) {
                $column = $mapping[$posSlug];
                
                // Jika slot di unit tujuan sudah dipakai orang lain, kosongkan orang lain itu
                $otherPersonId = $targetSppgUnit->{$column};
                if ($otherPersonId && $otherPersonId != $idPerson) {
                    \App\Models\Person::where('id_person', $otherPersonId)->update([
                        'id_work_assignment' => null,
                        'id_ref_position'    => null,
                    ]);
                }
                // Set person ini ke slot unit tujuan
                $targetSppgUnit->update([$column => $idPerson]);
            }
            
            // Sync SK: Jika unit di SK berbeda dengan unit yang dipetakan, kosongkan link SK agar sinkron
            if ($this->id_work_assignment && $this->workAssignment && $this->workAssignment->id_sppg_unit != $targetSppgUnitId) {
                $this->update(['id_work_assignment' => null]);
                $this->id_work_assignment = null;
                $this->load('workAssignment');
            }
        }

        // 4. Sinkronisasi ke Tabel sppg_officers (Pintu Utama Manage Officer)
        $nonOfficerSlugs = ['sppi', 'korwil', 'korcam', 'none'];
        if ($posSlug && !in_array($posSlug, $nonOfficerSlugs)) {
            $isActive = $this->sppgOfficer ? $this->sppgOfficer->is_active : true;
            \App\Models\SppgOfficer::updateOrCreate(
                ['id_person' => $idPerson],
                [
                    'id_ref_position' => $this->id_ref_position,
                    'id_sppg_unit'    => $targetSppgUnitId,
                    'is_active'       => $isActive,
                    'daily_honor'     => $this->sppgOfficer->daily_honor ?? 100000 
                ]
            );
        } else if ($this->sppgOfficer) {
            $this->sppgOfficer->delete();
        }
    }
}
