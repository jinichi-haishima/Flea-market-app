<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/'.$item->id);
        $response->assertStatus(200);

        // StripeのCheckout Sessionをモックする
        $mockSession = Mockery::mock('alias:' . StripeSession::class);
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://dummy-stripe-checkout-url.com']);

        $response = $this->actingAs($user)->post('/purchase/'.$item->id,[
            'payment_selection' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区...',
            'shipping_building' => '丸の内ビル',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('https://dummy-stripe-checkout-url.com');
        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
        ]);
    }

    public function test_purchase_item_is_sold_displayed_as_sold_out()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/'.$item->id);
        $response->assertStatus(200);

        // StripeのCheckout Sessionをモックする
        $mockSession = Mockery::mock('alias:' . StripeSession::class);
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://dummy-stripe-checkout-url.com']);

        $response = $this->actingAs($user)->post('/purchase/'.$item->id,['payment_selection' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区...',
            'shipping_building' => '丸の内ビル',]);

        $response->assertStatus(302);
        $response->assertRedirect('https://dummy-stripe-checkout-url.com');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_purchase_item_is_my_profile_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/'.$item->id);
        $response->assertStatus(200);

        // StripeのCheckout Sessionをモックする
        $mockSession = Mockery::mock('alias:' . StripeSession::class);
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://dummy-stripe-checkout-url.com']);

        $response = $this->actingAs($user)->post('/purchase/'.$item->id,['payment_selection' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区...',
            'shipping_building' => '丸の内ビル',]);

        $response->assertStatus(302);
        $response->assertRedirect('https://dummy-stripe-checkout-url.com');

        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    public function test_payment_method_elements_exist_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/'.$item->id);
        $response->assertStatus(200);

        $response->assertSee('payment_selection', false);
        $response->assertSee('selected-method-display', false);
    }

    public function test_user_can_submit_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->followingRedirects()->post('/purchase/address/'.$item->id, [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区...',
            'shipping_building' => '丸の内ビル',
        ]);

        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区...');
        $response->assertSee('丸の内ビル');
    }

    public function test_user_can_purchase_item_change_address_with_konbini_payment()
{
    $user = User::factory()->create([
        'postal_code' => '000-0000',
        'address' => '東京都新宿区...',
        'building' => '新宿ビル',
    ]);
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->followingRedirects()->post('/purchase/address/'.$item->id, [
        'shipping_postal_code'  => '123-4567',
        'shipping_address'      => '東京都渋谷区...',
        'shipping_building'     => '丸の内ビル',
    ]);

    $response->assertStatus(200);

    $mockSession = Mockery::mock('alias:' . StripeSession::class);
    $mockSession->shouldReceive('create')
        ->once()
        ->andReturn((object)['url' => 'https://dummy-stripe-checkout-url.com']);

    $response = $this->actingAs($user)->post('/purchase/'.$item->id, [
        'payment_selection'     => 'konbini',
        'shipping_postal_code'  => $user->fresh()->postal_code,
        'shipping_address'      => $user->fresh()->address,
        'shipping_building'     => $user->fresh()->building,
    ]);

    $this->assertDatabaseHas('orders', [
        'item_id' => $item->id,
        'buyer_id' => $user->id,
        'payment' => 'konbini',
        'shipping_postal_code' => '123-4567',
        'shipping_address' => '東京都渋谷区...',
        'shipping_building' => '丸の内ビル',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('https://dummy-stripe-checkout-url.com');
}
}