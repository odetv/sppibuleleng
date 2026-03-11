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
        Schema::create('pics', function (Blueprint $table) {
            $table->id('id_pic');
            $table->string('name_pic');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable(); // Jabatan di instansi/yayasan
            
            // Link to partner or foundation (Optional)
            $table->unsignedBigInteger('id_foundation')->nullable();
            $table->unsignedBigInteger('id_partner')->nullable();
            
            $table->foreign('id_foundation')->references('id_foundation')->on('foundations')->nullOnDelete();
            $table->foreign('id_partner')->references('id_partner')->on('partners')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pics');
    }
};
