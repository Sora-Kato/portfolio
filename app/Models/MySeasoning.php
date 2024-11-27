<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySeasoning extends Model
{
    use HasFactory;

    protected $table = 'myseasonings'; // テーブル名を指定
    protected $fillable = ['mySeasoning_id','mySeasoning_name']; // マスアサインメントを許可するカラムを指定

    protected $primaryKey = 'mySeasoning_id'; // 主キーを明示的に指定
    public $incrementing = true; // 自動増分を使用する
}
