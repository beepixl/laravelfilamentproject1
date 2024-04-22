<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory; 

    protected $fillable =[
        'user_id',
        'status',
        'payment_status', 
        'payment_method',
        'date',
        'totalamount', 
        'address',
        'city', 
        'state',
        'country',
      
 ]; 

 public function orderItemsDetails(): HasMany
 {
     return $this->hasMany(OrderItems::class,'order_id')->with('product');
 }
  
 public function orderPayment(): HasMany
 {
     return $this->hasMany(OrderPayment::class,'order_id');
 }

}
