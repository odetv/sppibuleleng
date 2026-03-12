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
        Schema::table('certifications', function (Blueprint $table) {
            if (!Schema::hasColumn('certifications', 'start_date')) {
                $table->date('start_date')->nullable()->after('issued_date');
            }
            if (Schema::hasColumn('certifications', 'file_path')) {
                $table->renameColumn('file_path', 'file_certification');
            }
        });

        // Change status to boolean
        Schema::table('certifications', function (Blueprint $table) {
            $table->boolean('status')->default(true)->change();
        });

        Schema::dropIfExists('sppg_certifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sppg_certifications', function (Blueprint $table) {
            $table->id('id_sppg_certification');
            $table->string('id_sppg_unit');
            $table->string('name_certification');
            $table->string('certification_number')->nullable();
            $table->string('issued_by')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_certification')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('id_sppg_unit')->references('id_sppg_unit')->on('sppg_units')->onDelete('cascade');
        });

        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->renameColumn('file_certification', 'file_path');
            $table->string('status')->default('Aktif')->change();
        });
    }
};
