<?php

namespace Tests\Unit;

use App\Models\Room;
use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_room()
    {
        $room = Room::create([
            'name' => 'Lab 101',
            'location' => 'Building B',
            'capacity' => 30,
        ]);

        $this->assertDatabaseHas('rooms', ['name' => 'Lab 101']);
    }

    public function test_room_has_capacity()
    {
        $room = Room::factory()->create(['capacity' => 50]);

        $this->assertEquals(50, $room->capacity);
    }

    public function test_it_can_update_a_room()
    {
        $room = Room::factory()->create();

        $room->update(['name' => 'Updated Room']);

        $this->assertEquals('Updated Room', $room->fresh()->name);
    }

    public function test_it_can_delete_a_room()
    {
        $room = Room::factory()->create();

        $room->delete();

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_room_has_bookings()
    {
        $room = Room::factory()->create();
        $booking = EventBooking::factory()->create(['room_id' => $room->id]);
   
        // Memuat relasi eventBookings untuk memastikan relasi sudah terload
        $room->load('bookings');

        $this->assertTrue($room->bookings->contains($booking));
    }

    public function test_it_can_fetch_available_rooms()
    {
        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();
        EventBooking::factory()->create(['room_id' => $room1->id]); // Room1 terbooking

        $availableRooms = Room::availableRooms()->get();

        $this->assertTrue($availableRooms->contains($room2));
        $this->assertFalse($availableRooms->contains($room1));
    }

}
