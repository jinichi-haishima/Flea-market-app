<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_mypage_shows_user_info_and_liked_items()
    {
        $user = User::factory()->create();
        $favoriteItem = Item::factory()->create(['name' => 'お気に入り商品']);

        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $favoriteItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee($favoriteItem->name);
        $response->assertSee($favoriteItem->profile_image_url);
        $response->assertSee($favoriteItem->name);
    }

    public function test_profile_page_shows_user_info()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image_url' => 'https://example.com/profile.jpg',
            'address' => '東京都渋谷区',
            'postal_code' => '123-4567',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('https://example.com/profile.jpg');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('123-4567');
    }
}