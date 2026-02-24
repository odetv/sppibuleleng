<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAssignment extends Model
{
    protected $table = 'work_assignments';
    protected $primaryKey = 'id_work_assignment';

    // Sertakan id_work_assignment jika Anda menginputnya manual di seeder
    protected $fillable = [
        'id_work_assignment',
        'id_assignment_decree',
        'id_sppg_unit'
    ];

    /**
     * Relasi ke Surat Keputusan (Parent)
     */
    public function decree(): BelongsTo
    {
        return $this->belongsTo(AssignmentDecree::class, 'id_assignment_decree', 'id_assignment_decree');
    }

    /**
     * Relasi ke Unit SPPG (Parent)
     * Karena SppgUnit menggunakan ID string, pastikan model SppgUnit sudah set incrementing = false
     */
    public function sppgUnit(): BelongsTo
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }
}
