<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_student_registration_form()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    /** @test */
    public function it_displays_the_professor_registration_form()
    {
        $response = $this->get('/dashboardregister');
        $response->assertStatus(200);
        $response->assertViewIs('dashboardregister');
    }

    /** @test */
    public function it_registers_a_student_successfully()
    {
        $response = $this->post('/register', [
            'name' => 'Student Name',
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'student@example.com', 'role' => 'student']);
    }

    /** @test */
    public function it_registers_a_professor_successfully()
    {
        $response = $this->post('/dashboardregister', [
            'name' => 'Prof. John',
            'email' => 'professor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'professor',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'professor@example.com', 'role' => 'professor']);
    }
}
