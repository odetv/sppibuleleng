<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_person',
        'id_ref_role', // Diperbarui dari id_ref_person_role
        'phone',
        'email',
        'password',
        'status_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel ref_roles
     * Menghubungkan akun ke hak aksesnya (Administrator, Author, dll)
     */
    public function role(): BelongsTo
    {
        // Diperbarui merujuk ke model RefRole dan foreign key id_ref_role
        return $this->belongsTo(RefRole::class, 'id_ref_role', 'id_ref_role');
    }

    /**
     * Relasi ke tabel persons
     * Menghubungkan akun ke data profil manusia
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'id_person', 'id_person');
    }
}
