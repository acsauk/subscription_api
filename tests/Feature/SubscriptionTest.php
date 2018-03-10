<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscription;

class SubscriptionTest extends TestCase
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

    /** @test */
    public function user_can_unsubscribe_a_phone_from_product_id()
    {
        // Arrange
        $active_subscription = Subscription::all()->first();
        $msisdn = $active_subscription->msisdn;
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions/unsubscribe?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
          ->assertJson(['msisdn' => $msisdn, 'product_id' => $product_id, 'active' => 0]);

        $updated_subscription = Subscription::find($active_subscription);
        $this->assertEquals($active_subscription->active, 0);
    }

    /** @test */
    public function user_can_search_for_subscription_with_msisdn_and_product_id()
    {
        // Arrange
        $active_subscription = Subscription::all()->first();
        $msisdn = $active_subscription->msisdn;
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
          ->assertJson(['msisdn' => $msisdn, 'product_id' => $product_id, 'active' => 1]);
    }
}
