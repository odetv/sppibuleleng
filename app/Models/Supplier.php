<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'type_supplier',
        'name_supplier',
        'leader_name',
        'phone',
        'commodities',
        'province',
        'regency',
        'district',
        'village',
        'address',
        'postal_code',
    ];

    /**
     * The SPPG units that belong to the supplier.
     */
    public function sppgUnits(): BelongsToMany
    {
        return $this->belongsToMany(
            SppgUnit::class,
            'sppg_unit_supplier',
            'id_supplier',
            'id_sppg_unit'
        )->withTimestamps();
    }
}
