<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_name', 'recipe_step'];

    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_recipe')
                    ->withPivot('quantity'); // 中間テーブルのquantityカラムを取得可能にする
    }

    public function seasonings()
    {
        return $this->belongsToMany(Seasoning::class, 'seasoning_recipe')
                    ->withPivot('quantity'); // 中間テーブルの quantity カラムを取得可能にする;
    }
}
