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
        Schema::create('sppg_unit_supplier', function (Blueprint $table) {
            $table->id('id_sppg_unit_supplier');
            $table->string('id_sppg_unit');
            $table->unsignedBigInteger('id_supplier');
            
            $table->foreign('id_sppg_unit')->references('id_sppg_unit')->on('sppg_units')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppg_unit_supplier');
    }
};
