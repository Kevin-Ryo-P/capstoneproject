<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_login_form()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /** @test */
    public function it_allows_users_to_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_rejects_invalid_login_attempts()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'The provided credentials do not match our records.');
        $this->assertGuest();
    }

    /** @test */
    public function it_logs_out_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
