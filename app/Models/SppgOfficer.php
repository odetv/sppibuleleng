<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppgOfficer extends Model
{
    protected $table = 'sppg_officers';
    protected $primaryKey = 'id_sppg_officer';

    protected $fillable = [
        'id_person',
        'id_sppg_unit',
        'id_ref_position',
        'is_active',
        'daily_honor',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'id_person', 'id_person');
    }

    public function sppgUnit(): BelongsTo
    {
        return $this->belongsTo(SppgUnit::class, 'id_sppg_unit', 'id_sppg_unit');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'id_ref_position', 'id_ref_position');
    }
}
