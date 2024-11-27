<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';
    protected $primaryKey = 'content_id'; // 主キーを明示的に指定
    public $incrementing = true; // 自動増分を使用する
    protected $fillable = ['name'];

    public function fridges()
    {
        return $this->hasMany(Fridge::class, 'fridge_id', 'content_id');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'content_recipe');
    }
}
