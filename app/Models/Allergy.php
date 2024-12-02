<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = ['allergy_name'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_allergy', 'allergy_id', 'recipe_id');
    }
}
