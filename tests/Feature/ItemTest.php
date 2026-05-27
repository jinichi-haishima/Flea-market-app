<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Models\Item_Condition;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Category;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_all_items_can_be_displayed()
    {
        $item1 = Item::factory()->create();
        $item2 = Item::factory()->create();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertSee($item2->name);
    }

    public function test_sold_item_is_SOLD_OUT()
    {
        $condition = Item_Condition::factory()->create();

        $item = Item::factory()->create(['condition_id' => $condition->id]);
        $buyer = User::factory()->create();

        Order::create([
            'item_id'              => $item->id,
            'buyer_id'             => $buyer->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address'     => '東京都渋谷区...',
            'shipping_building'    => '丸の内ビル',
            'payment'              => 'card',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_index_page_my_selled_items_are_not_displayed()
    {
        $seller = User::factory()->create();
        $myItem = Item::factory()->create(['seller_id' => $seller->id]);

        $otherUser = User::factory()->create();
        $otherItem = Item::factory()->create(['seller_id' => $otherUser->id]);

        $response = $this->actingAs($seller)->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }

    public function test_search_function_can_find_items_by_name()
    {
        $item1 = Item::factory()->create(['name' => 'Unique Item']);
        $item2 = Item::factory()->create(['name' => 'Another Item']);

        $response = $this->get('/?keyword=Unique');

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    public function test_search_keyword_is_retained_on_mylist_page()
    {
        $item =Item::factory()->create(['name' => 'Test Item']);
        $user = User::factory()->create();
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?keyword=Test');

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee('value="Test"', false);
    }

    public function test_show_page_displays_item_details()
    {
        $item = Item::factory()
            ->has(Comment::factory()->count(3))
            ->has(Category::factory()->count(2))
            ->create();

        $response = $this->get('/items/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee($item->image_url);
        $response->assertSee($item->name);
        $response->assertSee($item->description);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->itemCondition->condition);
        $response->assertSee($item->favorites->count());
        $response->assertSee($item->comments->count());
        foreach ($item->comments as $comment) {
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->content);
        }
        foreach ($item->categories as $category){
            $response->assertSee($category->name);
        }

    }
}