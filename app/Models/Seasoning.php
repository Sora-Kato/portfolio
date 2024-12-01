<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seasoning extends Model
{
    use HasFactory;

    protected $table = 'seasonings'; // テーブル名を指定
    protected $fillable = ['seasoning_id','seasoning_name']; // マスアサインメントを許可するカラムを指定
    protected $primaryKey = 'seasoning_id'; // 主キーを明示的に指定
    public $incrementing = true; // 自動増分を使用する

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'seasoning_recipe');
    }

}
