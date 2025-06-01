<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Cart extends Model
{
    protected $table = 'cart';
 
   protected $fillable = ['user_id'];

   
public function cartItems()
{
    return $this->hasMany(Cart_items::class);
}

public function user(){

    return $this->hasOne(User::class);
}

}
