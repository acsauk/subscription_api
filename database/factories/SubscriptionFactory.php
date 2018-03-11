<?php
use Faker\Factory as Faker;

$faker = Faker::create('en_GB');

$factory->define(App\Subscription::class, function ($faker)
{
    return [
        'msisdn' => '07535999111',
        'product_id' => 'productid1',
        'active' => 1
    ];
});

$factory->state(App\Subscription::class, 'unsubscribed', [
    'active' => 0,
    'subscribed_date' => $faker->dateTimeBetween($startDate = '-2 days', $endDate = '- 2 day')->format('Y-m-d H:i:s'),
    'unsubscribed_date' => $faker->dateTimeBetween($startDate = '-1 days', $endDate = '- 1 day')->format('Y-m-d H:i:s')
]);

$factory->state(App\Subscription::class, 'alias_msisdn', [
    'msisdn' => 'A56A75CC5C8C2',
]);
