<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use SoftDeletes;

    protected $table = 'persons';
    protected $primaryKey = 'id_person';

    /**
     * fillable harus menyertakan id_ref_position agar data jabatan 
     * bisa disimpan, dan id_user dihapus karena kolomnya sudah 
     * pindah ke tabel users.
     */
    protected $fillable = [
        'id_ref_position',
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
     * Relasi ke Tabel Posisi (Jabatan)
     * Ini yang dibutuhkan agar Sidebar bisa menampilkan 'Korwil', 'SPPI', dll.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'id_ref_position', 'id_ref_position');
    }

    /**
     * Relasi ke User
     * Karena di database Anda id_person ada di tabel 'users',
     * maka model Person 'hasOne' (memiliki satu) User. 
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_person', 'id_person');
    }
}
