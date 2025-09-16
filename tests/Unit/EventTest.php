<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_an_event_by_id()
    {
        // Membuat event booking di database
        $event = EventBooking::factory()->create();

        // Mengambil event langsung dari database
        $retrievedEvent = EventBooking::findOrFail($event->id);

        // Pastikan event ditemukan dan sesuai dengan data yang dibuat
        $this->assertEquals($event->id, $retrievedEvent->id);
        $this->assertEquals($event->event_name, $retrievedEvent->event_name);
        $this->assertEquals($event->room, $retrievedEvent->room);
    }

    /** @test */
    public function it_returns_error_when_event_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Coba mengambil event yang tidak ada
        EventBooking::findOrFail(999);
    }
}
