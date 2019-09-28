<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email
    ];
});

$factory->define(App\Checklist::class, function (Faker\Generator $faker) {
    return [
        'object_domain' => $faker->word, 
        'object_id' => $faker->biasedNumberBetween(1,3),
        'description' => $faker->text,
        'is_completed' => false,
        'due' => null,
        'urgency' => 1,
        'completed_at' => null,
        'updated_by' => null,
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->word, 
        'due' => null, 
        'urgency' => $faker->biasedNumberBetween(1,10), 
        'assignee_id' => $sve = $faker->biasedNumberBetween(1,10)
    ];
});

$factory->define(App\Template::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});