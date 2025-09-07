<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    protected $fillable = [
        'receipt_id',
        'item_id',
        'notes',
        'price',
        'quantity',
        'total'
    ];
    public function receipt(){
        return $this->belongsTo(Receipt::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }
}
