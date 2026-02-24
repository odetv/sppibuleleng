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
}
