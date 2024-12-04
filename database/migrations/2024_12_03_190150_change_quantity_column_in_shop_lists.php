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
        Schema::table('shop_lists', function (Blueprint $table) {
            $table->string('quantity')->change(); // 文字列型（VARCHAR）に変更
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_lists', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change(); // 元のデータ型に戻す
        });
    }
};
