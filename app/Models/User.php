<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_person',
        'id_ref_person_role',
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
     * Relasi ke tabel ref_person_roles
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_ref_person_role', 'id_ref_person_role');
    }

    /**
     * Relasi ke tabel persons
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'id_person', 'id_person');
    }
}
