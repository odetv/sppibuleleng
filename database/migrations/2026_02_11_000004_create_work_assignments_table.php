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
        Schema::create('work_assignments', function (Blueprint $table) {
            $table->id('id_work_assignment');
            // Menghubungkan ke SK dan Unit
            $table->foreignId('id_assignment_decree')->constrained('assignment_decrees', 'id_assignment_decree');
            $table->foreignId('id_sppg_unit')->constrained('sppg_units', 'id_sppg_unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_assignments');
    }
};
