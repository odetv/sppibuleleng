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
        'province',
        'regency',
        'district',
        'village',
        'address',
        'latitude_gps',
        'longitude_gps',
        'leader_id',
        'nutritionist_id',
        'accountant_id',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'leader_id', 'id_person');
    }

    public function nutritionist(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'nutritionist_id', 'id_person');
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'accountant_id', 'id_person');
    }

    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }
}
