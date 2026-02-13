<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use SoftDeletes;

    protected $table = 'persons';
    protected $primaryKey = 'id_person';

    protected $fillable = [
        'id_user',
        'nik',
        'no_kk',
        'name',
        'photo',
        'title_education',
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
        'npwp'
    ];

    /**
     * Relasi balik ke User (Opsional tapi disarankan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}