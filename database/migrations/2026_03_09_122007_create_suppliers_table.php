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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('type_supplier'); // Koperasi Desa Merah Putih, Koperasi, Bumdes, Bumdesma, UMKM, Supplier Lain
            $table->string('name_supplier');
            $table->string('leader_name');
            $table->string('phone');
            $table->text('commodities'); // Can be stored as JSON or coma separated
            
            // Location
            $table->string('province');
            $table->string('regency');
            $table->string('district');
            $table->string('village');
            $table->text('address');
            $table->string('postal_code')->nullable();
            
            // GPS Location
            $table->string('latitude_gps')->nullable();
            $table->string('longitude_gps')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
