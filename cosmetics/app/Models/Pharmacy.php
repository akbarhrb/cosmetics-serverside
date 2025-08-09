<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'pharmacy_name',
        'pharmacy_owner',
        'phone_number',
        'address',
    ];
    public function receipts(){
        return $this->hasMany(Receipt::class);
    }
}
