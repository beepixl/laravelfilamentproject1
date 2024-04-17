<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable =[
        'order_id',
        'item_id',
        'qty',
        'price',
        'subtotal',
    ]; 


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
