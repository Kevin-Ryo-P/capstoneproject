<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    /** @test */
    public function it_hashes_password_correctly()
    {
        $password = 'password';
        $hashedPassword = Hash::make($password);

        // Pastikan password yang di-hash tidak sama dengan plaintext password
        $this->assertNotEquals($password, $hashedPassword);

        // Pastikan password yang di-hash bisa diverifikasi
        $this->assertTrue(Hash::check($password, $hashedPassword));
    }

    /** @test */
    public function it_creates_admin_user_correctly()
    {
        // Membuat user admin tanpa database
        $admin = new User([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Pastikan user memiliki atribut yang sesuai
        $this->assertEquals('admin@example.com', $admin->email);
        $this->assertEquals('admin', $admin->role);
        $this->assertTrue(Hash::check('password', $admin->password));
    }
}
