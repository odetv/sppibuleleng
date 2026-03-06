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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->string('id_beneficiary')->primary();
            $table->string('id_sppg_unit')->nullable();
            $table->enum('group_type', ['Sekolah', 'Posyandu'])->nullable();
            $table->string('category')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->enum('ownership_type', ['Negeri', 'Swasta'])->nullable();
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude_gps', 10, 8)->nullable();
            $table->decimal('longitude_gps', 11, 8)->nullable();
            $table->integer('small_portion_male')->nullable();
            $table->integer('small_portion_female')->nullable();
            $table->integer('large_portion_male')->nullable();
            $table->integer('large_portion_female')->nullable();
            $table->integer('teacher_portion')->nullable();
            $table->integer('staff_portion')->nullable();
            $table->integer('cadre_portion')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone')->nullable();
            $table->string('pic_email')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamps();

            $table->foreign('id_sppg_unit')->references('id_sppg_unit')->on('sppg_units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
