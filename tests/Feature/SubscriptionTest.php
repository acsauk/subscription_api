<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscription as Subscription;
use Carbon\Carbon;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_subscribe_a_phone_to_product_id_query_string()
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
    public function user_can_subscribe_a_phone_to_product_id_international_format()
    {
        // Arrange
        // Mimic auto-encoding in laravel
        $msisdn = '%2B447535123123';
        $product_id = 'productid1';

        // Act
        $response = $this->get("/api/subscriptions/subscribe?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(201)
          // Mimic auto-decoding in laravel
          ->assertJson(['msisdn' => urldecode($msisdn), 'product_id' => $product_id]);

        $content = json_decode($response->getContent(), true);

        $subscription = Subscription::find($content['id']);
        $this->assertEquals($subscription->active, 1);
        // Mimic auto-decoding in laravel
        $this->assertEquals($subscription->msisdn, urldecode($msisdn));
    }

    /** @test */
    public function user_can_subscribe_a_phone_to_product_id_json()
    {
        // Arrange
        $msisdn = '07535123123';
        $product_id = 'productid1';

        $payload = [
            'msisdn' => '07535123123',
            'product_id' => 'productid1'
        ];

        // Act
        $this->json('post', '/api/subscriptions/subscribe', $payload)
        // Assert
            ->assertStatus(201)
            ->assertJsonStructure([
                  'msisdn',
                  'product_id',
                  'created_at',
                  'updated_at',
                  'subscribed_date'
            ]);
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
    public function user_can_unsubscribe_a_phone_from_product_id_international_format()
    {
        // Arrange
        $active_subscription = factory(Subscription::class)->create(
            ['msisdn' => '+447535123123']
        );

        // Mimic auto-encoding in laravel
        $msisdn = urlencode($active_subscription->msisdn);
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions/unsubscribe?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
        // Mimic auto-decoding in laravel
          ->assertJson(['msisdn' => urldecode($msisdn), 'product_id' => $product_id, 'active' => 0]);

        $updated_subscription = Subscription::find($active_subscription->id);
        $this->assertEquals($updated_subscription->active, 0);
    }

    /** @test */
    public function user_can_unsubscribe_a_phone_from_product_id_json()
    {
        // Arrange
        $active_subscription = factory(Subscription::class)->create(
            ['msisdn' => '+447535123123']
        );

        $payload = [
            'msisdn' => $active_subscription->msisdn,
            'product_id' => $active_subscription->product_id
        ];

        // Act
        $this->json('post', '/api/subscriptions/unsubscribe', $payload)
        // Assert
            ->assertStatus(200)
            ->assertJson([
                'msisdn' => [$active_subscription->msisdn],
                'product_id' => [$active_subscription->product_id],
                'active' => [0]
            ]);
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
    public function user_can_search_for_subscription_with_msisdn_and_product_id_international_format()
    {
        // Arrange
        $active_subscription = factory(Subscription::class)->create(
            ['msisdn' => '+447535123123']
        );

        // Mimic auto-encoding in laravel
        $msisdn = urlencode($active_subscription->msisdn);
        $product_id = $active_subscription->product_id;

        // Act
        $response = $this->get("/api/subscriptions?msisdn={$msisdn}&product_id={$product_id}");

        // Assert
        $response->assertStatus(200)
          // Mimic auto-decoding in laravel
          ->assertJson(['msisdn' => urldecode($msisdn), 'product_id' => $product_id, 'active' => 1]);
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
    public function user_can_search_for_subscriptions_with_msisdn_international_format()
    {
        // Arrange
        $active_subscription_1 = factory(Subscription::class)->create(
          ['msisdn' => '+447535123123']
        );
        $active_subscription_2 = factory(Subscription::class)->create(
          ['msisdn' => '+447535123123'],
          ['product_id' => 'productid2']
        );

        // Mimic auto-encoding in laravel
        $msisdn = urlencode($active_subscription_1->msisdn);

        // Act
        $response = $this->get("/api/subscriptions?msisdn={$msisdn}");

        // Assert
        $response->assertStatus(200)
          ->assertJson([
                          // Mimic auto-decoding in laravel
                          ['msisdn' => urldecode($msisdn),
                           'product_id' => $active_subscription_1->product_id,
                           'active' => 1],
                          // Mimic auto-decoding in laravel
                          ['msisdn' => urldecode($msisdn),
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

    /** @test */
    public function user_can_search_for_subscriptions_with_msisdn_alias()
    {
        // Arrange
        $active_subscription_1 = factory(Subscription::class)->create();
        $active_subscription_2 = factory(Subscription::class)->states('alias_msisdn')->create(
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
    public function subscription_date_is_maintained()
    {
      // Arrange
      $msisdn = '07535123123';
      $product_id = 'productid1';

      // Act
      $subscribed_response = $this->get("/api/subscriptions/subscribe?msisdn={$msisdn}&product_id={$product_id}");

      // Assert
      $content = json_decode($subscribed_response->getContent(), true);

      $subscription = Subscription::find($content['id']);
      $this->assertEquals($subscription->subscribed_date, Carbon::now()->format('Y-m-d'));
    }

    /** @test */
    public function unsubscription_date_is_maintained()
    {
      // Arrange
      $active_subscription = factory(Subscription::class)->create();

      // Act
      $unsubscribed_response = $this->get("/api/subscriptions/unsubscribe?msisdn={$active_subscription->msisdn}&product_id={$active_subscription->product_id}");

      // Assert
      $content = json_decode($unsubscribed_response->getContent(), true);

      $subscription = Subscription::find($content['id']);
      $this->assertEquals($subscription->unsubscribed_date, Carbon::now()->format('Y-m-d'));
    }
}
