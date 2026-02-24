<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppgUnit extends Model
{
    protected $table = 'sppg_units';
    protected $primaryKey = 'id_sppg_unit';

    /**
     * PENTING: Karena ID menggunakan string unik (Contoh: 2DWFSVHQ).
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sppg_unit',
        'code_sppg_unit',
        'name',
        'status',
        'operational_date',
        'province',
        'regency',
        'district',
        'village',
        'address',
        'latitude_gps',
        'longitude_gps',
        'leader_id'
    ];

    /**
     * Relasi ke User (Pemimpin Unit)
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id', 'id_user');
    }

    /**
     * Relasi Polymorphic ke SocialMedia secara Horizontal.
     */
    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }
}
