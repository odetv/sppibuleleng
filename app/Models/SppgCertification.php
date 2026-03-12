<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SppgCertification extends Model
{
    protected $table = 'sppg_certifications';
    protected $primaryKey = 'id_sppg_certification';

    protected $fillable = [
        'id_sppg_unit',
        'name_certification',
        'certification_number',
        'issued_by',
        'issued_date',
        'start_date',
        'expiry_date',
        'file_certification',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'issued_date' => 'date',
        'start_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function sppgUnit()
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }
}
