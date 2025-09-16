<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;

class LoginTest extends TestCase
{
    /** @test */
    public function it_redirects_authenticated_users()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        Auth::shouldReceive('attempt')->once()->andReturn(true);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(url('/home'), $response->headers->get('Location'));
    }

    /** @test */
    public function it_returns_back_on_failed_login()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'wrong@example.com',
            'password' => 'invalidpassword',
        ]);

        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('The provided credentials do not match our records.', session('error'));
    }
}
