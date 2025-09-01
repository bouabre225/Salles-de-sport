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
        Schema::create('messageries', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('photo_video');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs');
            $table->foreignId('coach_id')->constrained('coachs');
            $table->foreignId('salle_de_sport_id')->constrained('salles_de_sport');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messageries');
    }
};
