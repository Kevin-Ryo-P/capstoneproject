<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\EventBooking;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\View\View;
use Mockery;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'Bukayo Saka',
            'email' => 'saka@example.com',
            'password' => bcrypt('secret'),
            'role' => 'Admin',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'saka@example.com']);
    }

    public function test_user_has_role()
    {
        $user = User::factory()->create(['role' => 'Student']);

        $this->assertEquals('Student', $user->role);
    }

    public function test_it_can_update_a_user()
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $user->fresh()->name);
    }

    public function test_it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_has_event_bookings()
    {
        $user = User::factory()->create();
        $eventBooking = EventBooking::factory()->create(['user_id' => $user->id]);
    
        // Reload user untuk mendapatkan hubungan terbaru dari database
        $user->refresh();
    
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->eventBookings);
        $this->assertTrue($user->eventBookings->contains($eventBooking));
    }

    public function test_it_returns_users_view_with_users_data()
    {
        // Buat dummy data pengguna
        User::factory()->count(2)->create();
    
        // Panggil route melalui HTTP request (bukan langsung controller)
        $response = $this->get(route('users.index')); // Pastikan route benar
    
        // Pastikan status OK
        $response->assertStatus(200);
    
        // Pastikan view yang dipanggil benar
        $response->assertViewIs('users');
    
        // Pastikan data 'users' tersedia dalam view
        $response->assertViewHas('users', function ($users) {
            return count($users) === 2;
        });
    }

    public function test_it_calls_index_function_and_returns_users_view()
    {
        // Buat dummy data pengguna
        User::factory()->count(2)->create();
    
        $controller = app(UserController::class);
        $response = $controller->index();
        
        // Pastikan response adalah instance dari View
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
    
        // Pastikan view yang dipanggil benar
        $this->assertEquals('users', $response->getName());
    
        // Pastikan data 'users' tersedia dalam view
        $data = $response->getData();
        $this->assertArrayHasKey('users', $data);
        $this->assertCount(2, $data['users']);
    }
    
    
}