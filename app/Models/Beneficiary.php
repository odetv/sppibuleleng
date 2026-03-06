<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $primaryKey = 'id_beneficiary';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_beneficiary',
        'id_sppg_unit',
        'group_type',
        'category',
        'code',
        'name',
        'ownership_type',
        'province',
        'regency',
        'district',
        'village',
        'address',
        'postal_code',
        'latitude_gps',
        'longitude_gps',
        'small_portion_male',
        'small_portion_female',
        'large_portion_male',
        'large_portion_female',
        'teacher_portion',
        'staff_portion',
        'cadre_portion',
        'pic_name',
        'pic_phone',
        'pic_email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude_gps' => 'decimal:8',
        'longitude_gps' => 'decimal:8',
        'small_portion_male' => 'integer',
        'small_portion_female' => 'integer',
        'large_portion_male' => 'integer',
        'large_portion_female' => 'integer',
        'teacher_portion' => 'integer',
        'staff_portion' => 'integer',
        'cadre_portion' => 'integer',
    ];

    public function sppgUnit(): BelongsTo
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }
}
