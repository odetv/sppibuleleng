<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';
    protected $primaryKey = 'id_partner';

    protected $fillable = [
        'name_partner',
        'type_partner',
        'leader_name',
        'phone',
        'province',
        'regency',
        'district',
        'village',
        'address',
    ];

    public function pics()
    {
        return $this->hasMany(Pic::class, 'id_partner', 'id_partner');
    }
}
