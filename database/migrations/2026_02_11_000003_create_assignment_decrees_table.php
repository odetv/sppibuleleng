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
            $table->string('no_ba_verval')->nullable();
            $table->date('date_ba_verval')->nullable();
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
