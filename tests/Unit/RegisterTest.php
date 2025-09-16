<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_student_and_redirects()
    {
        $email = fake()->unique()->safeEmail(); // Menyimpan email yang dihasilkan
        
        $request = Request::create('/register-student', 'POST', [
            'name' => 'John Doe',
            'email' => $email,  // Menggunakan email yang disimpan
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $controller = new RegisterController();
        $response = $controller->registerStudent($request);

        // Menggunakan email yang disimpan dalam assertion
        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'student']);
        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function it_registers_a_professor_and_redirects()
    {
        $email = fake()->unique()->safeEmail(); // Menyimpan email yang dihasilkan
        
        $request = Request::create('/register-professor', 'POST', [
            'name' => 'Prof. Jane',
            'email' => $email,  // Menggunakan email yang disimpan
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'professor',
        ]);

        $controller = new RegisterController();
        $response = $controller->registerProfessor($request);

        // Menggunakan email yang disimpan dalam assertion
        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'professor']);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
