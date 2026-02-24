<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    /**
     * Relasi Polymorphic ke SocialMedia secara Horizontal.
     */
    public function socialMedia(): MorphOne
    {
        // 'socialable' harus sama dengan yang ada di Person.php dan Migration nanti
        return $this->morphOne(SocialMedia::class, 'socialable');
    }
}