<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'test_user@example.com',
            'password' => bcrypt('password'),
            'name' => 'test_user'

        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'test_user',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
        $user->delete();
    }

    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'wrongname',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Invalid credentials']);
    }

}
