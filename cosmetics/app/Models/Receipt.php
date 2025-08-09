<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'receipt_total',
        'status',
    ];
    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class);
    }
    public function receiptItems(){
        return $this->hasMany(ReceiptItem::class);
    }
}
