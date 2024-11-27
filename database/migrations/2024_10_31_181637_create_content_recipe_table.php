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
        Schema::create('content_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            $table->unsignedBigInteger('recipe_id');
            $table->foreign('content_id')->references('content_id')->on('contents')->onDelete('cascade');
            $table->foreign('recipe_id')->references('recipe_id')->on('recipes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_recipe');
    }
};
