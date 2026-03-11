<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    protected $table = 'foundations';
    protected $primaryKey = 'id_foundation';

    protected $fillable = [
        'name_foundation',
        'leader_name',
        'phone',
        'email',
        'province',
        'regency',
        'district',
        'village',
        'address',
    ];

    public function pics()
    {
        return $this->hasMany(Pic::class, 'id_foundation', 'id_foundation');
    }
}
