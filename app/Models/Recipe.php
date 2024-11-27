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
        return $this->belongsToMany(Content::class, 'content_recipe');
    }

    public function seasonings()
    {
        return $this->belongsToMany(Content::class, 'seasoning_recipe');
    }
}
