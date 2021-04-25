<?php

namespace Database\Factories;

use App\Models\ContactUs;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(ContactUs::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => strval(rand(1000000000, 9999999999)),
        'subject' => Str::random(20),
        'message' => Str::random(50),
    ];
});
