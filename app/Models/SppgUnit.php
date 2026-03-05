<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SppgUnit extends Model
{
    protected $table = 'sppg_units';
    protected $primaryKey = 'id_sppg_unit';

    // PENTING: ID diinput manual & bertipe string
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sppg_unit',
        'code_sppg_unit',
        'name',
        'status',
        'operational_date',
        'photo',
        'province',
        'regency',
        'district',
        'village',
        'address',
        'latitude_gps',
        'longitude_gps',
        'leader_id',
        'nutritionist_id',
        'accountant_id',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'leader_id', 'id_person');
    }

    public function nutritionist(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'nutritionist_id', 'id_person');
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'accountant_id', 'id_person');
    }

    public function socialMedia(): MorphOne
    {
        return $this->morphOne(SocialMedia::class, 'socialable');
    }

    public function workAssignments(): HasMany
    {
        return $this->hasMany(WorkAssignment::class, 'id_sppg_unit', 'id_sppg_unit');
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class, 'id_sppg_unit', 'id_sppg_unit');
    }

    /**
     * Sinkronisasi personil unit dengan tabel persons.
     * Algoritma: Saat ini unit ini berisi leader/nutritionist/accountant tertentu.
     * Pastikan data persons mencerminkan penugasan ini (dan hapus dari unit lain jika perlu).
     * Bekerja TANPA bergantung pada WorkAssignment.
     */
    /**
     * Sinkronisasi personil unit dengan tabel persons.
     * Dipanggil dari SppgUnitController setelah update.
     * Bekerja TANPA bergantung pada WorkAssignment.
     */
    public function syncPersonnel()
    {
        $unitId = $this->id_sppg_unit;

        $columns = [
            'leader_id'       => 'kasppg',
            'nutritionist_id' => 'ag',
            'accountant_id'   => 'ak',
        ];

        foreach ($columns as $column => $slug) {
            $newPersonId = $this->{$column};
            $position    = \App\Models\RefPosition::where('slug_position', $slug)->first();

            if (!$position) continue;

            // Cari WorkAssignment yang sesuai dengan tipe jabatan ini untuk unit ini
            $workAssignment = \App\Models\WorkAssignment::join('assignment_decrees', 'work_assignments.id_assignment_decree', '=', 'assignment_decrees.id_assignment_decree')
                ->where('work_assignments.id_sppg_unit', $unitId)
                ->where('assignment_decrees.type_sk', $position->id_ref_position)
                ->select('work_assignments.*')
                ->first();

            // ── Langkah 1: Jika ada WA yang cocok, bersihkan orang lain di jabatan ini yang terhubung ke WA ini ──
            if ($workAssignment) {
                \App\Models\Person::where('id_work_assignment', $workAssignment->id_work_assignment)
                    ->where('id_ref_position', $position->id_ref_position)
                    ->when($newPersonId, fn($q) => $q->where('id_person', '!=', $newPersonId))
                    ->update([
                        'id_work_assignment' => null,
                        'id_ref_position'    => null,
                    ]);
            }

            if (!$newPersonId) continue;

            // ── Langkah 2: Bersihkan unit LAIN yang masih mencantumkan orang ini ──
            $otherUnits = \App\Models\SppgUnit::where('id_sppg_unit', '!=', $unitId)
                ->where(function($q) use ($newPersonId) {
                    $q->where('leader_id', $newPersonId)
                      ->orWhere('nutritionist_id', $newPersonId)
                      ->orWhere('accountant_id', $newPersonId);
                })->get();

            foreach ($otherUnits as $otherUnit) {
                $otherUnit->update([
                    'leader_id'       => $otherUnit->leader_id       == $newPersonId ? null : $otherUnit->leader_id,
                    'nutritionist_id' => $otherUnit->nutritionist_id == $newPersonId ? null : $otherUnit->nutritionist_id,
                    'accountant_id'   => $otherUnit->accountant_id   == $newPersonId ? null : $otherUnit->accountant_id,
                ]);
            }

            // ── Langkah 3: Tetapkan jabatan & penugasan ke person ini ──
            \App\Models\Person::where('id_person', $newPersonId)->update([
                'id_ref_position'    => $position->id_ref_position,
                'id_work_assignment' => $workAssignment?->id_work_assignment,
            ]);
        }
    }
}
