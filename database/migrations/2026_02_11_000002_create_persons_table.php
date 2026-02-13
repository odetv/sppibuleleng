<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id('id_person');
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16);
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('title_education')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('place_birthday');
            $table->date('date_birthday');
            $table->integer('age');
            $table->string('religion');
            $table->string('marital_status');
            $table->string('village');    // Desa/Kelurahan
            $table->string('district');   // Kecamatan
            $table->string('regency');    // Kabupaten
            $table->string('province');   // Provinsi
            $table->text('address');
            $table->string('gps_coordinates')->nullable();
            $table->string('npwp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};