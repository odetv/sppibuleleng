<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certifications';
    protected $primaryKey = 'id_certification';

    protected $fillable = [
        'id_sppg_unit',
        'name_certification',
        'certification_number',
        'issued_by',
        'issued_date',
        'expiry_date',
        'file_path',
        'status',
    ];

    public function sppgUnit()
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }
}
