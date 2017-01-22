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

$factory->define(App\User::class, function ($faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => app('hash')->make($faker->password),
    ];
});

$factory->define(App\Priority::class, function ($faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\Task::class, function ($faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'due_date' => $faker->dateTime(),
        'priority_id' => function() {
            return factory(App\Priority::class)->create()->id;
        }
    ];
});
