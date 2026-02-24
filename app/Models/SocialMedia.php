<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialMedia extends Model
{
    // Nama tabel secara eksplisit (opsional, tapi bagus untuk kejelasan)
    protected $table = 'social_media';

    protected $fillable = [
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'socialable_id',
        'socialable_type'
    ];

    /**
     * Mengambil model pemilik (Person atau SppgUnit).
     */
    public function socialable(): MorphTo
    {
        return $this->morphTo();
    }
}
