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
        Schema::create('sppg_units', function (Blueprint $table) {
            $table->string('id_sppg_unit')->primary();
            $table->string('code_sppg_unit')->nullable()->unique();
            $table->string('name')->nullable();
            $table->enum('status', [
                'Operasional',
                'Belum Operasional',
                'Tutup Sementara',
                'Tutup Permanen'
            ])->nullable();
            $table->date('operational_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude_gps', 10, 8)->nullable();
            $table->decimal('longitude_gps', 11, 8)->nullable();

            // Relasi ke tabel persons (Kepala SPPG)
            $table->unsignedBigInteger('leader_id')->nullable();
            $table->foreign('leader_id')->references('id_person')->on('persons')->nullOnDelete();

            $table->unsignedBigInteger('nutritionist_id')->nullable();
            $table->foreign('nutritionist_id')->references('id_person')->on('persons')->nullOnDelete();

            $table->unsignedBigInteger('accountant_id')->nullable();
            $table->foreign('accountant_id')->references('id_person')->on('persons')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppg_units');
    }
};
