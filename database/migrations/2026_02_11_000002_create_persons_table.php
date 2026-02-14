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

            // --- TAMBAHKAN RELASI KE POSISI DISINI ---
            $table->foreignId('id_ref_position')
                ->nullable() // Boleh kosong jika user baru mendaftar belum punya jabatan
                ->constrained('ref_positions', 'id_ref_position')
                ->onDelete('set null'); // Jika data posisi dihapus, person tetap ada tapi jabatannya kosong
            // ----------------------------------------

            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16);
            $table->string('name');
            $table->string('npwp');
            $table->string('photo');
            $table->string('title_education');
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
            $table->string('gps_coordinates');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
