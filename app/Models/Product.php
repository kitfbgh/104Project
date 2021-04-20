<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'content',
        'price',
        'description',
        'quantity',
        'imageUrl',
        'image',
        'category',
        'unit',
        'origin_price',
        'size',
    ];

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'product_id',
            'category_id',
        );
    }

    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_product',
            'product_id',
            'order_id',
        );
    }
}
