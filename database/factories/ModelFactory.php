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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $title = rtrim($faker->unique()->sentence(mt_rand(3, 15)), '.');
    return [
        'title' => $title,
        'slug' => str_slug($title),
        'content' => $faker->paragraphs(mt_rand(1, 15), true),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'type_id' => function () {
            return factory(App\Type::class)->create()->id;
        }
    ];
});

$factory->define(App\Type::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->word;
    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});


