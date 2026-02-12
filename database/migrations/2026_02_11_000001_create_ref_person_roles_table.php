<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_person_roles', function (Blueprint $table) {
            $table->id('id_ref_person_role');
            $table->string('name_role');
            $table->string('slug_role')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_person_roles');
    }
};