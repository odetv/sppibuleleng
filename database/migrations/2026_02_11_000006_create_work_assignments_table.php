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

            // Relasi ke SK (Nullable: relawan tidak selalu punya SK)
            $table->foreignId('id_assignment_decree')->nullable()->constrained('assignment_decrees', 'id_assignment_decree')->nullOnDelete();

            // Relasi ke Unit SPPG (Nullable: posisi non-unit seperti SPPI tidak harus punya unit)
            $table->string('id_sppg_unit')->nullable();
            $table->foreign('id_sppg_unit')
                ->references('id_sppg_unit')
                ->on('sppg_units')
                ->nullOnDelete();

            $table->timestamps();
        });

        // Tambahkan foreign key ke tabel persons (Circular dependency handling)
        Schema::table('persons', function (Blueprint $table) {
            $table->foreign('id_work_assignment')
                ->references('id_work_assignment')
                ->on('work_assignments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['id_work_assignment']);
        });
        Schema::dropIfExists('work_assignments');
    }
};
