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
        Schema::create('coachs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->string('nom');
            $table->string('prenom');
            $table->unsignedInteger('cip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coachs');
    }
};
