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

            // Mendefinisikan morph secara manual agar ID mendukung STRING
            $table->string('socialable_id');   // Gunakan string, bukan integer
            $table->string('socialable_type'); // Menyimpan nama class model
            $table->index(['socialable_id', 'socialable_type']); // Index untuk performa

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
