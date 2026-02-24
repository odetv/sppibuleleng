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

            // Relasi ke SK (Tetap foreignId karena di tabel asalnya biasanya bigInt auto-increment)
            $table->foreignId('id_assignment_decree')->constrained('assignment_decrees', 'id_assignment_decree');

            // Relasi ke Unit SPPG (Wajib String agar cocok dengan tabel sppg_units)
            $table->string('id_sppg_unit');
            $table->foreign('id_sppg_unit')
                ->references('id_sppg_unit')
                ->on('sppg_units')
                ->onDelete('cascade');

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
