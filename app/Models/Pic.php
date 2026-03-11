<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $table = 'pics';
    protected $primaryKey = 'id_pic';

    protected $fillable = [
        'name_pic',
        'phone',
        'email',
        'position',
        'id_foundation',
        'id_partner',
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class, 'id_foundation', 'id_foundation');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'id_partner', 'id_partner');
    }
}
