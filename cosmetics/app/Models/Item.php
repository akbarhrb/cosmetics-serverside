<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'item_name',
        'item_color',
        'quantity',
        'price_unit_ind',
        'price_dozen',
        'price_unit_ph',
        'cost',
        'description',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
