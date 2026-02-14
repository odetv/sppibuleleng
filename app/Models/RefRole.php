<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRole extends Model
{
    // Sesuaikan dengan nama tabel baru
    protected $table = 'ref_roles';

    // Sesuaikan dengan primary key baru
    protected $primaryKey = 'id_ref_role';

    // Kita gunakan guarded kosong agar lebih fleksibel saat development
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
