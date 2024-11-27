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
        Schema::create('seasoning_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seasoning_id');
            $table->unsignedBigInteger('recipe_id');
            $table->foreign('seasoning_id')->references('seasoning_id')->on('seasonings')->onDelete('cascade');
            $table->foreign('recipe_id')->references('recipe_id')->on('recipes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasoning_recipe');
    }
};
