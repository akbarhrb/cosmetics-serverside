<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['cat_name'];

    public function items(){
        $this->hasMany(Item::class);
    }
}
