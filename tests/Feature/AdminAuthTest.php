<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase; // Membersihkan database setelah setiap pengujian

    /** @test */
    public function it_displays_the_login_form()
    {
        // Mengakses route login form
        $response = $this->get(route('admin.login')); 

        // Memastikan response mengembalikan tampilan yang benar
        $response->assertStatus(200)
                 ->assertViewIs('admin.admin'); 
    }

    /** @test */
    public function it_allows_admin_to_login()
    {
        // Menyiapkan user admin
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), 
            'role' => 'admin'
        ]);

        // Mengirimkan login request dengan email dan password yang valid
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        // Pastikan admin diarahkan ke dashboard setelah login berhasil
        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function it_redirects_non_admin_users()
    {
        // Menyiapkan user biasa (bukan admin)
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'), 
            'role' => 'user'
        ]);

        // Mengirimkan login request dengan kredensial yang valid
        $response = $this->post(route('admin.login'), [
            'email' => 'user@example.com',
            'password' => 'password'
        ]);

        // Pastikan user di-logout dan mendapat error
        $response->assertRedirect()
                 ->assertSessionHasErrors(['email' => 'You do not have access to this area.']);
        $this->assertFalse(Auth::check()); // Pastikan tidak ada user yang login
    }

    /** @test */
    public function it_shows_error_when_invalid_credentials()
    {
        // Mengirimkan login request dengan kredensial yang salah
        $response = $this->post(route('admin.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        // Pastikan ada error dan kembali ke halaman login
        $response->assertRedirect()
                 ->assertSessionHasErrors(['email' => 'Invalid credentials']);
    }

    /** @test */
    public function it_displays_the_dashboard_for_authenticated_admin()
    {
        // Menyiapkan user admin yang sudah login
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Login sebagai admin
        $this->actingAs($admin);

        // Mengakses dashboard setelah login
        $response = $this->get(route('admin.dashboard'));

        // Pastikan tampilan dashboard ditampilkan
        $response->assertStatus(200)
                 ->assertViewIs('dashboardhome'); 
    }
}
