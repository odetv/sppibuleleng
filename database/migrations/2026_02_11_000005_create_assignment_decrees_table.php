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
        Schema::create('assignment_decrees', function (Blueprint $table) {
            $table->id('id_assignment_decree');
            $table->string('no_sk')->unique();
            $table->date('date_sk');
            $table->string('no_ba_verval');
            $table->date('date_ba_verval');
            $table->string('file_sk')->nullable(); // Add file_sk column (nullable for import)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_decrees');
    }
};
