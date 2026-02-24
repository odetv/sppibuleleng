<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefPosition extends Model
{
    protected $table = 'ref_positions';
    protected $primaryKey = 'id_ref_position';
    protected $guarded = [];

    /**
     * Relasi ke Person: Satu jabatan bisa dimiliki banyak orang
     */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class, 'id_ref_position', 'id_ref_position');
    }
}
