<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Item_Condition;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_exhibition_page()
    {
        $user = User::factory()->create();
        $condition = Item_Condition::factory()->create();
        $categoryIds = Category::factory()->count(3)->create()->pluck('id')->toArray();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post('/sell',[
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'brand' => 'テストブランド',
            'category_id' => $categoryIds,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'brand' => 'テストブランド',
            'seller_id' => $user->id,
        ]);

        $item = Item::where('name', 'テスト商品')->first();

        $this->assertDatabaseHas('category_item', [
            'category_id' => $categoryIds[0],
            'item_id' => $item->id,
        ]);
    }
}
