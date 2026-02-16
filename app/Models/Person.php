<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'village',
        'district',
        'regency',
        'province',
        'address',
        'gps_coordinates',
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
}