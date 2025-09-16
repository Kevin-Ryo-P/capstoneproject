<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EventBooking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_event_booking()
    {
        // Siapkan data pengguna (admin)
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Siapkan data room untuk booking
        $room = Room::factory()->create(['name' => 'Room A']);

        // Login pengguna
        $this->actingAs($user);

        // Simulasikan pengiriman request untuk booking event
        $data = [
            'room' => 'Room A',
            'booking_date' => '2025-03-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'event_type' => 'Conference',
            'event_name' => 'Laravel Conference',
            'description' => 'A conference about Laravel.',
            'status' => 'pending',
            'permit_picture' => UploadedFile::fake()->image('permit_picture.jpg')
        ];

        // Kirimkan request untuk membuat booking event
        $response = $this->post(route('event-booking.store'), $data);

        // Verifikasi bahwa event booking berhasil dibuat
        $response->assertStatus(201)
                 ->assertJson(['message' => 'Event booked successfully!']);

        // Verifikasi bahwa data event booking ada di database
        $this->assertDatabaseHas('event_bookings', [
            'room' => 'Room A',
            'event_name' => 'Laravel Conference',
        ]);
    }

    /** @test */
    public function it_prevents_conflict_when_booking_an_event()
    {
        // Siapkan data pengguna (admin)
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Siapkan data room untuk booking
        $room = Room::factory()->create(['name' => 'Room A']);

        // Buat event booking yang sudah ada
        EventBooking::create([
            'room_id' => $room->id,
            'room' => $room->name,
            'booking_date' => '2025-03-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'event_type' => 'Conference',
            'event_name' => 'Existing Conference',
            'status' => 'accepted',
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'location' => $room->location,
        ]);

        // Login pengguna
        $this->actingAs($user);

        // Simulasikan pengiriman request untuk booking event yang bentrok
        $data = [
            'room_id' => $room->id,
            'room' => $room->name,
            'booking_date' => '2025-03-10',
            'start_time' => '11:00',
            'end_time' => '13:00',
            'event_type' => 'Conference',
            'event_name' => 'New Conference',
            'description' => 'Another conference.',
            'status' => 'pending',
        ];

        // Kirimkan request untuk booking event yang bentrok
        $response = $this->post(route('event-booking.store'), $data);

        // Pastikan response mengembalikan error tentang bentroknya jadwal
        $response->assertStatus(422)
                 ->assertJson(['message' => 'The room is already booked for the selected date and time.']);
    }

    /** @test */
    public function it_can_get_all_events()
    {
        $response = $this->getJson('/api/event-bookings');  // Menggunakan URL langsung
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => ['id', 'event_name', 'room', 'booking_date', 'start_time', 'end_time', 'status', 'name', 'role', 'user_id', 'description', 'location', 'permit_picture']
                ]);
    }

    /** @test */
    public function it_can_update_event_status()
    {
        // Siapkan data pengguna dan event
        $user = User::factory()->create();
        $event = EventBooking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        // Login pengguna
        $this->actingAs($user);

        // Kirimkan request untuk memperbarui status
        $response = $this->post(route('event-booking.updateStatus', ['id' => $event->id]), [
            'status' => 'accepted',
        ]);

        // Verifikasi bahwa status telah diperbarui di database
        $event->refresh();
        $this->assertEquals('accepted', $event->status);

        // Verifikasi response
        $response->assertRedirect()
                 ->assertSessionHas('success', 'Event status updated successfully.');
    }
    
    /** @test */
    public function it_can_show_dashboard_with_pending_events()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        EventBooking::factory()->create(['status' => 'pending']);
        $response = $this->get(route('dashboard.konfirmasi'));

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    /** @test */
    public function it_can_bulk_update_event_statuses()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $events = EventBooking::factory()->count(3)->create(['status' => 'pending']);

        $this->actingAs($user);

        $data = [
            'statuses' => [
                $events[0]->id => 'accepted',
                $events[1]->id => 'rejected',
                $events[2]->id => 'canceled',
            ]
        ];

        $response = $this->post(route('event-booking.bulkUpdate'), $data);
        $response->assertRedirect(route('dashboard.konfirmasi'));
        $this->assertDatabaseHas('event_bookings', ['id' => $events[0]->id, 'status' => 'accepted']);
    }

    /** @test */
    public function it_can_show_booking_history()
    {
        EventBooking::factory()->create();
        $response = $this->get(route('booking.history'));
        $response->assertStatus(200);
        $response->assertViewHas('bookings');
    }

    /** @test */
    public function it_can_show_accepted_events_for_today()
    {
        EventBooking::factory()->create(['status' => 'accepted', 'booking_date' => now()->toDateString()]);
        $response = $this->get(route('accepted.events'));
        $response->assertStatus(200);
        $response->assertViewHas('acceptedBookings');
    }

    /** @test */
    public function it_can_delete_a_booking()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $event = EventBooking::factory()->create();
        
        $this->actingAs($user);
        $response = $this->delete(route('event-booking.delete', $event->id));

        $response->assertRedirect(route('accepted.events'));
        $this->assertDatabaseMissing('event_bookings', ['id' => $event->id]);
    }

    /** @test */
    public function it_can_show_user_bookings()
    {
        $user = User::factory()->create();
        EventBooking::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get(route('user.bookings'));

        $response->assertStatus(200);
        $response->assertViewHas('bookings');
    }

    public function test_it_can_fetch_all_events()
    {
        // Siapkan beberapa data EventBooking menggunakan factory
        $event = EventBooking::factory()->create();  // Buat satu event
        
        // Kirimkan permintaan GET ke endpoint index
        $response = $this->get(route('eventBookings.index')); // Ganti dengan route yang sesuai

        // Pastikan respons memiliki status 200
        $response->assertStatus(200);

        // Pastikan respons berupa JSON yang berisi data event
        $response->assertJsonFragment([
            'id' => $event->id,
            'event_name' => $event->event_name,
            // Pastikan data lainnya sesuai dengan field yang ada pada model EventBooking
        ]);
    }

    public function test_it_updates_statuses_via_api()
    {
        $event = EventBooking::factory()->create(); // Buat booking untuk diuji

        $response = $this->json('POST', '/api/event-booking/bulk-update', [
            'statuses' => [
                $event->id => 'accepted',
            ],
        ]);

        $response->assertStatus(200);
        $this->assertEquals('accepted', $event->fresh()->status);
    }

    public function test_it_updates_statuses_via_web()
    {
        $event = EventBooking::factory()->create();

        $response = $this->post(route('events.bulkUpdate'), [
            'statuses' => [
                $event->id => 'accepted',
            ],
        ]);

        $response->assertRedirect(route('dashboard.konfirmasi'));
        $this->assertEquals('accepted', $event->fresh()->status);
    }

    /** @test */
    public function it_deletes_booking_and_redirects_when_not_json_request()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $event = EventBooking::factory()->create();

        // Simulasikan request tanpa JSON (Web Request biasa)
        $response = $this->delete(route('event-booking.delete', $event->id));

        // Pastikan redirect ke halaman accepted.events
        $response->assertRedirect(route('accepted.events'));

        // Pastikan booking terhapus dari database
        $this->assertDatabaseMissing('event_bookings', ['id' => $event->id]);
    }

    /** @test */
    public function it_returns_302_if_user_is_not_logged_in()
    {
        $event = EventBooking::factory()->create();

        // Jangan gunakan $this->actingAs(), karena kita ingin menguji tanpa login
        $response = $this->delete(route('event-booking.delete', $event->id));

        // Pastikan response adalah 302 Forbidden
        $response->assertStatus(302);
    }

    /** @test */
    public function it_redirects_admin_to_accepted_events_after_deleting_booking()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $event = EventBooking::factory()->create();

        // Simulasikan request dari Web (tanpa JSON)
        $response = $this->delete(route('event-booking.delete', $event->id));

        // Pastikan redirect ke accepted.events
        $response->assertRedirect(route('accepted.events'));

        // Pastikan ada flash message untuk sukses
        $response->assertSessionHas('success', 'Booking deleted successfully.');

        // Pastikan booking terhapus dari database
        $this->assertDatabaseMissing('event_bookings', ['id' => $event->id]);
    }

}