<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class commentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_comment_on_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/items/'.$item->id);
        $response->assertStatus(200);
        $response->assertSee('<p class="comment-count">0</p>', false);

        $response = $this->actingAs($user)->post('/items/'.$item->id .'/comments', [
            'content' => 'テストコメント',
        ]);
        $response = $this->followRedirects($response);
        $this->assertDatabaseHas('comments', [
            'content' => 'テストコメント',
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response->assertSee('<p class="comment-count">1</p>', false);
    }

    public function test_guest_cannot_comment_on_item()
    {
        $item = Item::factory()->create();

        $response = $this->get('/items/'.$item->id);
        $response->assertStatus(200);

        $response->assertSee('<p class="comment-count">0</p>', false);

        $response = $this->post('/items/'.$item->id.'/comments', [
            'content' => 'テストコメント',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_comment_content_is_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/items/'.$item->id);
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post('/items/'.$item->id.'/comments',[
            'content' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'content' => 'コメント内容は必須です。',
        ]);
    }

    public function test_comment_content_max_length()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/items/'.$item->id);
        $response->assertStatus(200);

        $longComment = str_repeat('i', 256);
        $response = $this->actingAs($user)->post('/items/'.$item->id.'/comments',[
            'content' => $longComment,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'content' => 'コメント内容は255文字以内で入力してください。',
        ]);
    }
}
