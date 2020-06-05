<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Courses_column;
use Faker\Generator as Faker;

$factory->define(Courses_column::class, function (Faker $faker) {
    $letters = ['A', 'B', 'C', 'D'];
    $code =
        $letters[mt_rand(0, count($letters) - 1)] .
        '' .
        $letters[mt_rand(0, count($letters) - 1)] .
        '' .
        $letters[mt_rand(0, count($letters) - 1)];
    return [
        'course_id' => factory(\App\Course::class)->create(),
        'short_desc' => $faker->sentence,
        'course_code' => $code,
    ];
});