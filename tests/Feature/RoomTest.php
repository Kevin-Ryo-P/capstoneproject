<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed'); // Seed database sebelum setiap test
    }

    public function test_it_can_list_rooms()
    {
        Room::factory()->count(3)->create();

        $response = $this->get(route('rooms.index'));

        $response->assertStatus(200);
        $response->assertViewHas('rooms');
    }

    public function test_it_can_store_a_room()
    {
        $roomData = [
            'name' => 'Conference Room',
            'location' => 'Building A',
            'capacity' => 50,
        ];

        $response = $this->post(route('rooms.store'), $roomData);

        $this->assertDatabaseHas('rooms', $roomData);
        $response->assertRedirect(route('rooms.index'));
    }

    public function test_it_can_show_a_room()
    {
        $room = Room::factory()->create();

        $response = $this->get(route('rooms.show', $room->id));

        $response->assertStatus(200);
        $response->assertViewHas('room', $room);
    }

    public function test_it_can_delete_a_room()
    {
        $room = Room::factory()->create();

        $response = $this->delete(route('rooms.destroy', $room->id));

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
        $response->assertRedirect(route('rooms.index'));
    }

    public function test_it_can_update_a_room()
    {
        $room = Room::factory()->create();

        $updatedData = [
            'name' => 'Updated Room',
            'location' => 'Updated Building',
            'capacity' => 100,
        ];

        $response = $this->put(route('rooms.update', $room->id), $updatedData);

        $this->assertDatabaseHas('rooms', ['name' => 'Updated Room']);
        $response->assertRedirect(route('rooms.index'));
    }

    public function test_it_can_search_a_room()
    {
        $user = User::factory()->create();  // Login user
        $this->actingAs($user);
        $room = Room::factory()->create(['name' => 'Conference Room']);

        $response = $this->get(route('rooms.search', ['query' => 'Conference']));

        $response->assertStatus(302);
        $response->assertRedirect(route('rooms.show', $room->id));        
    }

    public function test_it_returns_error_when_room_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Pastikan user login

        $response = $this->get(route('rooms.search', ['query' => 'Nonexistent Room']));

        $response->assertStatus(302); // Redirect karena room tidak ditemukan
        $response->assertSessionHas('error', 'Room not found.');
    }

    public function test_it_can_get_room_details()
    {
        $room = Room::factory()->create(['name' => 'Lab 101']);

        $response = $this->get(route('rooms.details', $room->name));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Lab 101',
            'location' => $room->location,
            'capacity' => $room->capacity,
        ]);
    }

    public function test_it_returns_404_if_room_details_not_found()
    {
        $response = $this->get(route('rooms.details', 'Unknown Room'));

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Room not found']);
    }

    public function test_it_can_access_edit_page()
    {
        $room = Room::factory()->create();

        $response = $this->get(route('rooms.edit', $room->id));

        $response->assertStatus(200);
        $response->assertViewHas('room');
    }

    public function test_it_can_list_rooms_page()
    {
        $response = $this->get(route('rooms.list'));

        $response->assertStatus(200);
        $response->assertViewHas('rooms');
    }

}
