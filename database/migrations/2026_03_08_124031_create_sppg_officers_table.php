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
        Schema::create('sppg_officers', function (Blueprint $table) {
            $table->id('id_sppg_officer');
            $table->unsignedBigInteger('id_person');
            $table->string('id_sppg_unit');
            $table->unsignedBigInteger('id_ref_position');
            $table->integer('daily_honor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_person')->references('id_person')->on('persons')->onDelete('cascade');
            $table->foreign('id_sppg_unit')->references('id_sppg_unit')->on('sppg_units')->onDelete('cascade');
            $table->foreign('id_ref_position')->references('id_ref_position')->on('ref_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppg_officers');
    }
};
