<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAssignment extends Model
{
    protected $table = 'work_assignments';
    protected $primaryKey = 'id_work_assignment';
    protected $fillable = ['id_assignment_decree', 'id_sppg_unit'];

    public function decree(): BelongsTo
    {
        return $this->belongsTo(AssignmentDecree::class, 'id_assignment_decree', 'id_assignment_decree');
    }

    public function sppgUnit(): BelongsTo
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }
}