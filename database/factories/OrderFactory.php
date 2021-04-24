<?php

namespace Database\Factories;

use App\Models\Order;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'status' => '訂單已送出',
        'payment' => '貨到付款',
        'billing_email' => $faker->email,
        'billing_address' => Str::random(30),
        'billing_name' => Str::random(25), // password
        'billing_phone' => strval(rand(1000000000, 9999999999)),
        'comment' => Str::random(30),
        'billing_subtotal' => strval(rand(1, 10000)),
        'billing_tax' => strval(rand(1, 10000)),
        'billing_total' => strval(rand(1, 10000)),
    ];
});
