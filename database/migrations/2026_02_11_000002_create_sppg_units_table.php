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
            $table->id('id_sppg_unit');
            $table->string('code_sppg')->unique();
            $table->string('name');
            $table->string('no_sppg');
            $table->string('district');
            $table->string('regency');
            $table->string('city');
            $table->text('address');
            $table->date('date_ops');
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
