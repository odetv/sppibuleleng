<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRole extends Model
{
    protected $table = 'ref_roles';
    protected $primaryKey = 'id_ref_role';
    protected $guarded = [];

    /**
     * Relasi ke User
     * Satu Role bisa dimiliki oleh banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_ref_role', 'id_ref_role');
    }
}
