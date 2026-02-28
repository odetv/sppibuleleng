<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssignmentDecree extends Model
{
    protected $table = 'assignment_decrees';
    protected $primaryKey = 'id_assignment_decree';

    protected $fillable = [
        'no_sk',
        'file_sk',
        'date_sk',
        'no_ba_verval',
        'date_ba_verval'
    ];

    /**
     * Relasi ke WorkAssignment (Satu SK bisa digunakan oleh banyak penugasan).
     */
    public function workAssignments(): HasMany
    {
        return $this->hasMany(WorkAssignment::class, 'id_assignment_decree', 'id_assignment_decree');
    }
}
