<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'ref_person_roles';
    protected $primaryKey = 'id_ref_person_role';
    protected $fillable = [
        'name_role',
        'slug_role',
    ];
}