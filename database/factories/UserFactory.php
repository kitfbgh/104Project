<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => User::ROLE_USER,
        'email_verified_at' => now(),
        'password' => Hash::make('password'), // password
        'remember_token' => Str::random(10),
    ];
});
