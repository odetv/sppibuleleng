<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();

            /** * morphs('socialable') akan otomatis menciptakan 2 kolom:
             * 1. socialable_id (BigInt) -> ID dari Person atau SppgUnit
             * 2. socialable_type (String) -> Nama class modelnya
             */
            $table->morphs('socialable');

            // Kolom horizontal sesuai permintaan Anda
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
