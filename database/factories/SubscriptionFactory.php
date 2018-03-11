<?php
use Faker\Factory as Faker;

$faker = Faker::create('en_GB');

$factory->define(App\Subscription::class, function ($faker) {
    return [
        'msisdn' => '07535999111',
        'product_id' => 'productid1',
        'active' => 1
    ];
});

$factory->state(App\Subscription::class, 'unsubscibed', [
    'active' => 0,
]);

$factory->state(App\Subscription::class, 'alias_msisdn', [
    'msisdn' => 'A56A75CC5C8C2',
]);
