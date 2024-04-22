<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'price',
        'description',
        'photo',
        'brand_id'
     
    ];

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subcategories(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class,'sub_category_id');
    } 

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
}
