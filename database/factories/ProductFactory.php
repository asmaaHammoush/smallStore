<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomFloat(2, 1, 1000), // توليد سعر عشوائي بين 1 و 1000
        'quantity' => $faker->numberBetween(1, 100), // توليد كمية عشوائية بين 1 و 100
        'category_id' => Category::inRandomOrder()->first()->id, // اختيار تصنيف عشوائي من الجدول
    ];
});

