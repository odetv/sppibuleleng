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
        Schema::create('sppg_certifications', function (Blueprint $table) {
            $table->bigIncrements('id_sppg_certification');
            $table->string('id_sppg_unit');
            $table->enum('name_certification', ['SLHS', 'Halal', 'HACCP', 'Chef']);
            $table->string('certification_number');
            $table->string('issued_by');
            $table->date('issued_date');
            $table->date('start_date');
            $table->date('expiry_date');
            $table->string('file_certification');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('id_sppg_unit')->references('id_sppg_unit')->on('sppg_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppg_certifications');
    }
};
