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

            // --- RELASI JABATAN & PENUGASAN ---
            $table->foreignId('id_ref_position')
                ->nullable()
                ->constrained('ref_positions', 'id_ref_position')
                ->onDelete('set null');

            $table->unsignedBigInteger('id_work_assignment')->nullable();
            // ----------------------------------

            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16)->nullable();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('npwp')->nullable();
            $table->string('photo')->nullable();
            $table->string('title_education')->nullable();
            $table->enum('last_education', ['D-III', 'D-IV', 'S-1'])->nullable();
            $table->string('major_education')->nullable();
            $table->enum('clothing_size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', '4XL', '5XL', '6XL', '7XL', '8XL', '9XL', '10XL'])->nullable();
            $table->enum('shoe_size', ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'])->nullable();
            $table->enum('batch', ['1', '2', '3', 'Non-SPPI'])->nullable();
            $table->enum('employment_status', ['ASN', 'Non-ASN'])->nullable();
            $table->enum('payroll_bank_name', ['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'])->nullable();
            $table->string('payroll_bank_account_number')->nullable();
            $table->string('payroll_bank_account_name')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('place_birthday');
            $table->date('date_birthday');
            $table->integer('age');
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();

            // --- FIELD BARU BPJS (NULLABLE) ---
            $table->string('no_bpjs_kes')->nullable();
            $table->string('no_bpjs_tk')->nullable();

            // --- FIELD ALAMAT KTP ---
            $table->string('village_ktp');
            $table->string('district_ktp');
            $table->string('regency_ktp');
            $table->string('province_ktp');
            $table->text('address_ktp');

            // --- FIELD ALAMAT DOMISILI ---
            $table->string('village_domicile');
            $table->string('district_domicile');
            $table->string('regency_domicile');
            $table->string('province_domicile');
            $table->text('address_domicile');

            // GPS DOMISILI (Decimal lebih disarankan untuk koordinat)
            $table->decimal('latitude_gps_domicile', 10, 8)->nullable()->default(0);
            $table->decimal('longitude_gps_domicile', 11, 8)->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
