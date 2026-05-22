<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /** 1. ログインに必要な情報が入力されていない場合 */
    public function test_email_is_required_for_login()
    {
        $response = $this->post('login', [
            'email' => '',
            'password' => 'password',

        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_password_is_required_for_login()
    {
        $response = $this->post('login', [
            'email' => 'email@example.com',
            'password' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_login_successfully()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        $response = $this->post('login',[
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirectToRoute('users.index');
    }

    /** 3. ログアウトができる */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);
        $this->assertGuest();
        $response->assertRedirectToRoute('users.index');
    }
}