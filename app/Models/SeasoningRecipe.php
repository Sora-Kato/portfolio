<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasoningRecipe extends Model
{
    use HasFactory;

    // 中間テーブル名を明示的に指定
    protected $table = 'seasoning_recipe';

    // 中間テーブルのカラムをfillableに追加
    protected $fillable = ['recipe_id', 'seasoning_id', 'quantity'];

}
