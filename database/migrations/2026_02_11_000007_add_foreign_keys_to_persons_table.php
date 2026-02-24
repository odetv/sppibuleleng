<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk menambahkan relasi foreign key.
     */
    public function up(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            // Pastikan kolom id_ref_position merujuk ke tabel ref_positions
            $table->foreign('id_ref_position')
                ->references('id_ref_position')
                ->on('ref_positions')
                ->onDelete('set null');

            // Pastikan kolom id_work_assignment merujuk ke tabel work_assignments
            $table->foreign('id_work_assignment')
                ->references('id_work_assignment')
                ->on('work_assignments')
                ->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi dengan menghapus constraint foreign key.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            // Menghapus foreign key berdasarkan nama kolomnya
            $table->dropForeign(['id_ref_position']);
            $table->dropForeign(['id_work_assignment']);
        });
    }
};
