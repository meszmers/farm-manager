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
        Schema::create('farm_animals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('animal_id');
            $table->timestamps();

            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('farm_id')->references('id')->on('farms');

            $table->unique(['farm_id', 'animal_id']);
            $table->unique('animal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_animals');
    }
};
