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
        'date',
        'totalamount',
 ]; 

 public function orderItemsDetails(): HasMany
 {
     return $this->hasMany(OrderItems::class,'id');
 }
  
 public function orderPayment(): HasMany
 {
     return $this->hasMany(OrderPayment::class,'id');
 }

}
