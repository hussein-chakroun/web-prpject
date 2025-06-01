<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart_items extends Model
{
    protected $table = 'cart_items';
 
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    // In App\Models\CartItem.php
public function product()
{
    return $this->belongsTo(Product::class);
}

public function cart(){

    return $this->hasOne(Cart::class);
}

}
