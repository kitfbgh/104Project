<?php

namespace Database\Factories;

use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'category' => Str::random(10),
        'origin_price' => strval(rand(1, 3000)),
        'price' => strval(rand(1, 3000)),
        'unit' => Str::random(10),
        'description' => Str::random(20), // password
        'content' => Str::random(20),
        'quantity' => strval(rand(1, 300)),
        'imageUrl' => null,
        'image' => null,
    ];
});
