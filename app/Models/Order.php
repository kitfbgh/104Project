<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'status',
        'billing_email',
        'billing_address',
        'billing_name',
        'billing_phone',
        'billing_subtotal',
        'billing_total',
        'billing_tax',
        'comment',
        'user_id',
    ];

   public function user()
   {
       return $this->belongsTo(
           User::class,
           'user_id'
       );
   } 

   public function products()
   {
       return $this->belongsToMany(
           Product::class,
           'order_product',
           'order_id',
           'product_id',
       )->withPivot('quantity');
   }
}
