<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\EventBooking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_event_booking_creation_logic_without_http()
    {
        // Siapkan data pengguna (admin)
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Siapkan data room untuk booking
        $room = Room::factory()->create(['name' => 'Room A']);

        // Simulasikan proses pembuatan event booking
        $eventBooking = EventBooking::create([
            'name' => 'Sample Event',
            'room' => 'Room A',
            'room_id' => '1',
            'booking_date' => '2025-03-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'event_type' => 'Conference',
            'event_name' => 'Unit Test Conference',
            'status' => 'pending',
            'user_id' => $user->id,
            'role' => 'admin',
        ]);

        // Verifikasi bahwa event booking berhasil dibuat dalam database tanpa request HTTP
        $this->assertDatabaseHas('event_bookings', [
            'room' => 'Room A',
            'event_name' => 'Unit Test Conference',
        ]);
    }

    /** @test */
    public function it_checks_event_booking_status_change_without_http()
    {
        // Siapkan data pengguna dan event booking
        $user = User::factory()->create([
            'role' => 'admin', // Pastikan ada nilai role
        ]);
        $event = EventBooking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        // Ubah status event tanpa request HTTP
        $event->status = 'accepted';
        $event->save();

        // Verifikasi bahwa status event berhasil diperbarui
        $this->assertEquals('accepted', $event->status);
    }

    /** @test */
    public function it_can_bulk_update_event_statuses()
    {
        // Buat user terlebih dahulu
        $user = User::factory()->create(['role' => 'admin']);

        // Buat event dengan user_id yang valid
        $events = EventBooking::factory()->count(3)->create(['user_id' => $user->id, 'status' => 'pending']);

        $statuses = [
            $events[0]->id => 'accepted',
            $events[1]->id => 'rejected',
            $events[2]->id => 'canceled',
        ];

        foreach ($statuses as $eventId => $status) {
            EventBooking::where('id', $eventId)->update(['status' => $status]);
        }

        foreach ($statuses as $eventId => $expectedStatus) {
            $this->assertDatabaseHas('event_bookings', ['id' => $eventId, 'status' => $expectedStatus]);
        }
    }


    /** @test */
    public function it_can_delete_a_booking()
    {
        $event = EventBooking::factory()->create();
        $event->delete();
        $this->assertDatabaseMissing('event_bookings', ['id' => $event->id]);
    }

    /** @test */
    public function it_can_delete_an_event()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = EventBooking::factory()->create(['user_id' => $user->id]);
        
        // Login sebagai user
        $this->actingAs($user);

        // Panggil API delete
        $response = $this->deleteJson("/api/event-booking/{$event->id}");

        // Periksa response
        $response->assertStatus(200)
                ->assertJson(['message' => 'Booking deleted successfully.']);

        // Pastikan event terhapus
        $this->assertDatabaseMissing('event_bookings', ['id' => $event->id]);
    }

    /** @test */
    public function it_returns_404_if_event_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Panggil API delete pada event yang tidak ada
        $response = $this->deleteJson("/api/event-booking/999");

        $response->assertStatus(404)
                ->assertJson(['message' => 'Booking not found.']);
    }

    /** @test */
    public function it_prevents_deleting_event_of_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $event = EventBooking::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);

        // User2 mencoba menghapus event User1
        $response = $this->deleteJson("/api/event-booking/{$event->id}");

        $response->assertStatus(403)
                ->assertJson(['message' => 'Unauthorized']);

        // Event masih ada di database
        $this->assertDatabaseHas('event_bookings', ['id' => $event->id]);
    }

    /** @test */
    public function it_can_get_all_events()
    {
        // Buat user dan event
        $user = User::factory()->create();
        EventBooking::factory()->create([
            'user_id' => $user->id,
            'event_name' => 'Meeting',
            'room' => 'Room A',
            'booking_date' => '2025-03-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'status' => 'approved',
            'description' => 'Quarterly meeting',
            'location' => 'Office',
            'permit_picture' => 'permit.jpg'
        ]);

        // Panggil API getEvents
        $response = $this->getJson('/api/events');

        // Periksa response
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'title' => 'Meeting',
                     'room' => 'Room A',
                     'booking_date' => '2025-03-10',
                     'status' => 'approved',
                 ]);
    }

    /** @test */
    public function it_can_upload_permit_picture()
    {
        Storage::fake('public'); // Simulasi storage agar bisa diuji dengan `assertExists`

        $user = User::factory()->create();
        $room = Room::factory()->create(['name' => 'Room A']);

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('permit_picture.jpg');

        $data = [
            'room' => 'Room A',
            'booking_date' => '2025-03-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'event_type' => 'Conference',
            'event_name' => 'Test Event',
            'status' => 'pending',
            'permit_picture' => $file,
        ];

        $response = $this->post(route('event-booking.store'), $data);

        // Pastikan response sukses
        $response->assertStatus(201);

        $this->assertTrue(
            Storage::disk('public')->exists('permits/' . $file->hashName()),
            "File permit tidak ditemukan di storage."
        );

    }

}