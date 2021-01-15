<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tax;
use Faker\Generator as Faker;

$factory->define(Tax::class, function (Faker $faker) {
    return [
        'year' => $faker->randomElement(['2020', '2021', '2019']),
        'pattern' => $faker->numberBetween(1, 5),
        'pattern_memo' => $faker->colorName,
        'income' => $faker->randomNumber(7, true),
        'expenses' => $faker->randomNumber(7, true),
        'declaration_flg' => $faker->numberBetween(1, 2),
        'deductions' => [
            [
                'deductionName' => 'test',
                'deductionMoney' => $faker->randomNumber(6, true),
            ],
            [
                'deductionName' => 'test1',
                'deductionMoney' => $faker->randomNumber(6, true),
            ],
            [
                'deductionName' => 'test2',
                'deductionMoney' => $faker->randomNumber(6, true),
            ],
            [
                'deductionName' => 'test3',
                'deductionMoney' => $faker->randomNumber(6, true),
            ],
            [
                'deductionName' => 'test4',
                'deductionMoney' => $faker->randomNumber(6, true),
            ],
        ],
        'insurance_fee_deduction' => 430000,
        'insurance_fee_income' => 9.5,
        'insurance_fee_equal' => 52000,
        'municipal_tax_income' => 10,
        'municipal_tax_equal' => 5000,
        'pension' => 16500,
    ];
});
