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
        Schema::create('salles_de_sport', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->string('nom');
            $table->string('nom_proprietaire');
            $table->string('rccm');
            $table->string('adresse');
            $table->time('horaires');
            $table->string('type_sport');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles_de_sport');
    }
};
