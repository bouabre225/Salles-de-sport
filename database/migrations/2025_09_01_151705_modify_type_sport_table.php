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
        Schema::table('salles_de_sport', function (Blueprint $table) {
            $table->dropColumn('type_sport');;
            $table->foreignId('type_sport')->constrained('salles_de_sport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salles_de_sport', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type_sport');
            $table->string('type_sport');
        });
    }
};
