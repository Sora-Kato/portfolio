<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fridge extends Model
{
    use HasFactory;

    // マスアサインメントを許可するカラムを指定
    protected $fillable = ['fridge_id', 'fridge_content'];// ここにカラム名を追加

    protected $primaryKey = 'fridge_id'; // 主キーを明示的に指定
    public $incrementing = true; // 自動増分を使用する
}
