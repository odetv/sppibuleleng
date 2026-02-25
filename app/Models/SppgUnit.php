<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppgUnit extends Model
{
    protected $table = 'sppg_units';
    protected $primaryKey = 'id_sppg_unit';

    // PENTING: ID diinput manual & bertipe string
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sppg_unit',
        'code_sppg_unit',
        'name',
        'status',
        'operational_date',
        'photo',
        'province',   // Pastikan ini ada
        'regency',    // Pastikan ini ada
        'district',   // Pastikan ini ada
        'village',    // Pastikan ini ada
        'address',
        'latitude_gps',  // Pastikan ini ada
        'longitude_gps', // Pastikan ini ada
        'leader_id'
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'leader_id', 'id_person');
    }

    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }
}
