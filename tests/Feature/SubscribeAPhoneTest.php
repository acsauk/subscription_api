<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscription;

class SubscribeAPhoneTest extends TestCase
{
    /** @test */
    public function user_can_subscribe_a_phone_to_product_id()
    {
        // Arrange
        $msisdn = '07535123123';
        $product_id = 'productid1';

        // Act
        $response = $this->get("/api/subscriptions/subscribe?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(201)
          ->assertJson(['msisdn' => $msisdn, 'product_id' => $product_id]);

        $content = json_decode($response->getContent(), true);

        $subscription = Subscription::find($content['id']);
        $this->assertEquals($subscription->active, 1);
    }
}
