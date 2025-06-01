<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 
    protected $table = 'products';
 
    protected $fillable = ['name', 'image','quantity','price','cost', 'description', 'enabled','category_id'];

    public function category(){

        return $this->belongsTo(Category::class);
    }

    public function cartitems(){

        return $this->hasOne(Cart_items::class);
    }

    public function orderitems(){

        return $this->hasOne(Order_items::class);
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->quantity == 0;
    }
    
}
