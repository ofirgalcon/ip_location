<?php

// Database seeder
// Please visit https://github.com/fzaninotto/Faker for more options

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Ip_location_model::class, function (Faker\Generator $faker) {

    return [
        'ip' => $faker->word(),
        'hostname' => $faker->word(),
        'city' => $faker->word(),
        'region' => $faker->word(),
        'country' => $faker->word(),
        'location' => $faker->word(),
        'organization' => $faker->word(),
        'postal_code' => $faker->word(),
        'timezone' => $faker->word(),
    ];
});
