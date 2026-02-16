<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SppgUnit extends Model
{
    protected $table = 'sppg_units';
    protected $primaryKey = 'id_sppg_unit';
    protected $fillable = [
        'code_sppg',
        'no_sppg',
        'district',
        'regency',
        'city',
        'address',
        'date_ops',
        'name'
    ];
}