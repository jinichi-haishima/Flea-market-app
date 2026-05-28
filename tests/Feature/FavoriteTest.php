<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Order;

class FavoriteTest extends TestCase
{
        use RefreshDatabase;

    public function test_myList_shows_liked_items()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    public function test_mylist_show_buy_items()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($order->buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($order->item->name);
    }

    public function test_guest_user_sees_no_items_on_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

            $favorite = Favorite::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }

    public function test_like_item_count()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('items/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('<p class="like-count">0</p>', false);

        $response = $this->actingAs($user)->post('/like/' . $item->id);
        $response = $this->followRedirects($response);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $response->assertSee('<p class="like-count">1</p>', false);
    }

    public function test_like_item_changes_heart_icon()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('items/' .$item->id);

        $response->assertStatus(200);
        $response->assertSee('heart-gray.png');
        $response->assertSee('alt="いいね前"', false);
        $response->assertDontSee('heart-pink.png');

        $response = $this->actingAs($user)->post('/like/' . $item->id);
        $response = $this->followRedirects($response);

        $this->assertDatabaseHas('favorites',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertSee('heart-pink.png');
        $response->assertSee('alt="いいね後"', false);
        $response->assertDontSee('heart-gray.png');
    }

    public function test_unlike_item_changes_heart_icon()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/items/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('heart-pink.png');
        $response->assertSee('alt="いいね後"', false);
        $response->assertDontSee('heart-gray.png');

        $response = $this->actingAs($user)->delete('/unlike/' . $item->id);
        $response = $this->followRedirects($response);

        $this->assertDatabaseMissing('favorites',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $response->assertSee('heart-gray.png');
        $response->assertSee('alt="いいね前"', false);
        $response->assertDontSee('heart-pink.png');
    }

}