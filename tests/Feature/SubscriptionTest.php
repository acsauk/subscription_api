<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscription as Subscription;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

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
        $active_subscription = factory(Subscription::class)->create();
        $msisdn = $active_subscription->msisdn;
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions/unsubscribe?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
          ->assertJson(['msisdn' => $msisdn, 'product_id' => $product_id, 'active' => 0]);

        $updated_subscription = Subscription::find($active_subscription->id);
        $this->assertEquals($updated_subscription->active, 0);
    }

    /** @test */
    public function user_can_search_for_subscription_with_msisdn_and_product_id()
    {
        // Arrange
        $active_subscription = factory(Subscription::class)->create();

        $msisdn = $active_subscription->msisdn;
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
          ->assertJson(['msisdn' => $msisdn, 'product_id' => $product_id, 'active' => 1]);
    }

    /** @test */
    public function user_can_search_for_subscriptions_with_msisdn()
    {
        // Arrange
        $active_subscription_1 = factory(Subscription::class)->create();
        $active_subscription_2 = factory(Subscription::class)->create(
          ['product_id' => 'productid2']
        );

        // Act
        $response = $this->get("/api/subscriptions?msisdn={$active_subscription_1->msisdn}");

        // Assert
        $response->assertStatus(200)
          ->assertJson([
                          ['msisdn' => $active_subscription_1->msisdn,
                           'product_id' => $active_subscription_1->product_id,
                           'active' => 1],
                           ['msisdn' => $active_subscription_2->msisdn,
                            'product_id' => $active_subscription_2->product_id,
                            'active' => 1]
                       ]);
    }

    /** @test */
    public function user_can_search_for_subscriptions_with_product_id()
    {
        // Arrange
        $active_subscription_1 = factory(Subscription::class)->create();
        $active_subscription_2 = factory(Subscription::class)->create(
          ['msisdn' => '07535123456']
        );

        // Act
        $response = $this->get("/api/subscriptions?product_id={$active_subscription_1->product_id}");

        // Assert
        $response->assertStatus(200)
          ->assertJson([
                          ['msisdn' => $active_subscription_1->msisdn,
                           'product_id' => $active_subscription_1->product_id,
                           'active' => 1],
                           ['msisdn' => $active_subscription_2->msisdn,
                            'product_id' => $active_subscription_2->product_id,
                            'active' => 1]
                       ]);
    }
}
